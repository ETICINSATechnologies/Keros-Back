<?php
namespace Keros\Controllers\Core;

use Keros\Entities\core\Address;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\CountryService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddressController
{
    /**
     * @var AddressService
     */
    private $addressService;
    /**
     * @var Logger
     */
    private $logger;
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->addressService = new AddressService($container);
    }
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing one address if it exists
     * @throws KerosException if the validation fails
     */
    public function getAddress(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting address by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);
        $address = $this->addressService->getOne($id);
        if (!$address) {
            throw new KerosException("The address could not be found", 400);
        }
        return $response->withJson($address, 200);
    }
    /**
     * @return Response containing the created address
     * @throws KerosException if the validation fails or the address cannot be created
     */
    public function createAddress(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating address from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $line1 = Validator::name($body["line1"]);
        $line2 = Validator::name($body["line2"]);
        $postalCode = Validator::float($body["postalCode"]);
        $city = Validator::name($body["city"]);
        $countryId = Validator::float($body["countryId"]);

        $address = new Address($line1, $line2, $postalCode, $city);
        $this->addressService->create($address, $countryId);
        return $response->withJson($address, 201);
    }
    /**
     * @return Response containing a page of addresss
     * @throws KerosException if an unknown error occurs
     */
    public function getPageAddresses(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page addresses from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Address::getSearchFields());
        $addresses = $this->addressService->getMany($params);
        $totalCount = $this->addressService->getCount($params);
        $page = new Page($addresses, $params, $totalCount);
        return $response->withJson($page, 200);
    }
}
