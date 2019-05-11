<?php

namespace Keros\Tools;

use \Exception;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class DirectoryManager
{
    /**
     * @var Logger
     */
    protected $logger;


    /**
     * DirectoryManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
    }


    /**
     * @param $file
     * @param bool $usingDate
     * @param string $location
     * @return string
     * @throws Exception
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
     * @param string $target
     * @param string $link
     * @throws KerosException
     */
    public function symlink(string $target, string $link){
        if(!symlink($target, $link)){
            $msg = 'Error during symlink';
            $this->logger->err($msg);
            throw new KerosException($msg, 500);
        }
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