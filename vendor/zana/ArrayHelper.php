<?php namespace Zana;

class ArrayHelper {
    private array $array;

    public function __construct(array $array) {
        $this->array = $array;
    }

    /**
     * Get the first element of the array.
     *
     * @return mixed The first element of the array, or null if the array is empty.
     */
    public function first() {
        return empty($this->array) ? null : $this->array[0];
    }

    /**
     * Get the last element of the array.
     *
     * @return mixed The last element of the array, or null if the array is empty.
     */
    public function last() {
        return empty($this->array) ? null : end($this->array);
    }

    /**
     * Get an element at a specific index.
     *
     * @param int $index The index of the element to retrieve.
     *
     * @return mixed The element at the specified index, or null if the index is out of range.
     */
    public function at(int $index) {
        return $this->array[$index] ?? null;
    }

    /**
     * Get all elements of the array.
     *
     * @return array The entire array.
     */
    public function all(): array {
        return $this->array;
    }

    /**
     * Get the count of elements in the array.
     *
     * @return int The number of elements in the array.
     */
    public function count(): int {
        return count($this->array);
    }

    /**
     * Clear the array.
     *
     * @return void
     */
    public function clear(): void {
        $this->array = [];
    }

    /**
     * Add an element to the array.
     *
     * @param mixed $element The element to add.
     * @return void
     */
    public function add($element): void {
        $this->array[] = $element;
    }

    /**
     * Remove an element at a specific index.
     *
     * @param int $index The index of the element to remove.
     * @return mixed The removed element, or null if the index is out of range.
     */
    public function remove(int $index) {
        if (isset($this->array[$index])) {
            $removedElement = $this->array[$index];
            unset($this->array[$index]);
            $this->array = array_values($this->array); // Reindex the array
            return $removedElement;
        }
        return null;
    }

    /**
     * Find an element in the array based on a callback condition.
     *
     * @param callable $callback The callback function to evaluate each element.
     * @return mixed The found element, or null if not found.
     */
    public function find(callable $callback) {
        foreach ($this->array as $element) {
            if ($callback($element)) {
                return $element;
            }
        }
        return null;
    }
}