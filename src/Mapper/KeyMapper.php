<?php

namespace Mapper;

class KeyMapper {
    /**
     * Generate chunk index by string
     * @param string $key
     * @param int $countChunks
     * @return int
     */
    public function getForKey($key, $countChunks) {
        return $this->getNumericHash($key) % $countChunks;
    }

    /**
     * Get number from string
     * @param string $key
     * @return int
     */
    protected function getNumericHash($key) {
        return (int) base_convert(substr(sha1($key), 0, 8), 16, 10);
    }
}
 