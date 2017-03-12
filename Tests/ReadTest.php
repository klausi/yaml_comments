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
        $parseResult = YamlComments::parse($yaml);
        $this->assertSame(Yaml::parse($yaml), $parseResult->getData());
        $this->assertSame([12 => '  # Some comment here.'], $parseResult->getComments());
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
        $parseResult = YamlComments::parse($yaml);
        $this->assertSame(1, $parseResult->getLineNumber('name'));
        $this->assertSame(2, $parseResult->getLineNumber('type'));
        $this->assertSame(4, $parseResult->getLineNumber('description'));
        $this->assertSame(5, $parseResult->getLineNumber('package'));
        $this->assertSame(6, $parseResult->getLineNumber('version'));
        $this->assertSame(7, $parseResult->getLineNumber('core'));
        $this->assertSame(8, $parseResult->getLineNumber('configure'));
        $this->assertSame(9, $parseResult->getLineNumber('dependencies'));
        $this->assertSame(10, $parseResult->getLineNumber(['dependencies', 0]));
        $this->assertSame(11, $parseResult->getLineNumber(['dependencies', 1]));
        $this->assertSame(12, $parseResult->getLineNumber(['dependencies', 2]));
        $this->assertSame(14, $parseResult->getLineNumber(['dependencies', 3]));
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
        $parseResult = YamlComments::parse($yaml);
        $this->assertSame(1, $parseResult->getLineNumber('core'));
        $this->assertSame(2, $parseResult->getLineNumber('name'));
        $this->assertSame(5, $parseResult->getLineNumber('project'));
        $this->assertSame(6, $parseResult->getLineNumber('timestamp'));
        $this->assertSame(7, $parseResult->getLineNumber('version'));
    }

    public function testReferenceLines()
    {
        $yaml = <<<'EOF'
- &hello
    Meat: pork
    Starch: potato
- banana
- *hello
EOF;
        $parseResult = YamlComments::parse($yaml);
        $this->assertSame(1, $parseResult->getLineNumber(0));
        $this->assertSame(2, $parseResult->getLineNumber([0, 'Meat']));
        $this->assertSame(3, $parseResult->getLineNumber([0, 'Starch']));
        $this->assertSame(4, $parseResult->getLineNumber(1));
        $this->assertSame(5, $parseResult->getLineNumber(2));
        $this->assertSame(5, $parseResult->getLineNumber([2, 'Meat']));
        $this->assertSame(5, $parseResult->getLineNumber([2, 'Starch']));
    }
}
