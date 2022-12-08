<?php

declare(strict_types=1);
include('./php/css_generator.php');

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\MenuItem\SelectableItem;

require_once(__DIR__ . '/vendor/autoload.php');

$fileList = array();
$optionList = array('recursivity' => false, 'output_image' => 'sprite.png', 'output_style' => 'style.css', 'padding' => 0, 'override_size' => 0, 'columns_number' => 0);

$exit = function (CliMenu $menu) {
    $menu->close();
};


$generateSprite = function (CliMenu $menu) {
    global $fileList, $optionList;
    $flash = $menu->flash("Sprite Generated!!");
    $flash
        ->getStyle()
        ->setBg('white')
        ->setFg('blue');
    try {
        $gen = new CSS_Generator($optionList, $fileList);
        $gen->createSpriteSheet();
        $gen->createHTML();
        $gen->imageConcat();
    } catch (Error) {
        $flash = $menu->flash("Please enter at least one valid file path");
        $flash
            ->getStyle()
            ->setBg('white')
            ->setFg('blue');
    }
    $flash->display();
};

$removeItem = function (CliMenu $menu) {
    global $fileList;
    $selectedItem = $menu->getSelectedItem();
    $menu->removeItem($selectedItem);
    unset($fileList[$selectedItem->getText()]);
    $menu->redraw();
};

$addFile = function (CliMenu $menu) {
    global $removeItem, $fileList;
    $text = $menu->askText();
    $text->getStyle()
        ->setBg('white')
        ->setFg('blue');
    $result = $text
        ->setPromptText('Enter file name for output image')
        ->setPlaceholderText('example')
        ->setValidationFailedText('Please enter a file name for output image')
        ->ask();
    $menu->addItem(new SelectableItem($result->fetch(), $removeItem, true));
    $menu->redraw();
    $fileList[$result->fetch()] = $result->fetch();
};

$outputImage = function (CliMenu $menu) {
    global $optionList;
    $text = $menu->askText();
    $text->getStyle()
        ->setBg('white')
        ->setFg('blue');
    $result = $text
        ->setPromptText('Enter file name for output stylesheet')
        ->setPlaceholderText('sprite')
        ->setValidationFailedText('Enter file name for output stylesheet')
        ->ask();
    if (preg_match('/([^\s]+(\.(?i)(png))$)/', $result->fetch())) {
        $optionList['output_image'] = $result->fetch();
    } else {
        $optionList['output_image'] = $result->fetch() . ".png";
    }
};

$outputStyle = function (CliMenu $menu) {
    global $optionList;
    $text = $menu->askText();
    $text->getStyle()
        ->setBg('white')
        ->setFg('blue');
    $result = $text
        ->setPromptText('Enter folder name')
        ->setPlaceholderText('style')
        ->setValidationFailedText('Please enter a folder name')
        ->ask();
    if (preg_match('/([^\s]+(\.(?i)(css))$)/', $result->fetch())) {
        $optionList['output_style'] = $result->fetch();
    } else {
        $optionList['output_style'] = $result->fetch() . ".css";
    }
};

$paddingInput = function (CliMenu $menu) {
    global $optionList;
    $number = $menu->askNumber();
    $number->getStyle()
        ->setBg('white')
        ->setFg('blue');
    $result = $number
        ->setPromptText('Enter desired padding in pixels')
        ->setPlaceholderText('')
        ->setValidationFailedText('Invalid number, try again')
        ->ask();
    $optionList['padding'] = $result->fetch();
};

$overrideInput = function (CliMenu $menu) {
    global $optionList;
    $number = $menu->askNumber();
    $number->getStyle()
        ->setBg('white')
        ->setFg('blue');
    $result = $number
        ->setPromptText('Enter desired size in pixels (force each picture in sprite to fit SIZExSIZE pixels)')
        ->setPlaceholderText('')
        ->setValidationFailedText('Invalid number, try again')
        ->ask();
    $optionList['override_size'] = $result->fetch();
};

$columnsInput = function (CliMenu $menu) {
    global $optionList;
    $number = $menu->askNumber();
    $number->getStyle()
        ->setBg('white')
        ->setFg('blue');
    $result = $number
        ->setPromptText('Enter desired number of columns')
        ->setPlaceholderText('')
        ->setValidationFailedText('Invalid number, try again')
        ->ask();
    $optionList['columns_number'] = $result->fetch();
};

$menu = (new CliMenuBuilder)
    ->enableAutoShortcuts()
    ->setTitle('CSS GENERATOR ---- press [x] to exit')
    ->setTitleSeparator('=')
    ->addItem('[G]ENERATE YOUR SPRITE !!!', $generateSprite)
    ->addLineBreak('-')
    ->addStaticItem('PARAMETERS')
    ->addLineBreak('-')
    ->addSplitItem(function (SplitItemBuilder $b) {
        global $outputImage, $outputStyle;
        $b->addCheckboxItem('[r]ecursive', function () {
            global $optionList;
            $optionList['recursivity'] = true;
        })
            ->addItem('[i]mage name', $outputImage)
            ->addItem('[s]pritesheet name', $outputStyle);
    })
    ->addLineBreak()
    ->addLineBreak()
    ->addStaticItem('OPTIONS')
    ->addLineBreak('-')
    ->addSplitItem(function (SplitItemBuilder $b) {
        global $paddingInput, $overrideInput, $columnsInput;
        $b->addItem('[p]adding', $paddingInput)
            ->addItem('[o]verride size', $overrideInput)
            ->addItem('[c]olumns number', $columnsInput);
    })
    ->addLineBreak()
    ->addLineBreak()
    ->addStaticItem('TARGET FOLDERS')
    ->addLineBreak('-')
    ->addItem('Enter target [f]older or [f]ile path -> HERE <-', $addFile)
    ->addLineBreak('-')
    ->addStaticItem('LIST OF FILES & FOLDERS:')
    ->addLineBreak('- ')
    ->setBorder(1, 2, 'white')
    ->setPadding(2, 4)
    ->setWidth(100)
    ->setMarginAuto()
    ->disableDefaultItems()
    ->build();

$menu->addCustomControlMapping("x", $exit);
$menu->open();
