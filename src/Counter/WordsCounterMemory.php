<?php
namespace Counter;

use Utils\ArrayUtils;

class WordsCounterMemory {
    /**
     * @var int
     */
    protected $maxWordsInMemory = 100;

    /**
     * @var array
     */
    protected $wordsCounters = [];

    /**
     * @param string $word
     * @return bool
     * @throws OutOfMemoryException
     */
    public function addWord($word) {
        if (!$this->isWordExists($word)) {
            if ($this->getCountWords() >= $this->maxWordsInMemory) {
                throw new OutOfMemoryException('Out of memory for words memory counters. Maximum ' . $this->maxWordsInMemory . ' words.');
            }
        }

        ArrayUtils::increment($this->wordsCounters, $word, 1);

        return true;
    }

    /**
     * @return void
     */
    public function clearCounters() {
        $this->wordsCounters = [];
    }

    /**
     * @return array
     */
    public function getWordsCounters() {
        return $this->wordsCounters;
    }

    /**
     * @param string $word
     * @return bool
     */
    public function isWordExists($word) {
        return array_key_exists($word, $this->wordsCounters);
    }

    /**
     * @return int
     */
    public function getCountWords() {
        return sizeof($this->wordsCounters);
    }
}