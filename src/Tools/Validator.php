<?php

namespace Keros\Tools;

use DateTime;
use Keros\Error\KerosException;

class Validator
{
    public static function requiredId($id): int
    {
        if ($id == null) {
            throw new KerosException("The provided id cannot be null", 400);
        }
        if (!is_int($id)) {
            throw new KerosException("The provided id is not an integer", 400);
        }
        if ($id < 0) {
            throw new KerosException("The ID cannot be a negative number", 400);
        }
        return $id;
    }

    public static function optionalId($id): ?int
    {
        if ($id == null) {
            return null;
        }
        if (!is_int($id)) {
            throw new KerosException("The provided id is not an integer", 400);
        }
        if ($id < 0) {
            throw new KerosException("The ID cannot be a negative number", 400);
        }
        return $id;
    }

    public static function requiredEmail($email): string
    {
        if ($email == null) {
            throw new KerosException("The provided email cannot be null", 400);
        }
        $email = trim($email);
        if (!preg_match("/^\S+@\S+$/", $email)) {
            throw new KerosException("The provided email is not valid", 400);
        }
        return $email;
    }

    public static function optionalEmail($email): ?string
    {
        if ($email == null) {
            return null;
        }
        $email = trim($email);
        if (strlen($email) == 0) {
            return null;
        }
        if (!preg_match("/^\S+@\S+$/", $email)) {
            throw new KerosException("The provided email is not valid", 400);
        }
        return $email;
    }

    public static function requiredString($string): string
    {
        if ($string == null) {
            throw new KerosException("The provided string cannot be null", 400);
        }
        $string = trim($string);
        if (strlen($string) == 0) {
            throw new KerosException("The provided string cannot be empty", 400);
        }
        return $string;
    }

    public static function optionalString($string): ?string
    {
        if ($string == null) {
            return null;
        }
        $string = trim($string);
        if (strlen($string) == 0) {
            return null;
        }
        return $string;
    }

    public static function requiredFloat($float): float
    {
        if ($float == null) {
            throw new KerosException("The provided float cannot be null", 400);
        }
        if (is_int($float)) {
            $float = floatval($float);
        }
        if (!is_float($float)) {
            throw new KerosException("The float provided is not actually a float", 400);
        }
        return $float;
    }

    public static function optionalFloat($float): ?float
    {
        if ($float == null || empty($float)) {
            return null;
        }
        return self::requiredFloat($float);
    }

    public static function requiredInt($int): int
    {
        if ($int == null) {
            throw new KerosException("The provided int cannot be null", 400);
        }
        if (!is_int($int)) {
            throw new KerosException("The int provided is not actually a int", 400);
        }
        return $int;
    }

    public static function requiredBool($bool): bool
    {
        if (!isset($bool)) {
            throw new KerosException("The provided boolean cannot be null", 400);
        }
        if (!is_bool($bool)) {
            throw new KerosException("The provided boolean is not a boolean", 400);
        }

        return $bool;
    }

    public static function optionalBool($bool): ?bool
    {
        if ($bool == null)
            return 0;
        if (!is_bool($bool)) {
            throw new KerosException("The provided boolean is not a boolean", 400);
        }
        return $bool;
    }

    public static function requiredPassword($password): string
    {
        if ($password == null) {
            throw new KerosException("The provided password cannot be null", 400);
        }
        $password = trim($password);
        if (strlen($password) < 8) {
            throw new KerosException("The password is too short", 400);
        }
        return $password;
    }

    public static function optionalDate($date): ?DateTime
    {
        if($date == null || empty($date)){
            return null;
        }
        return self::requiredDate($date);
    }

    public static function requiredDate($date): DateTime
    {
        // check if the string is of type: 'yyyy-mm-dd'
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})/", $date, $matches)) {
            // <checkdate> parameters are in sequence the day, the month and the year:
            // - $matches[0]: the entire string
            // - $matches[1]: the year
            // - $matches[2]: the month
            // - $matches[3]: the day
            if (sizeof($matches) > 3 && checkdate($matches[2], $matches[3], $matches[1])) {
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

    public static function optionalPhone($telephone): ?string
    {
        if ($telephone == null) {
            return null;
        }
        $telephone = trim($telephone);
        if (strlen($telephone) == 0) {
            return null;
        }
        if (!preg_match("/00\d{11}/", $telephone))
            throw new KerosException("The provided phone number is invalid", 400);

        return $telephone;
    }

    public static function requiredPhone($telephone): ?string
    {
        if (!preg_match("/00\d{11}/", $telephone))
            throw new KerosException("The provided phone number is invalid", 400);

        return $telephone;
    }

    public static function requiredSchoolYear($schoolYear): int
    {
        if ($schoolYear == null)
            throw new KerosException("The provided schoolYear cannot be null", 400);
        if (!is_int($schoolYear)) {
            throw new KerosException("The schoolYear provided is not actually an int", 400);
        }
        if (8 < $schoolYear || $schoolYear < 1)
            throw new KerosException("The provided schoolYear must be between 1 and 8", 400);

        return $schoolYear;
    }

    public static function optionalSchoolYear($schoolYear): ?int
    {
        if ($schoolYear == null)
            return null;
        if (!is_int($schoolYear)) {
            throw new KerosException("The schoolYear provided is not actually an int", 400);
        }
        if (8 < $schoolYear || $schoolYear < 1)
            throw new KerosException("The provided schoolYear must be between 1 and 8", 400);

        return $schoolYear;
    }

    public static function requiredArray($array): array
    {
        if ($array == null) {
            throw new KerosException("The provided array cannot be null", 400);
        }
        if (!is_array($array)) {
            throw new KerosException("The provided array is not actually an array", 400);
        }
        return $array;
    }
}