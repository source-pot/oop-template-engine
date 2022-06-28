<?php

namespace SourcePot\TemplateEngine;

interface ComponentInterface
{
    public function parse(array $data = []): self;
    public function render(): string;
}
