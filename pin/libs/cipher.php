<?php

function encriptar(string $dato, string $clave): string {
    $metodo = 'aes-256-cbc';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($metodo));
    $cifrado = openssl_encrypt($dato, $metodo, $clave, 0, $iv);
    // Guardamos el IV junto con el dato cifrado para poder desencriptar después
    return base64_encode($cifrado . '::' . $iv);
}

// Función para desencriptar
function desencriptar(string $datoCifrado, string $clave): string {
    $metodo = 'aes-256-cbc';
    list($cifrado, $iv) = explode('::', base64_decode($datoCifrado), 2);
    return openssl_decrypt($cifrado, $metodo, $clave, 0, $iv);
}