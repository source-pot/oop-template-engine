<?php

namespace SourcePot\TemplateEngine;

use SourcePot\TemplateEngine\Exception\FileNotFoundException;
use SourcePot\TemplateEngine\Exception\UnableToOpenFileException;

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

        throw new FileNotFoundException($filename);
    }

    private function loadFromFile(string $filename): void
    {
        try {
            $content = file_get_contents($filename);
            if($content === false) {
                // error loading file
                throw new UnableToOpenFileException($filename);
            }
        } catch(\Throwable $t) {
            throw new UnableToOpenFileException($t->getMessage());
        }

        $this->content = $content;
        $this->template = new Template($content);
    }

    public function parse(array $data = []): self
    {
        $this->template->parse($data);
        return $this;
    }

    public function render(): string
    {
        return $this->template->render();
    }
}
