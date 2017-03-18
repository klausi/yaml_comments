<?php

namespace Klausi\YamlComments;

class YamlComments
{

    const DUMP_OBJECT = 1;
    const PARSE_EXCEPTION_ON_INVALID_TYPE = 2;
    const PARSE_OBJECT = 4;
    const PARSE_OBJECT_FOR_MAP = 8;
    const DUMP_EXCEPTION_ON_INVALID_TYPE = 16;
    const PARSE_DATETIME = 32;
    const DUMP_OBJECT_AS_MAP = 64;
    const DUMP_MULTI_LINE_LITERAL_BLOCK = 128;
    const PARSE_CONSTANT = 256;

    /**
     * Parses YAML into a PHP value and an array of comment lines.
     *
     * @param string $yamlString
     *   The multi-line YAML contents.
     * @param int $flags
     *   (Optional) A bit field of PARSE_* constants to customize the YAML
     *   parser behavior. See the constants on this class.
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
