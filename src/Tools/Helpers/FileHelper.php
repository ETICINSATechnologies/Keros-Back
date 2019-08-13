<?php

namespace Keros\Tools\Helpers;

use Keros\Error\KerosException;
use Slim\Http\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ResponseInterface as Response;

class FileHelper
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

        if (!in_array($extension, self::$mixedExtensions)) {
            throw new KerosException('File extension is not supported', 400);
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new KerosException("Error during file uploading", 500);
        }

        return $file;
    }

    public static function verifyFilename($file, $filename): string
    {
        if (!$file) {
            throw new KerosException("The file " . $filename . " could not be found", 404);
        }

        return $file;
    }

    public static function verifyFilepath($filepath, $filename): string
    {
        if (!file_exists($filepath)) {
            throw new KerosException("The file " . $filename . " could not be found", 404);
        }

        return $filepath;
    }

    public static function getFileResponse($filepath, $response): Response
    {
        $response = $response->withHeader('Content-Type', mime_content_type($filepath));
        $response = $response->withHeader('Content-Disposition', 'attachment; filename="' . basename("$filepath") . '"');
        $response = $response->withHeader('Content-Length', filesize($filepath));
        return $response;
    }

    public static function makeNewFile($fileName)
    {
        $dirname = dirname($fileName);
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }
        $handle = fopen($fileName, 'w') or die('Cannot open file:  ' . $fileName);
        return $handle;
    }

    public static function closeFile($fileName)
    {
        fclose($fileName);
    }

    public static function deleteFile($filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public static function getUploadedFile($temp_fileName): UploadedFile
    {
        $file = new UploadedFile($temp_fileName, $temp_fileName, mime_content_type($temp_fileName), filesize($temp_fileName));
        return $file;
    }

    public static function normalizePath(string $path): string
    {
        return preg_replace('/(?<!\\\)\//', DIRECTORY_SEPARATOR, $path);
    }

    public static function safeCopyFileToDirectory(string $sourceFile, string $destinationDirectory): string
    {
        $sourceFileNormalized = self::normalizePath($sourceFile);
        $destinationDirectoryNormalized = self::normalizePath($destinationDirectory);

        $filename = pathinfo($sourceFileNormalized, PATHINFO_BASENAME);
        $newfilename = $filename;
        $filepath = $destinationDirectoryNormalized . $filename;

        if (file_exists($sourceFileNormalized)) {
            if (!file_exists($destinationDirectoryNormalized)) mkdir($destinationDirectoryNormalized, 0755, true);
            while (file_exists($filepath)) {
                $newfilename = md5(pathinfo($filename, PATHINFO_FILENAME) . microtime()) . '.' . pathinfo($filename, PATHINFO_EXTENSION);
                $filepath = $destinationDirectoryNormalized . $newfilename;
            }
            copy($sourceFileNormalized, $filepath);
        } else {
            throw new KerosException('File is missing', 500);
        }

        return $newfilename;
    }
}
