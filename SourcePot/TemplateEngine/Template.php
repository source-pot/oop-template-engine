<?php

namespace SourcePot\TemplateEngine;

use SourcePot\TemplateEngine\Exception\FileNotFoundException;
use SourcePot\TemplateEngine\Exception\TemplateParseException;
use SourcePot\TemplateEngine\Exception\UnrecognisedDirectiveFoundException;

class Template implements TemplateSnippetInterface
{
    private const DIRECTIVE_START = '{{';
    private const DIRECTIVE_END = '}}';

    protected string $baseDir = '';
    private array $compiledComponents = [];

    public function __construct(
        private string $templateContents
    ) { }

    public static function loadFromFile(string $fileName): self
    {
        if(!file_exists($fileName)) {
            throw new FileNotFoundException($fileName);
        }

        $template = new self(file_get_contents($fileName));
        $template->baseDir = substr($fileName, 0, strrpos($fileName,'/'));

        return $template;
    }

    public function parse(array $data = []): void {
        $templateString = $this->templateContents;

        while(true) {
            $tokenStartPos = strpos($templateString, self::DIRECTIVE_START);

            // If we didn't find a directive opening, the remainder of the template is just text
            if($tokenStartPos === false) {
                $text = $templateString;
            } else {
                // Anything from the start of the template string to the first
                // occurence of a directive opening is plain text
                $text = substr($templateString,0,$tokenStartPos);
            }

            $textContent = new TextContent($text);
            $this->compiledComponents[] = $textContent;

            // If we found no token, we have parsed the whole template
            if($tokenStartPos === false) {
                break;
            }

            // Now we know we have a directive opening, we can remove any text
            // before it then start to parse the directive itself
            $templateString = substr($templateString, $tokenStartPos+2);

            $tokenEndPos = strpos($templateString, self::DIRECTIVE_END);

            // If we didn't find a directive close, that's an error
            if($tokenEndPos === false) {
                throw new TemplateParseException(
                    'No directive closer found, expecting ' . self::DIRECTIVE_END
                );
            }

            // Capture contents of the directive
            $directive = substr($templateString, 0, $tokenEndPos);

            // Remove the directive from the text waiting to be parsed
            $templateString = substr($templateString, $tokenEndPos+2);

            // handle directive we just found
            if($directive[0] !== '@') {

                // This is a simple(ish) variable replace
                $comp = new VariableArrayContent($directive);
                $comp->parse($data);
                $this->compiledComponents[] = $comp;

            } else {

                // These are directives that need further processing
                [$directive,$params] = explode(':', $directive, 2);
                switch($directive) {
                    case '@include':
                        $this->includeFile($params, $data);
                        break;

                    case '@foreach':
                        [$arrayVariable, $iterationVariable] = explode(':', $params);

                        // Search for the end of the loop, marked with {{@foreach:variable_name}}
                        $loopEndText = '{{'.$directive.':'.$arrayVariable.'}}';

                        $tokenEndPos = strpos($templateString, $loopEndText);
                        if($tokenEndPos === false) {
                            throw new TemplateParseException(
                                "Invalid loop encountered, did not find $loopEndText in template"
                            );
                        }

                        // Verify that array actually exists
                        if(!isset($data[$arrayVariable]) || !is_iterable($data[$arrayVariable])) {
                            throw new TemplateParseException("Data variable $arrayVariable not found");
                        }

                        $contents = substr($templateString, 0, $tokenEndPos);
                        $tokenEndPos += strlen($loopEndText);
                        $templateString = substr($templateString, $tokenEndPos);
                        foreach($data[$arrayVariable] as $temp) {
                            $template = new Template($contents);
                            $template->parse([$iterationVariable => $temp]);
                            $this->compiledComponents[] = $template;
                        }
                        break;

                    default:
                        throw new UnrecognisedDirectiveFoundException($directive);
                }
            }
        }
    }

    private function includeFile(string $fileName, array $data): void
    {
        $template = Template::loadFromFile($this->baseDir.'/'.$fileName);
        $template->parse($data);
        $this->compiledComponents[] = $template;
    }

    public function render(): string {
        // return '';
        // return print_r($this->compiledComponents, true);
        return array_reduce(
            $this->compiledComponents,
            fn($carry, $component) => $carry . $component->render(),
            ''
        );
    }
}
