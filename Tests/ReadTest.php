<?php

namespace Klausi\YamlComments\Tests;

use Klausi\YamlComments\YamlComments;
use Symfony\Component\Yaml\Yaml;

class ReadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the YAML and the comment lines are parsed correctly.
     */
    public function testParse()
    {
        $yaml = <<<'EOF'
name: Node
type: module
description: 'Allows content to be submitted to the site and displayed on pages.'
package: Core
version: VERSION
core: 8.x
configure: entity.node_type.collection
dependencies:
  - text
  - drupal:rest
  - views
  # Some comment here.
  - rules
EOF;
        $yamlComments = YamlComments::parse($yaml);
        $this->assertSame(Yaml::parse($yaml), $yamlComments->getYaml());
        $this->assertSame([12 => '  # Some comment here.'], $yamlComments->getComments());
    }
}
