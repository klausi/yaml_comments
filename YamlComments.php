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
     * @return static
     *
     * @throws ParseException
     *   If the YAML is not valid.
     */
    public static function parse($yamlString, $flags = 0)
    {
        $yaml = Yaml::parse($yamlString, $flags);
        $comments = static::parseComments($yamlString);
        return new static($yaml, $comments);
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

    public function getYaml()
    {
        return $this->yaml;
    }

    public function getComments()
    {
        return $this->comments;
    }
}
