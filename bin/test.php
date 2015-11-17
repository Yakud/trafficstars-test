<?php

use Parser\Tokenizer;
use Reader\FileLineReader;

require_once __DIR__ . '/../bootstrap/bootstrap.php';


$fileName = $argv[1];

$FileReader = new FileLineReader($fileName);
$Tokenizer = new Tokenizer();

$FileReader->openFile();
foreach($FileReader->eachLines() as $line) {
    $line = $Tokenizer->getWords($line);

    var_export($line);
//    echo $line, PHP_EOL;
}
$FileReader->closeFile();