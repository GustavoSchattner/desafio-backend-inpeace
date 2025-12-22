<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_empty_phpdoc' => true,
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_trim' => true,
        'no_empty_comment' => true,
        
        'general_phpdoc_annotation_remove' => [
            'annotations' => ['author', 'created', 'version', 'package', 'copyright']
        ],
    ])
    ->setFinder($finder);