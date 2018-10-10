<?php
namespace Keros\Controllers\Ua;

use Keros\Entities\Ua\Firm;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Ua\FirmService;

use Keros\Entities\Core\Address;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FirmController
{
    /**
     * @var AddressService
     */
    private $addressService;
    /**
     * @var FirmService
     */
    private $firmService;
    /**
     * @var Logger
     */
    private $logger;
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->addressService=$container->get(AddressService::class);


        $this->firmService = $container->get(FirmService::class);
    }
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing one firm if it exists
     * @throws KerosException if the validation fails
     */
    public function getFirm(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting firm by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);
        $firm = $this->firmService->getOne($id);
        if (!$firm) {
            throw new KerosException("The firm could not be found", 400);
        }
        return $response->withJson($firm, 200);
    }
    /**
     * @return Response containing the created firm
     * @throws KerosException if the validation fails or the firm cannot be created
     */
    public function createFirm(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating firm from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $siret = Validator::int($body["siret"]);
        $line1= Validator::name(($body["address"])["line1"]);
        $line2=Validator::name(($body["address"])["line2"]);
        $postalCode=Validator::int(($body["address"])["postalCode"]);
        $city=Validator::name(($body["address"])["city"]);
        $countryId=Validator::name(($body["address"])["countryId"]);
        $name = Validator::name($body["name"]);
        $address=new Address($line1,$line2,$postalCode,$city);

        $this->addressService->create($address,$countryId);

        $typeId = Validator::float($body["typeId"]);

        $firm = new Firm($name,$siret);
        $this->firmService->create($firm,$typeId, $address);

        return $response->withJson($firm, 201);
    }
    public function updateFirm(Request $request, Response $response, array $args)
    {
        $this->logger->debug("updating firm from " . $request->getServerParams()["REMOTE_ADDR"]);
        $firmId = Validator::id($args['id']);

        $body = $request->getParsedBody();
        $siret = Validator::name($body["siret"]);
        $line1= Validator::name(($body["address"])["line1"]);
        $line2=Validator::name(($body["address"])["line2"]);
        $postalCode=Validator::int(($body["address"])["postalCode"]);
        $city=Validator::name(($body["address"])["city"]);
        $countryId=Validator::name(($body["address"])["countryId"]);
        $name = Validator::name($body["name"]);


        $address=new Address($line1,$line2,$postalCode,$city);
        $this->addressService->create($address,$countryId);

        $typeId = Validator::float($body["typeId"]);


        $firm=$this->firmService->update($firmId,$typeId, $address,$siret,$name);

        return $response->withJson($firm, 201);
    }
    /**
     * @return Response containing a page of firms
     * @throws KerosException if an unknown error occurs
     */
    public function getPageFirms(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page firms from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Address::getSearchFields());
        $firms = $this->firmService->getMany($params);
        $totalCount = $this->firmService->getCount($params);
        $page = new Page($firms, $params, $totalCount);
        return $response->withJson($page, 200);
    }
}
