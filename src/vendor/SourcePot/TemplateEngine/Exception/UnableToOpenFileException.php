<?php

namespace SourcePot\TemplateEngine\Exception;

use RuntimeException;

class UnableToOpenFileException extends RuntimeException
{
    public function __construct(string $filename)
    {
        parent::__construct("Unable to open file: $filename");
    }
}