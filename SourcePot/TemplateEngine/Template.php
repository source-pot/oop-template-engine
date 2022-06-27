<?php

namespace SourcePot\TemplateEngine;

use SourcePot\TemplateEngine\Exception\FileNotFoundException;
use SourcePot\TemplateEngine\Exception\TemplateParseException;
use SourcePot\TemplateEngine\Exception\UnrecognisedDirectiveFoundException;

class Template implements TemplateSnippetInterface
{
    private const DIRECTIVE_START = '{{';
    private const DIRECTIVE_END = '}}';

    private string $templateContents;
    private array $compiledComponents = [];

    public function __construct(
        private string $templateFileName
    ) { 
        if(!file_exists($templateFileName)) {
            throw new FileNotFoundException($templateFileName);
        }
        $this->templateContents = file_get_contents($templateFileName);
    }

    public function parse(array $data = []): void {
        $template = $this->templateContents;

        while(true) {
            $nextToken = strpos($template, self::DIRECTIVE_START);

            // Anything from the start of the template string to the first
            // occurence of a directive opening is plain text
            $text = substr($template,0,$nextToken);
            if(strlen($text) > 0) {
                $textContent = new TextContent($text);
                $this->compiledComponents[] = $textContent;
            }

            // If we didn't find a directive opening, we have parsed the template
            if($nextToken === false) {
                break;
            }

            // Now we know we have a directive opening, we can remove any text
            // before it then start to parse the directive itself
            $template = substr($template, $nextToken+2);

            $tokenEnd = strpos($template, self::DIRECTIVE_END);

            // If we didn't find a directive close, that's an error
            if($tokenEnd === false) {
                throw new TemplateParseException(
                    'No directive closer found, expecting '.self::DIRECTIVE_END
                );
            }

            // Capture contents of the directive
            $directive = substr($template, 0, $tokenEnd);

            // handle directive we just found
            if($directive[0] !== '@') {
                // This is a simple variable replace
                if(strpos($directive, '.') !== false) {
                    // If it contains a dot, it's an array
                    $comp = new VariableArrayContent($directive);
                    $comp->parse($data);
                    $this->compiledComponents[] = $comp;
                }
            } else {
                // These are directives that need further processing
                echo $directive."\n";

                [$directive,$params] = explode(':', $directive, 2);
                switch($directive) {
                    case '@include':
                        // Include a file
                        $template = new Template($params);
                        $template->parse($data);
                        $this->compiledComponents[] = $template;
                        break;

                    default:
                        throw new UnrecognisedDirectiveFoundException($directive);
                }
            }

            // Now we've captured the token, remove it from the remaining text
            // waiting to be parsed
            $template = substr($template, $tokenEnd+2);
        }
    }

    public function render(): string {
        // return '';
        return print_r($this->compiledComponents, true);
        return array_reduce(
            $this->compiledComponents,
            fn($carry, $component) => $carry . $component->render(),
            ''
        );
    }
}
