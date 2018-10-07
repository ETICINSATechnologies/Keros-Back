<?php

namespace Keros\Tools;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;

class Validator
{
    /**
     * @param int $id the id to validate
     * @return int the valid id
     * @throws KerosException if the id is invalid
     */
    public static function id(int $id){
        if($id == null) {
            throw new KerosException("The provided id cannot be null", 400);
        }
        if ($id < 0){
            throw new KerosException("The ID cannot be a negative number", 400);
        }
        return $id;
    }

    /**
     * @param string $email the email to validate
     * @return string the cleaned email
     * @throws KerosException if the email is invalid
     */
    public static function email(string $email): string {
        if($email == null) {
            throw new KerosException("The provided email cannot be null", 400);
        }
        $email = trim($email);
        if (!preg_match("/^\S+@\S+$/", $email)){
            throw new KerosException("The provided email is not valid", 400);
        }
        return $email;
    }

    /**
     * @param string $name the name to validate
     * @return string the cleaned name
     * @throws KerosException if the name is invalid
     */
    public static function name(string $name): string {
        if($name == null) {
            throw new KerosException("The provided name cannot be null", 400);
        }
        $name = trim($name);
        if (strlen($name) == 0){
            throw new KerosException("The provided name cannot be empty", 400);
        }
        return $name;
    }

    /**
     * @param float $float the float to validate
     * @return float the valid float
     * @throws KerosException if the float is invalid
     */
    public static function float(float $float): float{
        if($float == null) {
            throw new KerosException("The provided float cannot be null", 400);
        }
        if (!is_float($float)){
            throw new KerosException("The float provided is not actually a float", 400);
        }
        return $float;
    }
    /**
     * @param int $int the int to validate
     * @return int the valid float
     * @throws KerosException if the int is invalid
     */
    public static function int(int $int): int{
        if($int == null) {
            throw new KerosException("The provided int cannot be null", 400);
        }
        if (!is_int($int)){
            throw new KerosException("The int provided is not actually a int", 400);
        }
        return $int;
    }
}