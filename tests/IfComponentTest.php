<?php

use PHPUnit\Framework\TestCase;
use SourcePot\TemplateEngine\IfComponent;

class IfComponentTest extends TestCase
{
    public function testCorrectOutputWithTruthyValue(): void
    {
        $expectedOutput = 'test';
        $comp = new IfComponent('test', 'test');
        $var = ['test' => true];

        $output = $comp->parse($var)->render();

        $this->assertEquals($expectedOutput, $output);
    }

    public function testCorrectOutputWithFalseyValue(): void
    {
        $expectedOutput = 'test';
        $comp = new IfComponent('test', 'test');
        $var = ['test' => false];

        $output = $comp->parse($var)->render();

        $this->assertEquals('', $output);
    }
}