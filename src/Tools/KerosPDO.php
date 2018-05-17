<?php
namespace Keros\Tools;

use Exception;
use PDO;

function KerosPDO(){
    try
    {
        $host="localhost";
        $dbName="keros";
        $charset="utf8";
        $user='root';
        $password='root';
        return new PDO('mysql:host='.$host.';dbname='.$dbName.';charset='.$charset, $user , $password);
    }
    catch(Exception $e)
    {
        die('Error : '.$e->getMessage());
    }
}
