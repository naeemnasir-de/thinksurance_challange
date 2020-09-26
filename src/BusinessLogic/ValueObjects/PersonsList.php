<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 16:29
 */

namespace App\BusinessLogic\ValueObjects;


class PersonsList extends ListRef
{
    public static function createFromRows(array $rows): self
    {
        $instance = new self;

        foreach ($rows as $row) {
            if (is_array($row)) {
                $instance->addFromArray($row);
            }
        }
        return $instance;
    }


    /**
     * @param array $data
     * @return PersonsList
     */
    public function addFromArray(array $data): self
    {
        return $this->add(Person::createFromArray($data));
    }



    /**
     * @return array
     */
    public function render(): array
    {
        $return = [];

        foreach ($this as $offset => $item) {
            $return[$offset] = $item->toArray();
        }

        return $return;
    }

}