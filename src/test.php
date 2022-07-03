<?php

include __DIR__ . '/vendor/autoloader.php';

use SourcePot\TemplateEngine\Template;
use SourcePot\TemplateEngine\TemplateEngine;

try {

    TemplateEngine::setBaseDirectory(__DIR__.'/template/');

    $t = TemplateEngine::loadFromFile('demo.tpl');
    $t->parse([
        'pageTitle' => 'Welcome',
        'loggedIn' => true,
        'username' => 'Rob',
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
