<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Integración con API BCI
    |--------------------------------------------------------------------------
    |
    | Aqui se especifican las variables para conectarse a la API, y definir
    | si se usa la direccion de prueba (sandbox), o produccion.
    |
    */

    'url' => env('BCI_URL'),
    'password' => env('SII_PASSWORD'),
    'provider' => env('SII_PROVIDER'),
];
