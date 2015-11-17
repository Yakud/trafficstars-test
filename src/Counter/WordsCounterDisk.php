<?php
namespace Counter;

use DB\FileDB;
use Generator;
use Mapper\KeyMapper;
use Reader\FileLineReader;

class WordsCounterDisk {
    /**
     * @var int
     */
    protected $chunksCount = 10;

    /**
     * @var string
     */
    protected $tempDirectory = PATH_TEMP;

    /**
     * @var string
     */
    protected $chunkFilename = 'chunk.data';

    /**
     * @var FileLineReader
     */
    protected $FileLineReader = null;

    /**
     * @var KeyMapper
     */
    protected $KeyMapper = null;

    /**
     * @param string $word
     * @param int $count
     */
    public function addWord($word, $count) {
        $FileDB = $this->getDbForWord($word);
        $FileDB->load();
        $FileDB->increment($word, $count);
        $FileDB->save();
    }

    /**
     * @param string $word
     * @return FileDB
     */
    protected function getDbForWord($word) {
        $chunk = $this->getChunkForWord($word);
        return $this->getDbForChunk($chunk);
    }

    /**
     * @param string $chunk
     * @return FileDB
     */
    protected function getDbForChunk($chunk) {
        $folderName = $this->getChunkFolder($chunk);

        return new FileDB($this->chunkFilename, $folderName);
    }

    /**
     * @param string $chunk
     * @return string
     */
    protected function getChunkFolder($chunk) {
        return $this->tempDirectory . '/' . $chunk;
    }

    /**
     * @param string $word
     * @return int
     */
    protected function getChunkForWord($word) {
        $index = $this->getKeyMapper()->getForKey($word, $this->chunksCount);
        return $this->getChunkHash($index);
    }

    protected function getChunkHash($chunk) {
        return substr(sha1($chunk), 0, 8);
    }

    /**
     * @return KeyMapper
     */
    protected function getKeyMapper() {
        if (is_null($this->KeyMapper)) {
            $this->KeyMapper = new KeyMapper();
        }

        return $this->KeyMapper;
    }

    /**
     * @return Generator
     */
    public function eachWords() {
        for ($i = 0; $i < $this->chunksCount; $i++) {
            $chunk = $this->getChunkHash($i);
            $FileDB = $this->getDbForChunk($chunk);
            $FileDB->load();

            $words = $FileDB->getData();

            foreach ($words as $word => $count) {
                yield $word => $count;
            }

            unset($FileDB);
        }
    }

    public function addWords($getWordsCounters) {
        // группировка
    }
}