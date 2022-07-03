<?php

use PHPUnit\Framework\TestCase;
use SourcePot\TemplateEngine\TextComponent;

class TextComponentTest extends TestCase
{
    public function testOutputEqualsInput(): void
    {
        $input = 'hello, world';
        $comp = new TextComponent($input);

        $output = $comp->parse()->render();

        $this->assertEquals($input, $output);
    }
}