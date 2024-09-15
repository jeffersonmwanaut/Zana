<?php namespace Zana;

class ArrayHelper {
    private $array;

    public function __construct($array) {
        $this->array = $array;
    }

    /**
     * Get the first element of the array.
     *
     * @return mixed The first element of the array, or null if the array is empty.
     */
    public function first() {
        if (empty($this->array)) {
            return null;
        }
        return $this->array[0];
    }

    /**
     * Get the last element of the array.
     *
     * @return mixed The last element of the array, or null if the array is empty.
     */
    public function last() {
        if (empty($this->array)) {
            return null;
        }
        return end($this->array);
    }

    /**
     * Get an element at a specific index.
     *
     * @param int $index The index of the element to retrieve.
     *
     * @return mixed The element at the specified index, or null if the index is out of range.
     */
    public function at($index) {
        if (!isset($this->array[$index])) {
            return null;
        }
        return $this->array[$index];
    }

    /**
     * Get all elements of the array.
     *
     * @return array The entire array.
     */
    public function all() {
        return $this->array;
    }
}