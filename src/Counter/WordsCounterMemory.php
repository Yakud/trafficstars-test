<?php
namespace Counter;

use Utils\ArrayUtils;

class WordsCounterMemory {
    /**
     * Maximum count words counters in memory
     * @var int
     */
    protected $maxWordsInMemory = MAX_WORDS_IN_MEMORY;

    /**
     * Words counters
     * @var array
     */
    protected $wordsCounters = [];

    /**
     * Add word to memory counters
     * @param string $word
     * @return bool
     * @throws OutOfMemoryException
     */
    public function addWord($word) {
        if (!$this->isWordExists($word)) {
            if ($this->getNumberWords() >= $this->maxWordsInMemory) {
                throw new OutOfMemoryException('Out of memory for words counters. Maximum ' . $this->maxWordsInMemory . ' words.');
            }
        }

        ArrayUtils::increment($this->wordsCounters, $word, 1);

        return true;
    }

    /**
     * Clear memory counters
     * @return void
     */
    public function clearCounters() {
        $this->wordsCounters = [];
    }

    /**
     * Return words dictionary with counters
     * @return array
     */
    public function getWordsWithCounters() {
        return $this->wordsCounters;
    }

    /**
     * Is word exists in memory
     * @param string $word
     * @return bool
     */
    public function isWordExists($word) {
        return array_key_exists($word, $this->wordsCounters);
    }

    /**
     * Get count unique words
     * @return int
     */
    public function getNumberWords() {
        return sizeof($this->wordsCounters);
    }
}