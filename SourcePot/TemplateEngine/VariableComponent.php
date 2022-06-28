<?php

namespace SourcePot\TemplateEngine;

use InvalidArgumentException;

class VariableComponent implements ComponentInterface
{
    private array $keys;

    public function __construct(
        private string $content
    ) { 
        $this->keys = explode('.', $content);
    }

    public function parse(array $data = []): self
    {
        // Make sure this function is repeatable
        $keys = $this->keys;

        // The final key entry is the key of the value we're searching for
        $valueKey = array_pop($keys);

        foreach($keys as $key) {
            if(array_key_exists($key, $data)) {
                $data = $data[$key];
                continue;
            }
            throw new InvalidArgumentException(
                "Key $key not found in data array: " . implode(',',array_keys($data))
            );
        }

        if(!array_key_exists($valueKey, $data) || is_array($data[$valueKey])) {
            throw new InvalidArgumentException(
                "Invalid data found for key $valueKey"
            );
        }

        $this->content = $data[$valueKey];

        return $this;
    }

    public function render(): string
    {
        return $this->content;
    }
}
