<?php

namespace Keros\Tools\Helpers;

use Keros\Error\KerosException;
use Slim\Http\UploadedFile;
use Psr\Http\Message\ResponseInterface as Response;

class FileHelper
{
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
