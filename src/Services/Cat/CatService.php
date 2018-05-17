<?php

namespace Keros\Services\Cat;


use Keros\Entities\Cat\Cat;
use Keros\Error\KerosException;
use function Keros\Tools\KerosPDO;
use PDO;

class CatService
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = KerosPDO();
    }

    /**
     * @param Cat $cat the cat to create
     * @return Cat the created cat
     * @throws KerosException if the cat could not be created
     */
    public function create(Cat $cat): Cat {
        $PDO = KerosPDO();
        $req = $PDO->prepare("INSERT INTO cat (name, height) VALUES (:name, :height)");
        $req->bindParam(':name', $cat->name);
        $req->bindParam(':height', $cat->height);
        $res = $req->execute();

        if(!$res){
            throw new KerosException("The cat could not be created", 500);
        }

        $cat->id = $PDO->lastInsertId();

        return $cat;
    }

    /**
     * @param int $id the id of the searched cat
     * @return Cat if it exists, falsy value if not
     */
    public function getOne(int $id): Cat {
        $PDO = KerosPDO();
        $req = $PDO->prepare("SELECT * FROM cat WHERE id =:id");
        $req->bindParam(':id', $id);
        $req->execute();
        $res = $req->fetchObject(Cat::class);

        return $res;
    }

    /**
     * @param int $page the requested page number
     * @return array an array of all the cats on the requested page
     */
    public function getAll(int $page): array {
        $PDO = KerosPDO();
        $req = $PDO->prepare("SELECT * FROM cat ORDER BY name ASC LIMIT 25 OFFSET :page");
        $req->bindValue(':page', (int)( $page * 25 ), PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_CLASS, Cat::class);

        return $res;
    }
}