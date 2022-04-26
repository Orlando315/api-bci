<?php

namespace App\Integrations\Bci\Utils;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Exception;

class Utils
{
    /**
     * JWT
     *
     * @param  array  $key
     * @return string
     */
    public static function buildJWT(array $payload)
    {

        return static::encode($payload);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public static function generateNonce(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public static function generateState(): string
    {
        return (string) Uuid::uuid1();
    }

    /**
     * Undocumented function
     *
     * @param array $payload
     * @param [type] $key
     * @return void
     */
    private static function encode(array $payload)
    {
        $privateKey = Storage::get(config('bci.private'));

        $header = ['typ' => 'JWT', 'alg' => 'RS256'];

        $segments = [];
        $segments[] = static::urlsafeB64Encode((string) json_encode($header, JSON_UNESCAPED_SLASHES));
        $segments[] = static::urlsafeB64Encode((string) json_encode($payload, JSON_UNESCAPED_SLASHES));
        $signing_input = implode('.', $segments);

        $signature = static::sign($signing_input, $privateKey);
        $segments[] = static::urlsafeB64Encode($signature);

        return implode('.', $segments);
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     * @return string The base64 encode of what you passed in
     */
    private static function urlsafeB64Encode(string $input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * Sign a string with a given key and algorithm.
     *
     * @param  string $msg  The message to sign
     * @param  string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate  $key  The secret key.
     * @return string An encrypted message
     *
     * @throws Exception Unsupported algorithm or bad key was specified
     */
    public static function sign(string $msg, $key): string
    {
        $signature = '';
        $success = openssl_sign($msg, $signature, $key, 'SHA256');

        if (! $success) {
            throw new Exception('OpenSSL unable to sign data');
        }

        return $signature;
    }

}
