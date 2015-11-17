<?php
namespace Reader;


use Exception;
use Generator;
use LogicException;

class FileLineReader {
    /**
     * @var string
     */
    protected $fileOpenMode = 'r';

    /**
     * @var string
     */
    protected $fileName = '';

    /**
     * @var resource
     */
    protected $FileContext = null;

    /**
     * @param string $filename
     */
    public function __construct($filename) {
        $this->fileName = $filename;
    }

    public function __destruct() {
        $this->closeFile();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function openFile() {
        $this->FileContext = null;

        if (!file_exists($this->fileName)) {
            throw new LogicException("Cannot open {$this->fileName}. File not found");
        }

        $this->FileContext = fopen($this->fileName, $this->fileOpenMode);
        if ($this->FileContext === false) {
            throw new Exception("Error open file {$this->fileName}");
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function closeFile() {
        if (!$this->FileContext) {
            return false;
        }

        fclose($this->FileContext);
        $this->FileContext = null;

        return true;
    }

    /**
     * @return Generator
     * @throws Exception
     */
    public function eachLines() {
        while (($buffer = $this->readLine()) !== false) {
            yield $buffer;
        }

        if (!feof($this->FileContext)) {
            throw new Exception('Error: unexpected fgets() fail');
        }
    }

    /**
     * @return string
     * @throws LogicException
     */
    public function readLine() {
        if (!$this->FileContext) {
            throw new LogicException("You need open file, before read lines");
        }

        return fgets($this->FileContext);
    }
}