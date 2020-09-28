<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 16:33
 */

namespace App\BusinessLogic\ValueObjects;

/**
 * @method \SomeClass @offsetGet();
 * @method \SomeClass @current()
 * @method \SomeClass @last()
 * @method \SomeClass @first()
 */
class ListRef implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var array
     */
    protected $list = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * this is required for the unset() <=> offsetUnset() where an exception is
     * thrown if next() or rewind() is not called after unset.
     *
     * @var bool
     */
    protected $next_or_rewind_required = false;


    /**
     * ListRef constructor.
     */
    public function __construct()
    {
        $this->position = 0;
    }


    /**
     * @param $item
     *
     * @return ListRef
     */
    public function add($item): self
    {
        $this->offsetSet(null, $item);
        return $this;
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            // a value is appended using [] array append operator.
            $offset = $this->nextOffset();
        }
        $this->preAdd($offset, $value);
        $this->list[$offset] = &$value;
    }


    /**
     * Get the next numeric offset.
     *
     * we get the MAX numeric offset we have and increment it by 1.
     */
    protected function nextOffset(): int
    {
        $keys = array_keys($this->list);
        $keys = array_filter($keys, 'is_numeric'); // get numeric keys only
        if ($keys) {
            $last_key = max($keys);
        }
        else {
            $last_key = -1;
        }
        return $last_key + 1;
    }


    /**
     *
     */
    protected function preAdd(): void
    {

    }


    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $offset_pos = $this->offsetPosition($offset);
        if ($offset_pos !== null && $this->position === $offset_pos) {
            $this->next_or_rewind_required = true;
            if ($this->position > 0) {
                $this->position--;
            }
        }
        unset($this->list[$offset]);
    }


    /**
     * Get the internal pointer numeric position which corresponds to the
     * offset passed.
     * this is not in the interface
     *
     * @param $offset
     *
     * @return int|null
     */
    public function offsetPosition($offset): ?int
    {
        $keys = array_keys($this->list);
        $key  = array_search($offset, $keys);
        return $key === false ? null : $key;
    }


    public function rewind(): void
    {
        $this->position                = 0;
        $this->next_or_rewind_required = false;
    }


    /**
     * @return bool
     */
    public function valid(): bool
    {
        if (empty($this->list)) {
            return false;
        }
        return array_key_exists($this->position, array_keys($this->list));
    }


    public function next(): void
    {
        ++$this->position;
        $this->next_or_rewind_required = false;
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function current()
    {
        if ($this->next_or_rewind_required) {
            throw new \Exception(
                'Calling next or rewind is required after call to unset, current item can\'t be accessed otherwise because it is removed.'
            );
        }
        return $this->list[$this->key()];
    }


    /**
     * @return string|int
     */
    public function key()
    {
        $keys   = array_keys($this->list);
        $offset = $keys[$this->position];
        return $offset;
    }


    /**
     * Get by reference
     *
     * @param string|int $offset
     *
     * @return mixed|null
     */
    public function &offsetGet($offset)
    {
        if (array_key_exists($offset, $this->list)) {
            return $this->list[$offset];
        }
        $null = null;
        return $null;
    }


    /**
     * This is not in the interface, cast the internal list to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->list;
    }


    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->list);
    }


    /**
     * @param $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return (array_key_exists($offset, $this->list));
    }

}