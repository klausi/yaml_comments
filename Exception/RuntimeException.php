<?php

/**
 * This file has been copied from the Symfony YAML package because we override
 * many internals and don't want to depend on symfony/yaml.
 */

namespace Klausi\YamlComments\Exception;

/**
 * Exception class thrown when an error occurs during parsing.
 *
 * @author Romain Neutron <imprec@gmail.com>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
