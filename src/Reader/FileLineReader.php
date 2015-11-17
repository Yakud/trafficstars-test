<?php
namespace Reader;

use Exception;
use Generator;
use LogicException;

class FileLineReader {
    /**
     * Filename path
     * @var string
     */
    protected $fileName = '';

    /**
     * Open file context
     * @var resource
     */
    protected $FileContext = null;

    /**
     * @param string $filename
     */
    public function __construct($filename = '') {
        $this->fileName = $filename;
    }

    public function __destruct() {
        $this->closeFile();
    }

    /**
     * Open file and save context
     * @param string $fileOpenMode
     * @return bool
     * @throws Exception
     */
    public function openFile($fileOpenMode = 'r') {
        $this->FileContext = null;

        if (!file_exists($this->fileName)) {
            throw new LogicException("Cannot open {$this->fileName}. File not found");
        }

        $this->FileContext = fopen($this->fileName, $fileOpenMode);
        if ($this->FileContext === false) {
            throw new Exception("Error open file {$this->fileName}");
        }

        return true;
    }

    /**
     * Close file by context
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
     * Read line from file to Generator
     * @return Generator
     * @throws Exception
     */
    public function eachLines() {
        while (($buffer = $this->readLine()) !== false) {
            yield $buffer;
        }
    }

    /**
     * Read line from file
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