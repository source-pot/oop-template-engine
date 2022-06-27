<?php

namespace SourcePot\TemplateEngine;

class TextContent implements TemplateSnippetInterface
{
    public function __construct(
        private string $content = ''
    ) { }

    public function parse(array $data = []): void
    {
        // noop
    }

    public function render(): string
    {
        return $this->content;
    }
}
