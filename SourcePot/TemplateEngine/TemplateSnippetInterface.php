<?php

namespace SourcePot\TemplateEngine;

interface TemplateSnippetInterface
{
    public function parse(array $data = []): void;
    public function render(): string;
}
