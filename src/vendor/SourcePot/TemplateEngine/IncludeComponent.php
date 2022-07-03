<?php

namespace SourcePot\TemplateEngine;

use Exception\FileNotFoundException;
use Exception\UnableToOpenFileException;

class IncludeComponent implements ComponentInterface
{
    private string $content;
    private Template $template;

    public function __construct(string $filename)
    {
        if(file_exists(TemplateEngine::baseDirectory().$filename)) {
            $this->loadFromFile(TemplateEngine::baseDirectory().$filename);
            return;
        }

        if(file_exists($filename)) {
            $this->loadFromFile($filename);
            return;
        }

        throw new FileNotFoundException($fileName);
    }

    private function loadFromFile(string $filename): Template
    {
        $content = file_get_contents($filename);
        if($content === false) {
            // error loading file
            throw new UnableToOpenFileException($filename);
        }

        $this->content = $content;
        $this->template = new Template($content);
    }
}
