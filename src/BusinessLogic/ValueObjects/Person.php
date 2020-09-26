<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 16:29
 */

namespace App\BusinessLogic\ValueObjects;


class Person extends ListRef
{
    const FIRST_NAME ='firstName';
    const LAST_NAME ='lastName';
    const BIRTHDAY ='birthday';
    const ADDRESS ='address';
    const PHONE ='phoneNumber';

    public static function createFromArray(array $data) :self
    {
        $obj = new static;
        $obj->setFirstName($data[self::FIRST_NAME] ?? null);
        $obj->setLastName($data[self::LAST_NAME] ?? null);
        $obj->setAddress($data[self::ADDRESS] ?? null);
        $obj->setBirthday($data[self::BIRTHDAY] ?? null);
        $obj->setPhone($data[self::PHONE] ?? null);

        return $obj;
    }

    public function toArray(): array
    {
        return [
            self::FIRST_NAME => $this->getFirstName(),
            self::LAST_NAME => $this->getLastName(),
            self::BIRTHDAY => $this->getBirthday(),
            self::ADDRESS => $this->getAddress(),
            self::PHONE => $this->getPhone(),
        ];
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $firstName
     */
    public function setFirstName(string $firstName) :void
    {
        $this->offsetSet(self::FIRST_NAME, $firstName);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string|null
     */
    public function getFirstName() :?string
    {
        return $this->offsetGet(self::FIRST_NAME);
    }

    /**
     *@codeCoverageIgnore
     *
     * @param string $lastName
     */

    public function setLastName(string $lastName) :void
    {
        $this->offsetSet(self::LAST_NAME, $lastName);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string|null
     */
    public function getLastName() :?string
    {
        return  $this->offsetGet(self::LAST_NAME);
    }


    /**
     * @codeCoverageIgnore
     *
     * @param string $birthday
     */
    public function setBirthday(string $birthday) :void
    {
        $this->offsetSet(self::BIRTHDAY, $birthday);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string|null
     */
    public function getBirthday() :?string
    {
        return  $this->offsetGet(self::BIRTHDAY);
    }


    /**
     * @codeCoverageIgnore
     *
     * @param string $address
     */
    public function setAddress(string $address) :void
    {
        $this->offsetSet(self::ADDRESS, $address);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string|null
     */
    public function getAddress() :?string
    {
        return  $this->offsetGet(self::ADDRESS,);
    }


    /**
     * @codeCoverageIgnore
     *
     * @param string $phone
     */
    public function setPhone(string $phone) :void
    {
        $this->offsetSet(self::PHONE, $phone);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string|null
     */
    public function getPhone() :?string
    {
        return  $this->offsetGet(self::PHONE);
    }

}