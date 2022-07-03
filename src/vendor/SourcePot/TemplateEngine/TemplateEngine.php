<?php

namespace SourcePot\TemplateEngine;

class TemplateEngine
{
    public const TOKEN_START = '{{';
    public const TOKEN_END = '}}';

    private static $baseDirectory = '';

    public static function setBaseDirectory(string $baseDir): void
    {
        if(!str_ends_with($baseDir, '/')) {
            $baseDir .= '/';
        }
        self::$baseDirectory = $baseDir;
    }

    public static function baseDirectory(): string
    {
        return self::$baseDirectory;
    }

    public static function loadFromFile(string $filename): IncludeComponent
    {
        return new IncludeComponent($filename);
    }
}
