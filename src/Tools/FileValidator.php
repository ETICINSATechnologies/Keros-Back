<?php

namespace Keros\Tools;

use DateTime;
use Keros\Error\KerosException;
use Psr\Http\Message\UploadedFileInterface as UploadedFile;

class FileValidator
{

    public static function requiredFiles($array): array
    {
        if ($array == null) {
            throw new KerosException("No files found in request", 400);
        }

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

        if (!($extension=='jpg' || $extension=='jpeg' || $extension=='png')) {
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

        if (!($extension=='jpg' || $extension=='jpeg' || $extension=='png')) {
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

        if (!($extension=='pdf' || $extension=='doc' || $extension=='docx')) {
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

        if (!($extension=='pdf' || $extension=='doc' || $extension=='docx')) {
            $msg = 'File format not supported, only document formats supported';
            throw new KerosException($msg, 400);
        }

        return $file;
    }

    public static function requiredFileMixed($file): UploadedFile
    {
        if ($file == null) {
            throw new KerosException('File is empty', 400);
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        return $file;
    }

    public static function optionalFileMixed($file): ?UploadedFile
    {
        if ($file == null) {
            return null;
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        return $file;
    }

}