<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BCI Credentials
    |--------------------------------------------------------------------------
    |
    | These values set the credentials for the environment that will be used on
    | requests to the API.
    |
    */
   
    'key' => env('BCI_KEY'),
    'secret' => env('BCI_SECRET'),
    'app_id' => env('BCI_APP_ID'),
    'return_uri' => env('BCI_RETURN_URI', env('app.url')),

    /*
    |--------------------------------------------------------------------------
    | Sandbox mode
    |--------------------------------------------------------------------------
    |
    | This option controls the default state for the sandbox. By default
    | the sandbox es false
    |
    */
    'sandbox' => env('BCI_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | Private pem file path
    |--------------------------------------------------------------------------
    |
    */
    'private' => env('BCI_PRIVATE', 'private.pem'),
];
