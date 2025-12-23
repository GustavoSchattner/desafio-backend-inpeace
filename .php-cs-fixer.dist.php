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
        'single_quote' => true,
        'strict_comparison' => true,
        'declare_strict_types' => true,
        'ordered_imports' => true,
        'ordered_class_elements' => true,
        'no_unused_imports' => true,
        'no_trailing_whitespace_in_comment' => true,
        'phpdoc_annotation_without_dot' => true,
    ])
    ->setFinder($finder);