<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->exclude('var')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,         
        '@PSR12' => true,           
        'array_syntax' => ['syntax' => 'short'], 
        'declare_strict_types' => true,
        'ordered_imports' => true,   
        'no_unused_imports' => true, 
    ])
    ->setFinder($finder);