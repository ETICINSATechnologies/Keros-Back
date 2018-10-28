<?php

namespace Keros\Entities\Auth;


use Keros\Error\KerosException;

class LoginResponse
{
    protected static $secretKey = "weAreTheBestDevsIn3tic";

    protected static $alg = "sha256";

    /**
     * @var array|string
     */
    protected static $header =
        [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

    public static function encodeBase64($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public static function decodeBase64($data)
    {
        return base64_decode($data);
    }

    public static function encode(array $payload)
    {
        // transform header and payload array into json
        $header = json_encode(self::$header);
        $payload = json_encode($payload);

        // encode the header and the payload
        $base64UrlHeader = self::encodeBase64($header);
        $base64UrlPayload = self::encodeBase64($payload);

        // create signature
        $signature = hash_hmac(self::$alg, $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);

        // encode signature
        $base64UrlSignature = self::encodeBase64($signature);

        // Create JWT
        return
            [
                "token" => $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature
            ];
    }

    public static function decode(String $jwt)
    {
        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = explode('.', $jwt);
        $signature = hash_hmac(self::$alg, $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        if ($base64UrlSignature == self::encodeBase64($signature))
        {
            return json_decode(self::decodeBase64($base64UrlPayload));
        }

        throw new KerosException("The JWT is invalid", 404);
    }
}