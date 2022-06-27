<?php

namespace SourcePot\TemplateEngine;

class VariableArrayContent implements TemplateSnippetInterface
{
    private array $keys;
    private string $content;

    public function __construct(
        string $content
    ) { 
        $this->keys = explode('.', $content);
    }

    public function parse(array $data = []): void
    {
        // Our content must be something like 'key.key.value'
        // We need to dive into the given $data to find the value

        // Make sure this function is repeatable
        $keys = $this->keys;

        // The final key entry is the key of the value we're searching for
        $valueKey = array_pop($keys);

        foreach($keys as $key) {
            if(array_key_exists($key, $data)) {
                $data = $data[$key];
                continue;
            }
            throw new \InvalidArgumentException(
                "Key $key not found in data array: " . implode(',',array_keys($data))
            );
        }

        if(!array_key_exists($valueKey, $data) || is_array($data[$valueKey])) {
            throw new \InvalidArgumentException(
                "Invalid data found for key $valueKey"
            );
        }

        $this->content = $data[$valueKey];
    }

    public function render(): string
    {
        return $this->content;
    }
}
