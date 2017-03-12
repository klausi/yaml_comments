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
        $yamlResult = YamlComments::parse($yaml);
        $this->assertSame(Yaml::parse($yaml), $yamlResult->getData());
        $this->assertSame([12 => '  # Some comment here.'], $yamlResult->getComments());
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
        $yamlResult = YamlComments::parse($yaml);
        $this->assertSame(1, $yamlResult->getLineNumber('name'));
        $this->assertSame(2, $yamlResult->getLineNumber('type'));
        $this->assertSame(4, $yamlResult->getLineNumber('description'));
        $this->assertSame(5, $yamlResult->getLineNumber('package'));
        $this->assertSame(6, $yamlResult->getLineNumber('version'));
        $this->assertSame(7, $yamlResult->getLineNumber('core'));
        $this->assertSame(8, $yamlResult->getLineNumber('configure'));
        $this->assertSame(9, $yamlResult->getLineNumber('dependencies'));
        $this->assertSame(10, $yamlResult->getLineNumber(['dependencies', 0]));
        $this->assertSame(11, $yamlResult->getLineNumber(['dependencies', 1]));
        $this->assertSame(12, $yamlResult->getLineNumber(['dependencies', 2]));
        $this->assertSame(14, $yamlResult->getLineNumber(['dependencies', 3]));
    }

    public function testEmptyLine()
    {
        $yaml = <<<'EOF'
core: 8.x
name: Test

# These should not be here, will be added by drupal.org packaging.
project: test
timestamp: 1234567
version: 1.2
EOF;
        $yamlResult = YamlComments::parse($yaml);
        $this->assertSame(1, $yamlResult->getLineNumber('core'));
        $this->assertSame(2, $yamlResult->getLineNumber('name'));
        $this->assertSame(5, $yamlResult->getLineNumber('project'));
        $this->assertSame(6, $yamlResult->getLineNumber('timestamp'));
        $this->assertSame(7, $yamlResult->getLineNumber('version'));
    }
}
