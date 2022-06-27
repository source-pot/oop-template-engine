<?php

include __DIR__ . '/autoloader.php';

use SourcePot\TemplateEngine\Template;

try {

    $t = new Template(__DIR__.'/template/demo.tpl');
    $t->parse([
        'pageTitle' => 'Welcome',
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