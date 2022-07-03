<?php

use PHPUnit\Framework\TestCase;
use SourcePot\TemplateEngine\TemplateEngine;

class TemplateEngineTest extends TestCase
{
    public function testSettingBaseDirectoryAppendsSlash(): void
    {
        $dir = '/var/www';
        TemplateEngine::setBaseDirectory($dir);
        $this->assertEquals($dir.'/', TemplateEngine::baseDirectory());

        $dir = '/var/www/';
        TemplateEngine::setBaseDirectory($dir);
        $this->assertEquals($dir, TemplateEngine::baseDirectory());
    }

    public function testCorrectOutputWhenLoadingFromFile(): void
    {
        $input = 'hello, world';
        $filename = 'test-template-engine.tpl';
        file_put_contents($filename,$input);

        $template = TemplateEngine::loadFromFile($filename);

        $output = $template->parse()->render();

        $this->assertEquals($input, $output);

        unlink($filename);
    }
}
