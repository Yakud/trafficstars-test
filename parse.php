<?php

use Counter\OutOfMemoryException;
use Counter\WordsCounterDisk;
use Counter\WordsCounterMemory;
use Parser\WordsMiner;
use Reader\FileLineReader;

require_once __DIR__ . '/bootstrap/bootstrap.php';

$fileName = $argv[1];

/**
 * DEFINES
 */
define('MAX_WORDS_IN_MEMORY', 1000);
define('DB_CHUNKS_COUNT', 15);

/**
 * INIT INSTANCES
 */
$time = microtime(true);
$FileReader = new FileLineReader($fileName);
$WordsMiner = new WordsMiner();
$WordsCounterMemory = new WordsCounterMemory();
$WordsCounterDisk   = new WordsCounterDisk();

/**
 * CLEAR TMP
 */
array_map('unlink', glob(PATH_TEMP . '/*/*'));
array_map('rmdir', glob(PATH_TEMP . '/*'));

/**
 * MAIN
 */
$FileReader->openFile();
foreach($FileReader->eachLines() as $line) {
    // Read each lines and get words
    $words = $WordsMiner->getWords($line);

    foreach ($words as $word) {
        try {
            // Try add word in memory
            $WordsCounterMemory->addWord($word);
        } catch (OutOfMemoryException $Ex) {
            // Out of memory, write words from memory to drive
            $WordsCounterDisk->addWords($WordsCounterMemory->getWordsWithCounters());

            // Clear memory, add word
            $WordsCounterMemory->clearCounters();
            $WordsCounterMemory->addWord($word);
        }
    }
}

// Write words from memory to drive
if ($wordsWithCounters = $WordsCounterMemory->getWordsWithCounters()) {
    $WordsCounterDisk->addWords($wordsWithCounters);
}

$FileReader->closeFile();

// Shown all words from disk
foreach ($WordsCounterDisk->eachWords() as $word => $count) {
    echo $word . ':' . $count, PHP_EOL;
}

echo "Parse time: ", (microtime(true) - $time), PHP_EOL;