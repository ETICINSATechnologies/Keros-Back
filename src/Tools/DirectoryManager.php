<?php

namespace Keros\Tools;


class DirectoryManager
{
    /**
     * @param $file
     * @param bool $usingDate
     * @param string $location
     * @return string
     * @throws \Exception
     */
    public function uniqueFilename($file, $usingDate = false, $location = ''): string
    {
        do {
            if (!$usingDate)
                $filename = $location . md5(pathinfo($file, PATHINFO_FILENAME) . microtime()) . '.' . pathinfo($file, PATHINFO_EXTENSION);
            else {
                $date = new \DateTime();
                $filename = $location . $date->format('d-m-Y_H:i:s:u') . '.' . pathinfo($file, PATHINFO_EXTENSION);
            }
        } while (file_exists($filename));
        return $filename;
    }

    /**
     * @param $path
     * @param int $mode
     */
    public function mkdir($path, $mode = 0755)
    {
        if (!file_exists($path)) {
            mkdir($path, $mode, true);
        }
    }
}