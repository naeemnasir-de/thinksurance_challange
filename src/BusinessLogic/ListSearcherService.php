<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 28.09.20
 * Time: 20:46
 */

namespace App\BusinessLogic;


class ListSearcherService
{
    /**
     * Its just a implementation of bst as we have sorted list(in this scenerio its the fastest)
     * I tried to use interpolution searching but it work best when we have some pattern in the number in ASC
     *
     * @param array $integersList
     * @param int   $numberToFind
     *
     * @return int
     */
    public function searchNumber(array $integersList, int $numberToFind): int
    {
        if (count($integersList) === 0) {
            return -1;
        }
        if ($integersList[0] === $numberToFind) {
            return 0;
        }
        $start = 0;
        $end   = count($integersList) - 1;
        while ($start <= $end) {
            $mid = ceil(($start + $end) / 2);
            if ($integersList[$mid] == $numberToFind) {
                return (int) $mid;
            }
            if ($numberToFind < $integersList[$mid]) {
                $end = $mid - 1;
            }
            else {
                $start = $mid + 1;
            }
        }
        return -1;
    }
}