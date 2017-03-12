<?php

namespace Klausi\YamlComments\Tests;

use Klausi\YamlComments\Parser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    protected $parser;

    protected function setUp()
    {
        $this->parser = new Parser();
    }

    protected function tearDown()
    {
        $this->parser = null;
    }

    /**
     * @dataProvider getDataFormSpecifications
     */
    public function testSpecifications($expected, $yaml, $lineNumbers, $message)
    {
        $parseResult = $this->parser->parse($yaml);
        $this->assertEquals($expected, var_export($parseResult->getData(), true), $message);
        $this->assertEquals($lineNumbers, var_export($parseResult->getLineNumbers(), true), $message);
    }

    public function getDataFormSpecifications()
    {
        $parser = new Parser();
        $path = __DIR__.'/Fixtures';

        $tests = [];
        $files = glob("$path/*.yml");
        foreach ($files as $file) {
            $yamls = file_get_contents($file);

            // split YAMLs documents
            foreach (preg_split('/^---( %YAML\:1\.0)?/m', $yamls) as $yaml) {
                if (!$yaml) {
                    continue;
                }

                $test = Yaml::parse($yaml);
                if (isset($test['todo']) && $test['todo']) {
                    // TODO
                } else {
                    eval('$expected = '.trim($test['php']).';');
                    eval('$lineNumbers = '.trim($test['line_numbers']).';');

                    $tests[] = [var_export($expected, true), $test['yaml'], var_export($lineNumbers, true), $test['test']];
                }
            }
        }

        return $tests;
    }
}
