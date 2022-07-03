<?php

namespace SourcePot\TemplateEngine;

use SourcePot\TemplateEngine\Exception\FileNotFoundException;
use SourcePot\TemplateEngine\Exception\TemplateParseException;
use SourcePot\TemplateEngine\Exception\UnrecognisedDirectiveFoundException;

class Template implements ComponentInterface
{
    private string $templateContents;
    private array $components = [];

    public function __construct(
        string $templateContents
    ) {
        $this->templateContents = $templateContents;
        // check the incoming template contents and build a list of components
        while(strlen($templateContents) > 0)
        {
            $tokenStartPos = strpos($templateContents, TemplateEngine::TOKEN_START);

            if($tokenStartPos === false) {
                // If we didn't find a token start, assume all that is left is plain text
                $template = new TextComponent($templateContents);
                $this->components[] = $template;
                break;
            }

            if($tokenStartPos > 0) {
                // If we found a token start not at the beginning of the string, everything before
                // the token start is a text component
                $template = new TextComponent(substr($templateContents, 0, $tokenStartPos));
                $this->components[] = $template;
                $templateContents = substr($templateContents, $tokenStartPos);
                continue;
            }

            $tokenEndPos = strpos($templateContents, TemplateEngine::TOKEN_END, $tokenStartPos);

            if($tokenEndPos === false) {
                // If we didn't find the end of a token, this is a terminal error
                throw new Exception\TemplateParseException('No end of token found');
            }

            $token = substr(
                $templateContents,
                $tokenStartPos + strlen(TemplateEngine::TOKEN_START),
                $tokenEndPos - $tokenStartPos - strlen(TemplateEngine::TOKEN_START)
            );

            $nextBlockStartPos = $tokenEndPos + strlen(TemplateEngine::TOKEN_END);

            if($token[0] !== '@') {
                // Simple variable replacement is just {{variable}}
                $template = new VariableComponent($token);
                $this->components[] = $template;
                $templateContents = substr($templateContents, $nextBlockStartPos);
                continue;
            }

            [$token,$params] = explode(':',$token,2);

            switch($token) {
                case '@include':
                    $template = new IncludeComponent($params);
                    $this->components[] = $template;
                    $templateContents = substr($templateContents, $nextBlockStartPos);
                    continue 2; // while loop
                
                case '@foreach':
                    // This is a "block component" which means it has another corresponding token
                    // somewhere later in the file.  We need to find that first
                    [$iterableVariable, $instanceVariable] = explode(':',$params,2);
                    $closingToken = TemplateEngine::TOKEN_START.'@endforeach:'.$iterableVariable.TemplateEngine::TOKEN_END;
                    $closingTokenStart = strpos($templateContents, $closingToken, $nextBlockStartPos);

                    if($closingTokenStart === false) {
                        // Terminal error if we can't find the end
                        throw new TemplateParseException('No closing token found for '.$token);
                    }

                    $blockContents = substr($templateContents, $nextBlockStartPos, $closingTokenStart-$nextBlockStartPos);

                    $template = new LoopComponent($blockContents, $iterableVariable, $instanceVariable);
                    $this->components[] = $template;
                    $templateContents = substr($templateContents, $nextBlockStartPos + strlen($blockContents) + strlen($closingToken));
                    continue 2; // while loop

                case '@if':
                    $closingToken = TemplateEngine::TOKEN_START.'@endif:'.$params.TemplateEngine::TOKEN_END;
                    $closingTokenStart = strpos($templateContents, $closingToken, $nextBlockStartPos);

                    if($closingTokenStart === false) {
                        throw new TemplateParseException('No closing token found for '.$token);
                    }

                    $blockContents = substr($templateContents, $nextBlockStartPos, $closingTokenStart-$nextBlockStartPos);

                    $template = new IfComponent($blockContents, $params);
                    $this->components[] = $template;
                    $templateContents = substr($templateContents, $nextBlockStartPos + strlen($blockContents) + strlen($closingToken));
                    continue 2;

                default:
                    // Treat unrecognised tokens as plain text
                    $template = new TextComponent($token);
                    $this->components[] = $template;
                    $templateContents = substr($templateContents, $nextBlockStartPos);
                    continue 2; // while loop
            }

            // failsafe if nothing above was caught, we break here to stop an infinite loop
            break;
        }
    }

    public function parse(array $data = []): self {
        foreach($this->components as $component) {
            $component->parse($data);
        }
        return $this;
    }

    public function render(): string {
        return array_reduce(
            $this->components,
            fn($carry, $component) => $carry . $component->render(),
            ''
        );
    }
}
