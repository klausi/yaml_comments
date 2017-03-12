<?php

namespace Klausi\YamlComments;

/**
 * Holds all data after a YAML file has been parsed.
 */
class ParseResult
{

    /**
     * Magic array key used in $this->lineNumbers for nested structures.
     */
    const LINE_NUMBER_KEY = '__yaml_line_number';

    /**
     * The parsed data.
     *
     * @var mixed
     */
    private $data;

    /**
     * Nested array of line numbers for the parsed data.
     *
     * Has the same structure as $this->data except that the array leafs are
     * line numbers instead of parsed values.
     *
     * @var array
     */
    private $lineNumbers;

    /**
     * The comment lines, indexed by line number.
     *
     * @var string[]
     */
    private $comments;

    /**
     * Constructor.
     */
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
        // @todo throw exception if key path is invalid in data.

        $lineNumbers = $this->lineNumbers;
        $data = $this->data;
        foreach ($keyPath as $key) {
            // Embedded references: just return the line of the reference.
            if (isset($data[$key]) && !isset($lineNumbers[$key])) {
                return $lineNumbers;
            }
            $lineNumbers = $lineNumbers[$key];
            $data = $data[$key];
        }

        if (is_array($lineNumbers)) {
            $lineNumbers = $lineNumbers[self::LINE_NUMBER_KEY];
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
