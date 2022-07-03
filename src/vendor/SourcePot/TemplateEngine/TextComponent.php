<?php

namespace SourcePot\TemplateEngine;

class TextComponent implements ComponentInterface
{
    public function __construct(
        private string $content = ''
    ) { }

    public function parse(array $data = []): self
    {
        // noop
        return $this;
    }

    public function render(): string
    {
        return $this->content;
    }
}
