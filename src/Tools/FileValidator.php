<?php

namespace Keros\Tools;

use Keros\Error\KerosException;
use Slim\Http\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;

class FileValidator
{

    public static function requiredFiles($array): array
    {
        if ($array == null) {
            throw new KerosException("No files found in request", 400);
        }

        return $array;
    }

    public static function optionalFiles($array): ?array
    {
        return $array;
    }

    public static function requiredFilePhoto($file): UploadedFile
    {
        if ($file == null) {
            throw new KerosException('File is empty', 400);
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $extension= strtolower($extension);

        if (!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')) {
            $msg = 'File format not supported, only jpg and png formats supported';
            throw new KerosException($msg, 400);
        }

        return $file;
    }

    public static function optionalFilePhoto($file): ?UploadedFile
    {
        if ($file == null) {
            return null;
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $extension= strtolower($extension);

        if (!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')) {
            $msg = 'File format not supported, only picture formats supported';
            throw new KerosException($msg, 400);
        }

        return $file;
    }

    public static function requiredFileDocument($file): UploadedFile
    {
        if ($file == null) {
            throw new KerosException('File is empty', 400);
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $extension= strtolower($extension);

        if (!($extension == 'pdf' || $extension == 'doc' || $extension == 'docx')) {
            $msg = 'File format not supported, only document formats supported';
            throw new KerosException($msg, 400);
        }

        return $file;
    }

    public static function optionalFileDocument($file): ?UploadedFile
    {
        if ($file == null) {
            return null;
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $extension= strtolower($extension);

        if (!($extension == 'pdf' || $extension == 'doc' || $extension == 'docx')) {
            $msg = 'File format not supported, only document formats supported';
            throw new KerosException($msg, 400);
        }

        return $file;
    }

    protected static $mixedExtensions = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif');

    public static function requiredFileMixed($file): UploadedFileInterface
    {
        if ($file == null) {
            throw new KerosException('File is empty', 400);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $extension= strtolower($extension);

        if (!in_array($extension, self::$mixedExtensions)) {
            throw new KerosException('File extension is not supported', 400);
        }


        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        return $file;
    }

    public static function optionalFileMixed($file): ?UploadedFileInterface
    {
        if ($file == null) {
            return null;
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $extension= strtolower($extension);

        if (!in_array($extension, self::$mixedExtensions)) {
            throw new KerosException('File extension is not supported', 400);
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        return $file;
    }
}
