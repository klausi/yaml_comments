<?php

namespace Klausi\YamlComments;

class ParseResult
{

    private $data;
    private $lineNumbers;
    private $comments;

    public function __construct($data, array $lineNumbers = [], array $comments = [])
    {
        $this->data = $data;
        $this->lineNumbers = $lineNumbers;
        $this->comments = $comments;
    }

    /**
     * Determines the line number of a given key in the YAML content.
     *
     * @param string|array $keyPath
     *   Either a simple key string for the first level of key/value pairs in
     *   the YAML or a sequence of keys that should be traversed in a nested
     *   structure.
     *
     * @return int
     *   The line number where the given key appears.
     */
    public function getLineNumber($keyPath)
    {
        // Support a simple key path passed as single string.
        if (!is_array($keyPath)) {
            $keyPath = [$keyPath];
        }
        $lineNumbers = $this->lineNumbers;
        foreach ($keyPath as $key) {
            $lineNumbers = $lineNumbers[$key];
        }

        if (is_array($lineNumbers)) {
            $lineNumbers = $lineNumbers['__yaml_line_number'];
        }

        return $lineNumbers;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getLineNumbers()
    {
        return $this->lineNumbers;
    }

    public function getComments()
    {
        return $this->comments;
    }
}
