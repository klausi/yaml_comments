<?php

/**
 * This file has been copied from the Symfony YAML package because we override
 * many internals and don't want to depend on symfony/yaml.
 */

namespace Klausi\YamlComments\Exception;

/**
 * Exception class thrown when an error occurs during dumping.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DumpException extends RuntimeException
{
}
