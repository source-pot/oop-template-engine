# oop-template-engine
Template Engine (think Smarty or Twig) written in PHP using OOP but on a much smaller scale.

## Tests

Assuming you have Docker installed, just run `docker compose up` to build the testing container and
run the test suite.

## Usage

```
use SourcePot\TemplateEngine\TemplateEngine;

TemplateEngine::setBaseDir( __DIR__.'/templates' );
$template = TemplateEngine::loadFromFile( 'main.tpl' );
$template->parse([
    'pageTitle' => 'Hello, world'
]);

echo $template->render();
```

### Notes
When `loadFromFile` is invoked, the template contents are loaded and processed to create a tree
of template components.

When you `parse` the template, the data you give is handed down to each component to interpolate
with its contents.

Finally, `render` visits each component in the template tree and returns the interpolated test

## Supported directives

### Simple variable replacement
Nice and easy, just like everybody else - `{{variable}}`.  Expects ['variable' => 'value'] to be
given during parsing.

This also works with dot-notation arrays like `{{some.deep.value}}` which will look for:
```
$data = [
    'some' => [
        'deep => [
            'value' => 'hello'
        ]
    ]
];
```
To output 'hello'.

### Including another file
Also simple - `{{@include:filename}}`.  Includes the file with the name `filename`.

### Looping
`{{@foreach:array:variable}}` will loop over `array`, creating `variable` in each iteration, e.g:
```
$data = [
    'array' => [1, 2, 3, 4, 5]
]
```
The value of `variable` will be `1`, `2`, `3`, `4`, `5` during each iteration.  Just use the simple
variable replacement of `{{variable}}` to output it.

To mark the end of the loop, use `{{@foreach:array}}`.  Same as the start, except without the
instance variable.