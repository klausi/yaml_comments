# YamlComments
Parses YAML and provides comments with their line numbers as well as the line
number of any given key of the document structure.

## Usage

```php
use Klausi\YamlComments\YamlComments;

$exampleYaml = <<<'EOF'
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

$yamlComments = YamlComments::parse($exampleYaml);
```

Get the comment lines of this document:
```php
print_r($yamlComments->getComments());
```
```
Array
(
    [3] => # Messing up line numbers with comments, yay!
    [13] =>   # Some comment here.
)
```

Get the line number of a particular key:
```php
print $yamlComments->getLineNumber('name');
1
print $yamlComments->getLineNumber('description');
4
print $yamlComments->getLineNumber(['dependencies', 3]);
14
```
