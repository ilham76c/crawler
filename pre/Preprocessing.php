<?php
require_once __DIR__ . '/vendor/autoload.php';

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container
class Preprocessing {

    public function __construct() {
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        $this->stemmer  = $stemmerFactory->createStemmer();
        $stopwordFactory = new Sastrawi\StopWordRemover\StopWordRemoverFactory();
        $this->stopword = $stopwordFactory->createStopWordRemover();
        $tokenizerFactory = new Sastrawi\Tokenizer\TokenizerFactory();
        $this->tokenizer = $tokenizerFactory->createDefaultTokenizer();
    }
    
    public function stemming(string $sentence) : string
    {
        return $this->stemmer->stem($sentence);
    }

    public function stopwordRemoval(string $sentence) : string 
    {
        return $this->stopword->remove($sentence);
    }

    public function tokenizing(string $sentence) : array
    {
        return $this->tokenizer->tokenize($sentence);
    }   
}