<?php

include __DIR__ . '/autoloader.php';

use SourcePot\TemplateEngine\Template;
use SourcePot\TemplateEngine\TemplateEngine;

try {

    TemplateEngine::setBaseDirectory(__DIR__.'/template/');

    // test just plain text
    // $t = Template::loadFromFile('footer.tpl');
    // echo $t->render();
    // echo "\n".str_repeat('-',40)."\n";
    
    // test simple variable replacement
    // $t = Template::loadFromFile('header.tpl');
    // $t->parse([
    //     'pageTitle' => 'Dummy title'
    // ]);
    // echo $t->render();
    // echo "\n".str_repeat('-',40)."\n";

    // test a loop
    // $t = Template::loadFromFile('section.tpl');
    // $t->parse([
    //     'sections' => [
    //         [
    //             'title' => 'First section title',
    //         ],
    //         [
    //             'title' => 'part 2',
    //         ]
    //     ],
    //     'section' => [
    //         'title' => 'dummy'
    //     ],
    //     'dummyText' => 'Lorum Epson Dell Lenovo',
    // ]);
    // echo $t->render();
    // echo "\n".str_repeat('-',40)."\n";

    // more complicated template with include, loop, and variables
    $t = Template::loadFromFile('demo.tpl');
    $t->parse([
        'pageTitle' => 'Welcome',
        'dummyText' => 'Lorum epson dell lenovo',
        'sections' => [
            [
                'title' => 'part 1',
            ],
            [
                'title' => 'part 2',
            ],
        ],
        'section' => [
            'title' => 'dummy',
        ],
    ]);

    echo $t->render();

} catch(\Throwable $t) {
    echo $t->getMessage()."\n";
}
