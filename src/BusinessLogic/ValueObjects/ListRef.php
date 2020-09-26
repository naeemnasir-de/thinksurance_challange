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


    public function __construct()
    {
        $this->position = 0;
    }


    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->list);
    }



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
     * @param $item
     * @return ListRef
     */
    public function add($item): self
    {
        $this->offsetSet(null, $item);
        return $this;
    }


    /**
     * This is not in the interface, add an item passing it by reference.
     *
     * This will only accept variables as input.
     *
     * @param $item
     *
     * @return self
     * @throws Exception\CollectionException
     * @throws Exception\InvalidValue
     */
    public function addByReference(&$item): self
    {
        $offset = $this->nextOffset();
        $this->preAdd($offset, $item);
        $this->list[$offset] = &$item;
        return $this;
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
     * @param $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return (array_key_exists($offset, $this->list));
    }


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


    public function rewind(): void
    {
        $this->position                = 0;
        $this->next_or_rewind_required = false;
    }


    public function next(): void
    {
        ++$this->position;
        $this->next_or_rewind_required = false;
    }


    /**
     * This is not in the interface, go back in the array.
     */
    public function prev(): void
    {
        --$this->position;
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


    /**
     * @return mixed
     * @throws Exception\CollectionException | Exception\OutOfBound
     */
    public function current()
    {
        if ($this->next_or_rewind_required) {
            throw new Exception\OutOfBound(
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
     * @return int
     */
    public function position(): int
    {
        return $this->position;
    }


    /**
     * This is not in the interface, Get the last item.
     *
     * @return mixed|null
     */
    public function last()
    {
        $keys   = array_keys($this->list);
        $offset = array_pop($keys);
        if (array_key_exists($offset, $this->list)) {
            return $this->list[$offset];
        }
        $null = null;
        return $null;
    }


    /**
     * This is not in the interface, Get the first item.
     *
     * @return mixed|null
     */
    public function first()
    {
        $keys   = array_keys($this->list);
        $offset = array_shift($keys);
        if (array_key_exists($offset, $this->list)) {
            return $this->list[$offset];
        }
        $null = null;
        return $null;
    }


    /**
     * This is not in the interface, Return the keys of the internal list.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->list);
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
     * This is not in the interface, merge this object with another object and act
     * like array_merge, numeric indexes are added, other indexes are overridden.
     *
     * This method will alter the current object.
     *
     * @param self $another_list
     *
     * @return self
     *
     * @throws Exception\CollectionException
     */
    public function merge(self $another_list): self
    {
        for ($another_list->rewind(); $another_list->valid(); $another_list->next()) {
            $key = $another_list->key();
            $this->offsetSet(is_numeric($key) ? null : $key, $another_list->current());
        }
        return $this;
    }


    /**
     * Change the position of item at $offset1 in the internal array to be at
     * the position of item at $offset2 and vise versa.
     *
     * @param $offset1
     * @param $offset2
     *
     * @return static
     * @throws Exception\CollectionException
     * @throws Exception\OutOfBound
     */
    public function swap($offset1, $offset2): self
    {
        $pos1 = $this->offsetPosition($offset1);
        $pos2 = $this->offsetPosition($offset2);
        if ($pos1 === null || $pos2 === null) {
            // offset does not exist
            throw new Exception\OutOfBound("Trying to swap non exist offsets");
        }
        if ($pos1 === $pos2) {
            return $this;
        }
        $temp           = $this[$offset1];
        $this[$offset1] = $this[$offset2];
        $this[$offset2] = $temp;

        return $this;
    }


    /**
     * Change the position of item at $offset1 in the internal array to be at
     * the position of item at $offset2 and vise versa using re-order and refilling
     * the array
     *
     * @param $offset1
     * @param $offset2
     *
     * @return self
     *
     * @throws Exception\CollectionException
     * @throws Exception\OutOfBound
     */
    public function swapReorder($offset1, $offset2): self
    {
        $pos1 = $this->offsetPosition($offset1);
        $pos2 = $this->offsetPosition($offset2);
        if ($pos1 === null || $pos2 === null) {
            // offset does not exist
            throw new Exception\OutOfBound("Trying to swap non exist offsets");
        }
        if ($pos1 == $pos2) {
            return $this;
        }

        $clone = clone $this;
        for ($clone->rewind(); $clone->valid(); $clone->next()) {
            $key      = $clone->key();
            $curr_pos = $clone->position();
            if ($curr_pos === $pos1) {
                $this[$key] = $clone->offsetGet($offset2);
            }
            elseif ($curr_pos === $pos2) {
                $this[$key] = $clone->offsetGet($offset1);
            }
            else {
                $this[$key] = $clone->current();
            }
        }

        return $this;
    }


    /**
     * Get the item by internal pointer numeric position and not by the offset.
     *
     * This is not in the interface.
     *
     * @param int $pos
     *
     * @return null|mixed
     */
    public function byPositionGet($pos)
    {
        $keys = $this->keys();
        if (array_key_exists($pos, $keys)) {
            $offset = $keys[$pos];
            return $this->list[$offset];
        }

        return null;
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


    /**
     * Check if this container is empty.
     *
     * This is not in the interface
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return count($this) === 0;
    }


    /**
     * Perform array_diff_key against another list where the first parameter is
     * this collection and the second is the new list.
     *
     * This will return a new Collection.
     *
     * @param self $another_list
     *
     * @return static
     */
    public function diffKey(self $another_list): self
    {
        $diff     = array_diff_key($this->toArray(), $another_list->toArray());
        $new_list = new static();
        foreach ($diff as $key => $value) {
            $new_list[$key] = $value;
        }
        return $new_list;
    }


    /**
     * Return Recursive diff from another list with index check.
     *
     * The first array is this collection and compute the diff against the passed collection.
     *
     * This will return a new Collection.
     *
     * @param self $another_list
     *
     * @return static
     */
    public function diffRecursiveAssoc(self $another_list): self
    {
        $ret  = new static();
        $diff = static::arrayDiffRecursiveAssoc($this->toArray(), $another_list->toArray());
        foreach ($diff as $key => $value) {
            $ret[$key] = $value;
        }
        return $ret;
    }


    /**
     * Return Recursive diff from another list.
     *
     * The first array is this collection and compute the diff against the passed collection.
     *
     * This will return a new Collection.
     *
     * @param self $another_list
     *
     * @return static
     */
    public function diffRecursive(self $another_list): self
    {
        $ret  = new static();
        $diff = static::arrayDiffRecursive($this->toArray(), $another_list->toArray());
        foreach ($diff as $key => $value) {
            $ret[$key] = $value;
        }
        return $ret;
    }


    /**
     * Implementation of array_intesect_key against another list.
     *
     * This will return a new Collection.
     *
     * @param self $another_list
     *
     * @return static
     */
    public function intersectKey(self $another_list): self
    {
        $ret = new static();
        foreach ($this as $key => $value) {
            if ($another_list->offsetExists($key)) {
                $ret[$key] = $value;
            }
        }
        return $ret;
    }


    /**
     * A version of php array_diff_assoc which is recursive.
     *
     * array_diff_assoc — Computes the difference of arrays with additional index check
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    protected static function arrayDiffRecursiveAssoc(array $array1, array $array2): array
    {
        $ret = array();

        foreach ($array1 as $key1 => $value1) {
            if (!array_key_exists($key1, $array2)) {
                $ret[$key1] = $value1;
                continue;
            }
            if (is_array($value1) && is_array($array2[$key1])) {
                $recursive_diff = self::arrayDiffRecursiveAssoc($value1, $array2[$key1]);
                if (count($recursive_diff)) {
                    $ret[$key1] = $recursive_diff;
                }
            }
            elseif ($value1 instanceof self && $array2[$key1] instanceof self) {
                $recursive_diff = $value1->diffRecursiveAssoc($array2[$key1]);
                if ($recursive_diff->count()) {
                    $ret[$key1] = $recursive_diff;
                }
            }
            elseif (!is_array($value1) && !is_array($array2[$key1])) {
                if ($value1 !== $array2[$key1]) {
                    $ret[$key1] = $value1;
                }
            }
            else {
                $ret[$key1] = $value1;
            }
        }
        return $ret;
    }


    /**
     * A version of php array_diff which is recursive.
     *
     * array_diff — Computes the difference of arrays
     *
     * This function is costy because it uses array_search
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    protected static function arrayDiffRecursive(array $array1, array $array2): array
    {
        $ret = array();

        foreach ($array1 as $key1 => $value1) {
            if (false === ($key2 = array_search($value1, $array2, true))) {
                $ret[$key1] = $value1;
                continue;
            }
            if (is_array($value1) && is_array($array2[$key2])) {
                $recursive_diff = self::arrayDiffRecursive($value1, $array2[$key2]);
                if (count($recursive_diff)) {
                    $ret[$key1] = $recursive_diff;
                }
            }
            elseif ($value1 instanceof self && $array2[$key2] instanceof self) {
                $recursive_diff = $value1->diffRecursive($array2[$key2]);
                if ($recursive_diff->count()) {
                    $ret[$key1] = $recursive_diff;
                }
            }
            elseif (!is_array($value1) && !is_array($array2[$key2])) {
                if ($value1 !== $array2[$key2]) {
                    $ret[$key1] = $value1;
                }
            }
            else {
                $ret[$key1] = $value1;
            }
        }
        return $ret;
    }



    protected function preAdd(&$offset, &$value): void
    {
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

}