<?php

namespace Keros\Tools\Authorization;

use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthenticationMiddleware
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var JwtCodec
     */
    private $jwtCodec;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->jwtCodec = $container->get(JwtCodec::class);
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        // If testing, assume user 1 is connected
        if(ConfigLoader::getConfig()['isTesting']){
            $response = $next($request->withAttribute("userId",1), $response);
            return $response;
        }

        $authorizationHeaders = $request->getHeader("Authorization");
        if (empty($authorizationHeaders)) {
            throw new KerosException("Authentication header not found", 401);
        }
        $token = $authorizationHeaders[0];
        if (!isset($token)) {
            throw new KerosException("Authentication token not found", 401);
        }

        $payload = $this->jwtCodec->decode($token);

        $response = $next($request->withAttribute("userId", $payload->id), $response);

        return $response;
    }
}