<?php

use PHPUnit\Framework\TestCase;
use SourcePot\TemplateEngine\VariableComponent;

class VariableComponentTest extends TestCase
{
    public function testVariableReplacementWithSimpleVariable(): void
    {
        $value = 'hello';
        $comp = new VariableComponent('test');
        $var = ['test' => $value];

        $output = $comp->parse($var)->render();

        $this->assertEquals($value, $output);
    }

    public function testVariableReplacementWithArrayVariable(): void
    {
        $value = 'hello';
        $comp = new VariableComponent('test.deep.array');
        $var = [
            'test' => [
                'deep' => [
                    'array' => $value
                ]
            ]
        ];

        $output = $comp->parse($var)->render();

        $this->assertEquals($value, $output);
    }
}