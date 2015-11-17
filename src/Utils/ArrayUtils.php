<?php
namespace Utils;


class ArrayUtils {
    /**
     * Get array value by key or default
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(array $array, $key, $default = null) {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

    /**
     * Set array value by key
     *
     * @param array $array
     * @param string $key
     * @param $value
     * @return mixed
     */
    public static function set(array &$array, $key, $value) {
        $array[$key] = $value;
    }

    /**
     * Increment array value by key
     * @param array $array
     * @param string $key
     * @param int $increment
     */
    public static function increment(array &$array, $key, $increment = 1) {
        $value = (int) self::get($array, $key, 0);
        self::set($array, $key, $value + $increment);
    }
}