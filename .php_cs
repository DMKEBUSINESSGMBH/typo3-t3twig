<?php
$finder = \PhpCsFixer\Finder::create()
    ->in(__DIR__.'/Classes');
return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        'phpdoc_align' => false,
        'no_superfluous_phpdoc_tags' => false,
    ])
    ->setLineEnding("\n")
    ;
