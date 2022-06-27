<?php

spl_autoload_register(function(string $className): void {
    $vendorName = substr($className, 0, strpos($className, '\\'));
    if($vendorName !== 'SourcePot') return;
    $fileName = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
    if(file_exists($fileName)) include $fileName;
});