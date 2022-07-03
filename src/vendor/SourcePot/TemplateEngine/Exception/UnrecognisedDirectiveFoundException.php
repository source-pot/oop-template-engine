<?php

namespace SourcePot\TemplateEngine\Exception;

use RuntimeException;

class UnrecognisedDirectiveFoundException extends RuntimeException
{
    public function __construct(string $directive)
    {
        parent::__construct("Invalid template directive found: $directive");
    }
}