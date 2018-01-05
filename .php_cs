<?php
$header = <<<EOF
This file is part of PHP CS Fixer.
(c) Fabien Potencier <fabien@symfony.com>
    Dariusz Rumiński <dariusz.ruminski@gmail.com>
This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;
return PhpCsFixer\Config::create()
    // ->setRiskyAllowed(true)
    // ->setRules(array(
    //     '@Symfony'                              => true,
    //     '@Symfony:risky'                        => true,
    //     'array_syntax'                          => array('syntax' => 'long'),
    //     'combine_consecutive_unsets'            => true,
    //     // one should use PHPUnit methods to set up expected exception instead of annotations
    //     'general_phpdoc_annotation_remove'      => array('expectedException', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp'),
    //     'header_comment'                        => array('header' => $header),
    //     'heredoc_to_nowdoc'                     => true,
    //     'no_extra_consecutive_blank_lines'      => array('break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block'),
    //     'no_unreachable_default_argument_value' => true,
    //     'no_useless_else'                       => true,
    //     'no_useless_return'                     => true,
    //     'ordered_class_elements'                => true,
    //     'ordered_imports'                       => true,
    //     'php_unit_strict'                       => true,
    //     'phpdoc_add_missing_param_annotation'   => true,
    //     'phpdoc_order'                          => true,
    //     'psr4'                                  => true,
    //     'strict_comparison'                     => true,
    //     'strict_param'                          => true,
    // ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in('src')
            ->in('test')
    )
;
?>
