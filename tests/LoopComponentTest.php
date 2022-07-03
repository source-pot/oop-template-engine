<?php

use PHPUnit\Framework\TestCase;
use SourcePot\TemplateEngine\LoopComponent;

class LoopComponentTest extends TestCase
{
    public function testIterationWithNoVariables(): void
    {
        $input = 'hello';
        $comp = new LoopComponent($input, 'test', 'var');

        $var = [
            'test' => [
                1,2,3
            ]
        ];

        $output = $comp->parse($var)->render();

        $this->assertEquals(str_repeat($input,3), $output);
    }

    public function testIterationWithSimpleVariableReplacement(): void
    {
        $input = '{{test.var}}';
        $comp = new LoopComponent($input, 'test', 'test');

        $var = [
            'test' => [
                ['var' => 1],
                ['var' => 2],
                ['var' => 3],
            ]
        ];

        $output = $comp->parse($var)->render();

        $this->assertEquals('123', $output);
    }

    public function testIterationWithGlobalVariable(): void
    {
        $input = '{{global}}';
        $comp = new LoopComponent($input, 'test', 'test');

        $var = [
            'global' => 'hello',
            'test' => [
                ['var' => 1],
                ['var' => 2],
                ['var' => 3],
            ]
        ];

        $output = $comp->parse($var)->render();

        $this->assertEquals(str_repeat('hello',3), $output);
    }
}
