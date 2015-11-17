<?php

use Counter\OutOfMemoryException;
use Counter\WordsCounterMemory;
use Parser\WordsMiner;
use Reader\FileLineReader;

require_once __DIR__ . '/../bootstrap/bootstrap.php';

$fileName = $argv[1];

$FileReader = new FileLineReader($fileName);
$WordsMiner = new WordsMiner();
$WordsCounterMemory = new WordsCounterMemory();

$FileReader->openFile();
foreach($FileReader->eachLines() as $line) {
    $words = $WordsMiner->getWords($line);

    foreach ($words as $word) {
        try {
            $WordsCounterMemory->addWord($word);
        } catch (OutOfMemoryException $Ex) {
            // TODO put words on disk
            throw $Ex;
        }
    }

}
$FileReader->closeFile();

var_export($WordsCounterMemory->getWordsCounters());