<?php

namespace Keros\Tools\Authorization;


use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class JwtCodec
{

    /**
     * @var \Monolog\Logger
     */
    private $logger;

    function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
    }

    public function encode(array $payload): String
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
        $base64UrlHeader = $this->encodeBase64($header);
        $base64UrlPayload = $this->encodeBase64($payload);

        // create signature
        $signature = hash_hmac($hash, $base64UrlHeader . "." . $base64UrlPayload, $secretKey, true);

        // encode signature
        $base64UrlSignature = self::encodeBase64($signature);

        // return the token
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function decode(String $jwt)
    {
        $kerosConfig = ConfigLoader::getConfig();
        $hash = $kerosConfig["HASH"];
        $secretKey = $kerosConfig["SECRET_KEY"];

        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = explode('.', $jwt);
        $signature = hash_hmac($hash, $base64UrlHeader . "." . $base64UrlPayload, $secretKey, true);

        // check if the $signature is the same as expected
        if ($base64UrlSignature == $this->encodeBase64($signature)) {
            return json_decode($this->decodeBase64($base64UrlPayload));
        }

        throw new KerosException("The JWT is invalid", 400);
    }

    public function encodeBase64($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public function decodeBase64($data)
    {
        return base64_decode($data);
    }
}