<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 27.09.20
 * Time: 17:58
 */

namespace App\BusinessLogic\FilesImporter;

use App\BusinessLogic\ValueObjects\PersonsList;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Override scandir
 * @param $directory
 * @param null $sorting_order
 * @param null $context
 * @return array
 */
function scandir($directory, $sorting_order = null, $context = null)
{
    return ['person1'];
}

/**
 * Override file_get_contents
 *
 * @return string
 */
function file_get_contents(): string
{
    return \json_encode(
        [
            'firstName' => 'xxx',
            'lastName' => 'abc',
            'birthday' => '02,1259',
            'address' => 'langstrasse',
            'phoneNumber' => '0300',
        ]
    );
}


class JsonFilesImporterServiceTest extends TestCase
{

    /**
     * @var string
     */
    private $lastErrorMessage;

    /**
     * @var Filesystem|MockObject
     */
    private $filesystem;

    /**
     * @var ParameterBagInterface|MockObject
     */
    private $params;

    /**
     * @var LoggerInterface|MockObject
     */
    private $appLogger;

    /**
     * @var JsonFilesImporterService
     */
    private $instance;


    public function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->createMock(Filesystem::class);
        $this->params = $this->createMock(ParameterBagInterface::class);
        $this->appLogger = $this->createMock(LoggerInterface::class);

        $this->instance = new JsonFilesImporterService(
            $this->filesystem,
            $this->params,
            $this->appLogger
        );
    }

    public function testImportFiles(): void
    {
        $projectDir = \uniqid('projectDir', true);
        $jsonFilesDir = \uniqid('files', true);

        $this->params->expects(static::exactly(2))
            ->method('has')
            ->withConsecutive(
                [
                    static::equalTo('kernel.project_dir')
                ],
                [
                    static::equalTo('app.json_files_dir'),
                ]
            )
            ->willReturnOnConsecutiveCalls(true, true);

        $this->params->expects(static::exactly(2))
            ->method('get')
            ->withConsecutive(
                [
                    static::equalTo('kernel.project_dir')
                ],
                [
                    static::equalTo('app.json_files_dir'),
                ]
            )
            ->willReturnOnConsecutiveCalls($projectDir, $jsonFilesDir);

        $this->filesystem->method('exists')->willReturn(true);
        static::assertInstanceOf(PersonsList::class, $this->instance->importFiles());
    }

    public function testImportFilesWithRootDirNotFound(): void
    {
        $this->params->expects(static::once())
            ->method('has')
            ->with(static::equalTo('kernel.project_dir'))
            ->willReturn(false);
        static::assertNull($this->instance->importFiles());
        static::assertEquals('Directory not found !', $this->instance->getLastErrorMessage());
    }

    public function testImportFilesWithFileNotFound(): void
    {
        $projectDir = \uniqid('projectDir', true);
        $jsonFilesDir = \uniqid('files', true);

        $this->params->expects(static::exactly(2))
            ->method('has')
            ->withConsecutive(
                [
                    static::equalTo('kernel.project_dir')
                ],
                [
                    static::equalTo('app.json_files_dir'),
                ]
            )
            ->willReturnOnConsecutiveCalls(true, true);

        $this->params->expects(static::exactly(2))
            ->method('get')
            ->withConsecutive(
                [
                    static::equalTo('kernel.project_dir')
                ],
                [
                    static::equalTo('app.json_files_dir'),
                ]
            )
            ->willReturnOnConsecutiveCalls($projectDir, $jsonFilesDir);

        $this->filesystem->method('exists')->willReturn(false);
        static::assertNull($this->instance->importFiles());
    }


}