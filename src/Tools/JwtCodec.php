<?php

namespace Keros\Tools;


use Keros\Error\KerosException;

class JwtCodec
{
    public static function encode(array $payload): String
    {
        $kerosConfig = ConfigLoader::getConfig();
        $alg = $kerosConfig["ALG"];
        $hash = $kerosConfig["HASH"];
        $secretKey = $kerosConfig["SECRET_KEY"];

        // creation of the header
        $header = json_encode(array(
            "alg" => $alg,
            "typ" => "JWT"
        ));

        // creation of the payload
        $payload = json_encode($payload);

        // encode the header and the payload
        $base64UrlHeader = self::encodeBase64($header);
        $base64UrlPayload = self::encodeBase64($payload);

        // create signature
        $signature = hash_hmac($hash, $base64UrlHeader . "." . $base64UrlPayload, $secretKey, true);

        // encode signature
        $base64UrlSignature = self::encodeBase64($signature);

        // return the token
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function decode(String $jwt)
    {
        $kerosConfig = ConfigLoader::getConfig();
        $hash = $kerosConfig["HASH"];
        $secretKey = $kerosConfig["SECRET_KEY"];

        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = explode('.', $jwt);
        $signature = hash_hmac($hash, $base64UrlHeader . "." . $base64UrlPayload, $secretKey, false);

        // check if the $signature is the same as expected
        if ($base64UrlSignature == self::encodeBase64($signature ))
        {
            return json_decode(self::decodeBase64($base64UrlPayload));
        }

        throw new KerosException("The JWT is invalid", 404);
    }

    public static function encodeBase64($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public static function decodeBase64($data)
    {
        return base64_decode($data);
    }
}