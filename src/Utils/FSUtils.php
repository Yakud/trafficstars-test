<?php
namespace Utils;


class FSUtils {
    /**
     * @param string $folder
     * @return bool
     */
    public static function createFolderIfNotExists($folder) {
        if (!file_exists($folder)) {
            return mkdir($folder, 0777, true);
        }

        return false;
    }

    /**
     * @param string $filename
     * @return bool
     */
    public static function createFileIfNotExists($filename) {
        if (!file_exists($filename)) {
            file_put_contents($filename, '');
            return true;
        }

        return false;
    }
}