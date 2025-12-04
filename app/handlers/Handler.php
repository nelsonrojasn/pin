<?php

namespace App\Handlers;

/**
 * Handler base
 * Clase abstracta para todos los handlers
 */
abstract class Handler
{
    /**
     * Método principal que deben implementar todos los handlers
     * @param array $params Parámetros de la ruta
     */
    abstract public function handle(array $params = []);
}
