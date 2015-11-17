<?php
namespace DB;


use Utils\ArrayUtils;
use Utils\FSUtils;

class FileDB {
    protected $data = [];

    protected $dbName = '';
    protected $dbPath = '';

    /**
     * @param string $dbName
     * @param string $dbPath
     */
    public function __construct($dbName, $dbPath) {
        $this->dbName = $dbName;
        $this->dbPath = $dbPath;
        $this->init();
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param string $key
     * @param int $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return ArrayUtils::get($this->data, $key, $default);
    }

    /**
     * Increment key value
     * @param string $key
     * @param int $increment
     */
    public function increment($key, $increment) {
        ArrayUtils::increment($this->data, $key, $increment);
    }

    /**
     * Set key value
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        ArrayUtils::set($this->data, $key, $value);
    }

    /**
     * Load data in memory
     * @return bool
     */
    public function load() {
        $jsonData = file_get_contents($this->getPathFull());
        if ($jsonData) {
            $this->data = json_decode($jsonData, true);
            if (!is_array($this->data)) {
                $this->data = [];
            }
        } else {
            $this->data = [];
        }

        return true;
    }

    /**
     * Save db on disk
     * @return void
     */
    public function save() {
        $jsonData = json_encode($this->data);
        file_put_contents($this->getPathFull(), $jsonData);
    }


    /**
     * @return void
     */
    protected function init() {
        $folderPath = $this->getPathFolder();
        FSUtils::createFolderIfNotExists($folderPath);

        $filePath = $this->getPathFull();
        FSUtils::createFileIfNotExists($filePath);
    }

    /**
     * @return string
     */
    protected function getPathFull() {
        return $this->getPathFolder() . '/' . $this->dbName;
    }

    /**
     * @return string
     */
    protected function getPathFolder() {
        return $this->dbPath;
    }
}