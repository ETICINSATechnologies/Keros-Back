<?php

namespace Keros\Tools;

use DateTime;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;

class Validator
{
    /**
     * @param int $id the id to validate
     * @return int the valid id
     * @throws KerosException if the id is invalid
     */
    public static function id(int $id)
    {
        if ($id == null)
        {
            throw new KerosException("The provided id cannot be null", 400);
        }
        if ($id < 0)
        {
            throw new KerosException("The ID cannot be a negative number", 400);
        }
        return $id;
    }

    /**
     * @param string $email the email to validate
     * @return string the cleaned email
     * @throws KerosException if the email is invalid
     */
    public static function email(string $email): string
    {
        if ($email == null)
        {
            throw new KerosException("The provided email cannot be null", 400);
        }
        $email = trim($email);
        if (!preg_match("/^\S+@\S+$/", $email))
        {
            throw new KerosException("The provided email is not valid", 400);
        }
        return $email;
    }

    /**
     * @param string $name the name to validate
     * @return string the cleaned name
     * @throws KerosException if the name is invalid
     */
    public static function name(string $name): string
    {
        if ($name == null)
        {
            throw new KerosException("The provided name cannot be null", 400);
        }
        $name = trim($name);
        if (strlen($name) == 0)
        {
            throw new KerosException("The provided name cannot be empty", 400);
        }
        return $name;
    }

    /**
     * @param float $float the float to validate
     * @return float the valid float
     * @throws KerosException if the float is invalid
     */
    public static function float(float $float): float
    {
        if ($float == null)
        {
            throw new KerosException("The provided float cannot be null", 400);
        }
        if (!is_float($float))
        {
            throw new KerosException("The float provided is not actually a float", 400);
        }
        return $float;
    }

    /**
     * @param string $password
     * @return string
     * @throws KerosException
     */
    public static function password(string $password): string
    {
        if (strlen($password) > 8)
            return $password;
        throw new KerosException("The password is too short", 400);
    }

    /**
     * @param string $date
     * @return DateTime
     * @throws KerosException
     */
    public static function date(string $date): DateTime
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) \d\d:\d\d:\d\d/", $date, $matches))
        {
            if (sizeof($matches) > 3 && checkdate($matches[2], $matches[2], $matches[1]))
            {
                return DateTime::createFromFormat("Y-m-d H:i:s", $date);
            }
        }

        throw new KerosException("The date " . $date ." is incorrect", 400);
    }
}