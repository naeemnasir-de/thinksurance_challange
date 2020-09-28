<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 10:45
 */

namespace App\BusinessLogic\FilesImporter;

use App\BusinessLogic\ValueObjects\PersonsList;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class JsonFilesImporterService
{
    public const DIRECTORY_SEPARATOR = '/';

    /**
     * @var string
     */
    private $lastErrorMessage;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var string
     */
    private $folderDir;

    /**
     * @var LoggerInterface
     */
    private $appLogger;


    public function __construct(
        Filesystem $filesystem,
        ParameterBagInterface $params,
        LoggerInterface $appLogger
    )
    {
        $this->filesystem = $filesystem;
        $this->params     = $params;
        $this->appLogger  = $appLogger;
    }


    /**
     * @return array
     */
    public function importFiles(): ?PersonsList
    {
        $directory = $this->getJsonFilesDirectory();
        if ($directory === null) {
            $this->setLastErrorMessage('Unable to configue json files directory !');
            return null;
        }
        $files = $this->getFilesFromDirectory($directory);
        if ($files === null) {
            $this->setLastErrorMessage('Directory not found !');
            return null;
        }
        $persons = $this->getPersons($files);
        return $persons;

    }


    /**
     * @return string|null
     */
    private function getJsonFilesDirectory(): ?string
    {
        if ($this->folderDir !== null) {
            return $this->folderDir;
        }
        if (!$this->params->has('kernel.project_dir')
            || !$this->params->has('app.json_files_dir')
        ) {
            $this->folderDir = null;
        }
        $this->folderDir = $this->params->get('kernel.project_dir') .
            $this->params->get('app.json_files_dir');

        return $this->folderDir;
    }


    /**
     * @param string $dirPath
     *
     * @return array|null
     */
    private function getFilesFromDirectory(string $dirPath): ?array
    {
        if (!$this->filesystem->exists($dirPath)) {
            return null;
        }
        $result = scandir($dirPath);

        return \array_diff($result, ['.', '..']);
    }


    /**
     * @param array $files
     *
     * @return array
     */
    private function getPersons(array $files): PersonsList
    {
        $result = [];
        foreach ($files as $name) {
            $fileDir = $this->getFilePath($name);
            $content = $this->getFileContent($fileDir);

            if ($content === null) {
                $this->appLogger->error(
                    \sprintf(
                        "%s unable to read file: %s",
                        __METHOD__,
                        $name
                    )
                );
                continue;
            }

            $contentAsArray = \json_decode($content, true);
            // json string is invalid
            if ($contentAsArray === null) {
                $this->appLogger->error(
                    \sprintf(
                        "%s unable to parse file: %s",
                        __METHOD__,
                        $name
                    )
                );
                continue;
            }
            $result[] = $contentAsArray;

        }
        return PersonsList::createFromRows($result);

    }


    /**
     * @param string $fileName
     *
     * @return string
     */
    private function getFilePath(string $fileName): ?string
    {
        $fileDir = $this->getJsonFilesDirectory();
        if ($fileDir === null) {
            return null;
        }
        return $fileDir . self::DIRECTORY_SEPARATOR . $fileName;
    }


    /**
     * @param string $filePath
     *
     * @return string|null
     */
    private function getFileContent(string $filePath): ?string
    {
        if (!$this->filesystem->exists($filePath)) {
            return null;
        }
        /** As file size is very small we can use file_get_contents
         * If the files size is huge than we consider line by line reading to avoid memory exhaust
         **/
        $result = file_get_contents($filePath);

        return $result;
    }


    /**
     * @return string
     */
    public function getLastErrorMessage(): string
    {
        return $this->lastErrorMessage;

    }


    /**
     * @param string $lastErrorMessage
     */
    public function setLastErrorMessage(string $lastErrorMessage): void
    {
        $this->lastErrorMessage = $lastErrorMessage;
    }

}