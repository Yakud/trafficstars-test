<?php
namespace Parser;


class WordsMiner {
    /**
     * Get array words from string
     * @param string $line
     * @return array
     */
    public function getWords($line) {
        // Convert string to lowercase
        $lowercaseLine = mb_strtolower(trim($line), 'UTF-8');

        // Replace symbol "-" to " "
        $lowercaseLine = mb_ereg_replace('[\-]', ' ', $lowercaseLine);

        // Leave only characters and digits
        $onlyWordsLine = mb_ereg_replace('[^a-zA-Z0-9а-яА-Я ]', '', $lowercaseLine);

        $words = explode(' ', $onlyWordsLine);

        // Filter empty words and numbers
        $result = array_filter($words, function($word) {
            return $word && !is_numeric($word);
        });

        return $result;
    }
}