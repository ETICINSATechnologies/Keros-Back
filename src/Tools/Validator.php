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

    public static function optionalName(String $name): string
    {
        if ($name == null)
            return "";

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
     * @param int $int the int to validate
     * @return int the valid float
     * @throws KerosException if the int is invalid
     */
    public static function int(int $int): int
    {
        if ($int == null)
        {
            throw new KerosException("The provided int cannot be null", 400);
        }
        if (!is_int($int))
        {
            throw new KerosException("The int provided is not actually a int", 400);
        }
        return $int;
    }

    /**
     * @param bool $bool $float the float to validate
     * @return bool the valid bool
     * @throws KerosException if the float is invalid
     */
    public static function bool(bool $bool): bool
    {
        if ($bool != 0 && $bool != 1)
        {
            throw new KerosException("The provided boolean is not a boolean", 400);
        }

        return $bool;
    }

    /**
     * @param $bool
     * @return bool|null
     * @throws KerosException
     */
    public static function optionalBool($bool)
    {
        if ($bool == null)
            return 0;

        return self::bool($bool);
    }

    /**
     * @param string $password
     * @return string
     * @throws KerosException
     */
    public static function password(string $password): string
    {
        if (strlen($password) < 8)
        {
            throw new KerosException("The password is too short", 400);
        }
        return $password;
    }

    /**
     * @param string $date
     * @return DateTime
     * @throws KerosException
     */
    public static function date(string $date): DateTime
    {
        // check if the string is of type: 'yyyy-mm-dd'
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})/", $date, $matches))
        {
            // <checkdate> parameters are in sequence the day, the month and the year:
            // - $matches[0]: the entire string
            // - $matches[1]: the year
            // - $matches[2]: the month
            // - $matches[3]: the day
            if (sizeof($matches) > 3 && checkdate($matches[2], $matches[3], $matches[1]))
            {
                // check if the time is precised (with the format hh:mm:ss)
                if (preg_match("/\d{2}:\d{2}:\d{2}$/", $date))
                    return DateTime::createFromFormat("Y-m-d H:i:s", $date);
                else
                    return DateTime::createFromFormat("Y-m-d", $date);
            }

            throw new KerosException("The provided date " . $date . " is not valid", 400);
        }

        throw new KerosException("The date " . $date . " is not a date", 400);
    }

    /**
     * @param string $date
     * @return DateTime
     * @throws KerosException
     */
    public static function optionalDate($date)
    {
        return $date == null || strlen($date) == 0 ? new DateTime("now") : self::date($date);
    }

    /**
     * @param string $telephone
     * @return null|string
     * @throws KerosException
     */
    public static function optionalPhone(string $telephone): String
    {
        if ($telephone == null || strlen($telephone) == 0)
            return null;

        if (!preg_match("/00\d{10}/", $telephone))
            throw new KerosException("The provided phone number is invalid", 400);

        return $telephone;
    }

    /**
     * @param $schoolYear
     * @return mixed
     * @throws KerosException
     */
    public static function schoolYear(int $schoolYear): int
    {
        //$schoolYear = intval($schoolYear);

        if ($schoolYear == null)
            throw new KerosException("The provided schoolYear cannot be null", 400);

        if (8 < $schoolYear && $schoolYear < 1)
            throw new KerosException("The provided schoolYear must be between 1 and 8", 400);

        return $schoolYear;
    }
}