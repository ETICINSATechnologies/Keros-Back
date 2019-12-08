<?php


namespace Keros\Services\Treso;




use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Keros\DataServices\Treso\TurnoverDataService;
use Keros\Entities\Treso\Turnover;
use Keros\Error\KerosException;
use Keros\Tools\Validator;

use Psr\Container\ContainerInterface;
use Keros\Entities\Core\RequestParameters;
class TurnoverService
{
    /** @var TurnoverDataService */
    private $turnoverDataService;

    /**
     * TurnoverService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->turnoverDataService=$container->get(TurnoverDataService::class);
    }

    /**
     * @param array $fields
     * @return Turnover
     * @throws KerosException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $fields): Turnover
    {
        //instead of manually enter the current date time, we call the
        //$date=Validator::requiredDate($fields["date"]);
        $str="now";
        $today=new DateTime($str);
        //$dateFormat="Y-m-d";
        //$todayFormatted=date($dateFormat);
        $revenue=Validator::requiredFloat($fields["revenue"]);
        $turnover=new Turnover($today,$revenue);
        $this->turnoverDataService->persist($turnover);
        return $turnover;
    }

    //todo
    /**
     * question:
     * I try to copy your pattern from other modules
     * but I don't understand why we should get the object by id then pass it to the delete funcion
     * instead of delete by id directly
     */
    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $turnover = $this->getOne($id);
        $this->factureDataService->delete($turnover);
    }

    public function getOne(int $id): Turnover
    {
        $id = Validator::requiredId($id);

        $turnover = $this->turnoverDataService->getOne($id);
        if (!$turnover) {
            throw new KerosException("The member could not be found", 404);
        }
        return $turnover;
    }
    /**
     * @return Turnover[]|null
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->turnoverDataService->getAll();
    }

    /**
     * @param DateTime $date
     * @return Turnover
     * @throws KerosException
     */
    public function getByDate($date):Turnover
    {
        $date = Validator::requiredDate($date);
        $turnover=$this->getByDate($date);
        if (!$turnover) {
            throw new KerosException("The turnover of that date could not be found", 404);
        }
        return $turnover;
    }

    public function getCount(?RequestParameters $requestParameters): int
    {
        return $this->turnoverDataService->getCount($requestParameters);
    }


    //todo:getByDay(not DateTime)
    //todo::getByIntervalOfDays

    /**
     * @return Turnover
     * @throws KerosException
     */
    public function getLatest():Turnover
    {
        $turnovers=$this->turnoverDataService->getAll();
        $latestTurnover=null;
        foreach($turnovers as $turnover){
            if($latestTurnover==null||$turnover->getDate()>$latestTurnover->getDate()){
                $latestTurnover=$turnover;
            }
        }
        if(!$latestTurnover){
            $msg = "No turnover found " ;
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        return $latestTurnover;
    }


    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->turnoverDataService->getPage($requestParameters);
    }

}