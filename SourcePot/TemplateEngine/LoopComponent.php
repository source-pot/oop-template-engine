<?php

namespace SourcePot\TemplateEngine;

class LoopComponent implements ComponentInterface
{
    private Template $template;
    private array $components = [];

    public function __construct(
        private string $content,
        private string $iterableVariable,
        private string $instanceVariable
    ) { 
        $this->template = new Template($content);
    }

    public function parse(array $data = []): self
    {
        foreach($data[$this->iterableVariable] as $var) {
            // Cheap deep-clone of an object
            $t = unserialize(serialize($this->template));
            $t->parse(array_merge($data, [$this->instanceVariable => $var]));
            $this->components[] = $t;
        }
        return $this;
    }

    public function render(): string
    {
        return array_reduce(
            $this->components,
            fn($carry,$comp) => $carry.$comp->render(),
            ''
        );
    }
}