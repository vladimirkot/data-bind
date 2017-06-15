<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('storage')
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

$rules = [
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => false,
    'blank_line_before_return' => true,
    'braces' => [
        'allow_single_line_closure' => true,
        'position_after_functions_and_oop_constructs' => 'same'
        ],
    'cast_spaces' => true,
    'class_definition' => [
        'multiLineExtendsEachSingleLine' => false,
        'singleItemSingleLine' => false
    ],
    'class_keyword_remove' => false,
    'concat_space' => ['spacing' => 'none'],
    'declare_equal_normalize' => ['space' => 'none'],
    'elseif' => true,
    'encoding' => true,
    'full_opening_tag' => true,
    'function_declaration' => [
        'closure_function_spacing' => 'one'
    ],
    'function_typehint_space' => true,
    'hash_to_slash_comment' => true,
    'header_comment' => [
        'commentType' => 'comment',
        'header' => 'MIT License

Copyright (c) 2017 Eugene Bogachov

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.', // @TODO CHANGE IT TO MIT STRING
        'location' => 'after_declare_strict',
        'separate' => 'bottom'
    ],
    'include' => true,
    'indentation_type' => true,
    'line_ending' => true,
    'lowercase_cast' => true,
    'lowercase_constants' => true,
    'lowercase_keywords' => true,

];

return PhpCsFixer\Config::create()
    ->setRules($rules)
    ->setFinder($finder)
    ->setUsingCache(false)
;