<?php
namespace Keros\Entities;

use function Keros\Tools\KerosPDO;
use Keros\Tools\Logger;
use Keros\Error\KerosException;
use PDO;

class Cat
{
    private $_id;
    private $_name;
    private $_height;

    public function __construct($id, $name, $height)
    {
        $this->_id = $id;
        $this->_name = $name;
        $this->_height = $height;
    }

    /**
     * @return Cat the cat with it's assigned id
     * @throws \Exception if the insertion failed
     */
    public function create(){
        $PDO = KerosPDO();
        $req = $PDO->prepare("INSERT INTO cat (name, height) VALUES (:name, :height)");
        $req->bindParam(':name', $this->_name);
        $req->bindParam(':height', $this->_height);
        $res = $req->execute();

        if(!$res){
            throw new KerosException("The cat could not be created", 500);
        }

        $this->_id = $PDO->lastInsertId();

        return $this;
    }

    /**
     * @param int $id The id of the cat to search
     * @return Cat the cat if found
     * @throws \Exception if the cat could not be found
     */
    public static function getOne(int $id){
        $PDO = KerosPDO();
        $req = $PDO->prepare("SELECT * FROM cat WHERE id =:id");
        $req->bindParam(':id', $id);
        $req->execute();
        $res = $req->fetchObject(__CLASS__, ["id", "name", "height"]);

        if(!$res){
            throw new KerosException("The cat could not be found", 404);
        }

        return $res;
    }

    public static function getAll($page){
        $PDO = KerosPDO();
        $req = $PDO->prepare("SELECT * FROM cat ORDER BY name ASC LIMIT 25 OFFSET :page");
        $req->bindValue(':page', (int)( $page * 25 ), PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_CLASS, __CLASS__, ["id", "name", "height"]);

        return $res;
    }
}