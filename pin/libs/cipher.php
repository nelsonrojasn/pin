<?php

function encriptar(string $dato, string $clave): string {
    $metodo = 'aes-256-cbc';
    $iv_longitud = openssl_cipher_iv_length($metodo); // 16 bytes
    $iv = openssl_random_pseudo_bytes($iv_longitud);
    
    // Usamos OPENSSL_RAW_DATA para obtener el cifrado binario puro (más compacto)
    $cifrado_raw = openssl_encrypt($dato, $metodo, $clave, OPENSSL_RAW_DATA, $iv);
    
    // Calculamos una firma para asegurarnos de que nadie altere el SQLite en disco
    $hmac = hash_hmac('sha256', $iv . $cifrado_raw, $clave, true); // 32 bytes
    
    // Empaquetamos todo secuencialmente: IV (16) + HMAC (32) + Datos Cifrados
    $b64 = base64_encode($iv . $hmac . $cifrado_raw);
    return str_replace(['+', '/', '='], ['-', '_', ''], $b64);
}

function desencriptar(string $datoCifrado, string $clave): string {
    $metodo = 'aes-256-cbc';
    $b64 = str_replace(['-', '_'], ['+', '/'], $datoCifrado);
    // PHP maneja el padding (=) faltante de forma automática en base64_decode
    $payload = base64_decode($b64);
    
    
    $iv_longitud = openssl_cipher_iv_length($metodo); // 16
    $hmac_longitud = 32; // SHA256 raw son 32 bytes
    
    // Extraemos las partes usando los tamaños fijos por posición
    $iv = substr($payload, 0, $iv_longitud);
    $hmac_recibido = substr($payload, $iv_longitud, $hmac_longitud);
    $cifrado_raw = substr($payload, $iv_longitud + $hmac_longitud);
    
    // Verificamos la firma antes de gastar ciclos en desencriptar
    $hmac_calculado = hash_hmac('sha256', $iv . $cifrado_raw, $clave, true);
    
    // hash_equals nos protege de ataques de sincronización (timing attacks)
    if (!hash_equals($hmac_recibido, $hmac_calculado)) {
        return ''; // Datos manipulados o clave incorrecta
    }
    
    return openssl_decrypt($cifrado_raw, $metodo, $clave, OPENSSL_RAW_DATA, $iv) ?: '';
}