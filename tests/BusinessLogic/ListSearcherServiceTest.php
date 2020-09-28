<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 28.09.20
 * Time: 20:55
 */

namespace App\Tests\BusinessLogic;


use App\BusinessLogic\ListSearcherService;
use PHPUnit\Framework\TestCase;

/**
 * Class ListSearcherServiceTest
 *
 * @package App\Tests\BusinessLogic
 */
class ListSearcherServiceTest extends TestCase
{
    /**
     * @var ListSearcherService
     */
    private $instance;


    public function setUp(): void
    {
        parent::setUp();
        $this->instance = new ListSearcherService();
    }


    /**
     * @covers \App\BusinessLogic\ListSearcherService::searchNumber
     */
    public function testSearchNumberNumberExistInTheFirstHalfOfArray(): void
    {
        $listArray        = [];
        $numberToFind1    = 250000;
        $numberStartsFrom = -1;
        for ($i = 0; $i < 1000000; $i++) {
            $listArray[] = $numberStartsFrom = $numberStartsFrom + 1;
        }

        static::assertEquals(250000, $this->instance->searchNumber($listArray, $numberToFind1));
    }


    /**
     * @covers \App\BusinessLogic\ListSearcherService::searchNumber
     */
    public function testSearchNumberNumberExistInTheSecondHalfOfArray(): void
    {
        $listArray        = [];
        $numberToFind1    = 750000;
        $numberStartsFrom = -1;
        for ($i = 0; $i < 1000000; $i++) {
            $listArray[] = $numberStartsFrom = $numberStartsFrom + 1;
        }

        static::assertEquals(750000, $this->instance->searchNumber($listArray, $numberToFind1));
    }


    /**
     * @covers \App\BusinessLogic\ListSearcherService::searchNumber
     */
    public function testSearchNumberNumberExistExactMiddleOfArray(): void
    {
        $listArray        = [];
        $numberToFind1    = 500000;
        $numberStartsFrom = -1;
        for ($i = 0; $i < 1000000; $i++) {
            $listArray[] = $numberStartsFrom = $numberStartsFrom + 1;
        }

        static::assertEquals(500000, $this->instance->searchNumber($listArray, $numberToFind1));
    }


    /**
     * @covers \App\BusinessLogic\ListSearcherService::searchNumber
     */
    public function testSearchNumberNumberIsTheFirstElementOfArray(): void
    {
        $listArray        = [];
        $numberToFind1    = 0;
        $numberStartsFrom = -1;
        for ($i = 0; $i < 1000000; $i++) {
            $listArray[] = $numberStartsFrom = $numberStartsFrom + 1;
        }

        static::assertEquals(0, $this->instance->searchNumber($listArray, $numberToFind1));
    }


    /**
     * @covers \App\BusinessLogic\ListSearcherService::searchNumber
     */
    public function testSearchNumberNumberArrayIsEmpty(): void
    {
        $listArray     = [];
        $numberToFind1 = 25;

        static::assertEquals(-1, $this->instance->searchNumber($listArray, $numberToFind1));
    }


}