<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 10:49
 */

namespace App\BusinessLogic\FilesImporter;

use App\BusinessLogic\ValueObjects\PersonsList;

/**
 * Every importer class have to implement this interface
 * i.e jsonImporter, xmlImporter
 *
 * Interface FilesImporterInterface
 *
 * @package App\BusinessLogic\FilesImporter
 */
interface FilesImporterInterface
{
    public function importFiles(): ?PersonsList;

}