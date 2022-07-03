<?php

use PHPUnit\Framework\TestCase;
use SourcePot\TemplateEngine\IncludeComponent;
use SourcePot\TemplateEngine\Exception\FileNotFoundException;
use SourcePot\TemplateEngine\Exception\UnableToOpenFileException;

class IncludeComponentTest extends TestCase
{
    public function testThrowsCorrectExceptionWhenFileNotFound(): void
    {
        $filename = '/var/non-existent-file';

        $this->expectException(FileNotFoundException::class);

        $component = new IncludeComponent($filename);
    }

    public function testThrowsCorrectExceptionWhenCannotOpenFile(): void
    {
        $filename = '/root';
        
        $this->expectException(UnableToOpenFileException::class);

        $component = new IncludeComponent($filename);
    }

    public function testCorrectOutputFromTemplate(): void
    {
        $input = 'hello, world';
        $filename = 'test-include-component.tpl';
        file_put_contents($filename, $input);

        $template = new IncludeComponent($filename);

        $output = $template->parse()->render();

        $this->assertEquals($input, $output);

        unlink($filename);
    }
}