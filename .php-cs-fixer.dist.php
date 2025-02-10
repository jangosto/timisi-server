<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        'single_line_after_imports' => false,
        'no_superfluous_phpdoc_tags' => false,
        'single_line_throw' => false,
        'native_function_invocation' => true,
        'declare_strict_types' => true,
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setFinder($finder)
;
