<?php

namespace SourcePot\TemplateEngine;

class IfComponent implements ComponentInterface
{
    private bool $showContent;

    public function __construct(
        private string $content,
        private string $truthyVariable
    ) { }

    public function parse(array $data = []): self
    {
        $this->showContent = (bool)$data[$this->truthyVariable];
        return $this;
    }

    public function render(): string
    {
        return $this->showContent ? $this->content : '';
    }
}
