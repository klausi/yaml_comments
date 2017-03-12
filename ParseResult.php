<?php

namespace Klausi\YamlComments;

class ParseResult {

    private $data;
    private $lineNumbers;
    private $comments;

    public function __construct($data, array $lineNumbers = [], array $comments = []) {
        $this->data = $data;
        $this->lineNumbers = $lineNumbers;
        $this->comments = $comments;
    }

    function getData() {
        return $this->data;
    }

    function getLineNumbers() {
        return $this->lineNumbers;
    }

    function getComments() {
        return $this->comments;
    }

}
