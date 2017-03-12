<?php

namespace Klausi\YamlComments;

class YamlComments
{

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
}
