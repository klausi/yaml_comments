<?php

namespace Klausi\YamlComments;

use Symfony\Component\Yaml\Yaml;

class YamlComments
{
    
    /**
     * The parsed YAML content as returned by the Symfony YAML component.
     *
     * @var mixed
     */
    private $yaml;

    /**
     * The comment lines, indexed by line number.
     *
     * @var array
     */
    private $comments;

    /**
     * Constructor.
     *
     * @param mixed $yaml
     *   The parsed YAML structure as returned by the Symfony YAML component.
     * @param string[] $comments
     *   The parsed comment lines, indexed by line number.
     */
    public function __construct($yaml, array $comments)
    {
        $this->yaml = $yaml;
        $this->comments = $comments;
    }

    /**
     * Parses YAML into a PHP value and an array of comment lines.
     *
     * @param string $yamlString
     *   The multi-line YAML contents.
     * @param int $flags
     *   (Optional) A bit field of PARSE_* constants to customize the YAML
     *   parser behavior. See \Symfony\Component\Yaml\Yaml::parse().
     *
     * @return ParseResult
     *
     * @throws ParseException
     *   If the YAML is not valid.
     */
    public static function parse($yamlString, $flags = 0)
    {
        $yaml = new Parser();

        return $yaml->parse($yamlString, $flags);
    }

    /**
     * Parses YAML for comment lines.
     *
     * @param string $yamlString
     *   The multi-line YAML contents.
     *
     * @return string[]
     *   The comment lines, indexed by line number. Line counting starts with 1.
     */
    public static function parseComments($yamlString)
    {
        // Cleanup to support various operating system line endings.
        $yamlString = str_replace(["\r\n", "\r"], "\n", $yamlString);
        $lines = explode("\n", $yamlString);
        $comments = [];
        foreach ($lines as $lineNr => $line) {
            if (preg_match('/^[\s]*#/', $line) === 1) {
                // We want to index our lines by human readable line numbers, so
                // we start with 1 instead of 0.
                $comments[$lineNr + 1] = $line;
            }
        }

        return $comments;
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
        $line = 0;
        $structure = $this->yaml;
        foreach ($keyPath as $key) {
            foreach ($structure as $currentKey => $value) {
                $line++;
                if ($currentKey === $key) {
                    $structure = $value;
                    continue 2;
                }
            }
        }

        foreach ($this->comments as $commentLineNr => $comment) {
            if ($commentLineNr <= $line) {
                $line++;
            }
        }

        return $line;
    }

    public function getYaml()
    {
        return $this->yaml;
    }

    public function getComments()
    {
        return $this->comments;
    }
}
