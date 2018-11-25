<?php

namespace Keros\Tools\Authorization;


class PasswordEncryption
{
    public static function encrypt(String $password): String
    {
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $encryptedPassword;

    }

    public static function verify(String $password, String $hash): bool
    {
        return password_verify($password, $hash);
    }
}