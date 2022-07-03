<?php

use PHPUnit\Framework\TestCase;
use SourcePot\TemplateEngine\Template;

class TemplateTest extends TestCase
{
    public function testCorrectOutputFromTextOnlyTemplate(): void
    {
        $input = 'hello, world';
        $template = new Template($input);

        $output = $template->parse()->render();

        $this->assertEquals($input, $output);
    }

    public function testCorrectOutputFromVariableOnlyTemplate(): void
    {
        $input = '{{var}}';
        $template = new Template($input);

        $expectedOutput = 'hello, world';

        $data = ['var' => $expectedOutput];

        $output = $template->parse($data)->render();

        $this->assertEquals($expectedOutput, $output);
    }

    public function testCorrectOutputFromLoopOnlyTemplate(): void
    {
        $input = '{{@foreach:vars:var}}{{var}}{{@foreach:vars}}';
        $template = new Template($input);

        $expectedOutput = '123';

        $data = [
            'vars' => [
                1,2,3
            ]
        ];

        $output = $template->parse($data)->render();

        $this->assertEquals($expectedOutput, $output);
    }
}