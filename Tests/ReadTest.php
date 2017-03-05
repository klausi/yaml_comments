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

    /**
     * Tests retrieving the line number for any given key.
     */
    public function testLineNumber()
    {
        $yaml = <<<'EOF'
name: Node
type: module
# Messing up line numbers with comments, yay!
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
        $this->assertSame(1, $yamlComments->getLineNumber('name'));
        $this->assertSame(2, $yamlComments->getLineNumber('type'));
        $this->assertSame(4, $yamlComments->getLineNumber('description'));
        $this->assertSame(5, $yamlComments->getLineNumber('package'));
        $this->assertSame(6, $yamlComments->getLineNumber('version'));
        $this->assertSame(7, $yamlComments->getLineNumber('core'));
        $this->assertSame(8, $yamlComments->getLineNumber('configure'));
        $this->assertSame(9, $yamlComments->getLineNumber('dependencies'));
        $this->assertSame(10, $yamlComments->getLineNumber(['dependencies', 0]));
        $this->assertSame(11, $yamlComments->getLineNumber(['dependencies', 1]));
        $this->assertSame(12, $yamlComments->getLineNumber(['dependencies', 2]));
        $this->assertSame(14, $yamlComments->getLineNumber(['dependencies', 3]));
    }
}
