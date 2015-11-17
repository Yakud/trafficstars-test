<?php

use Counter\OutOfMemoryException;
use Counter\WordsCounterDisk;
use Counter\WordsCounterMemory;
use Parser\WordsMiner;
use Reader\FileLineReader;

require_once __DIR__ . '/../bootstrap/bootstrap.php';

$fileName = $argv[1];

$time = microtime(true);

$FileReader = new FileLineReader($fileName);
$WordsMiner = new WordsMiner();
$WordsCounterMemory = new WordsCounterMemory();
$WordsCounterDisk = new WordsCounterDisk();

$FileReader->openFile();

foreach($FileReader->eachLines() as $line) {
    $words = $WordsMiner->getWords($line);

    foreach ($words as $word) {
        try {
            $WordsCounterMemory->addWord($word);
        } catch (OutOfMemoryException $Ex) {
            foreach ($WordsCounterMemory->getWordsCounters() as $wordConcrete => $counter) {
                $WordsCounterDisk->addWord($wordConcrete, $counter);
            }

//            $WordsCounterDisk->addWords($WordsCounterMemory->getWordsCounters());

            $WordsCounterMemory->clearCounters();
            $WordsCounterMemory->addWord($word);
        }
    }
}

$FileReader->closeFile();

foreach ($WordsCounterDisk->eachWords() as $word => $count) {
    echo $word . ':' . $count, PHP_EOL;
}

echo "Parse time: ", (microtime(true) - $time), PHP_EOL;

