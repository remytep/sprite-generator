<?php

include(__DIR__ . '/php/ImagesLister.class.php');
include(__DIR__ . '/php/OptionsLister.class.php');
include(__DIR__ . '/php/BaseSpriteCreator.class.php');
include(__DIR__ . '/php/Spritesheet.class.php');
include(__DIR__ . '/php/HTMLcreator.class.php');
include(__DIR__ . '/php/SpriteGenerator.class.php');

class CSS_Generator {
    public function __construct()
    {
        $this->optionsLister = new OptionsLister;
        $this->imagesLister = new ImagesLister;
        $this->baseSprite = new BaseSpriteCreator;
        $this->spritesheet = new Spritesheet;
        $this->html = new HTMLcreator;
        $this->spriteGen = new SpriteGenerator($this->html, $this->spritesheet,$this->baseSprite);
        $this->optionsArray = $this->getOptionsArray();
        $this->imagesArray = $this->getImagesArray();
    }
    public function getOptionsArray(){
        return $this->optionsLister
            ->getOptions();
    }
    public function getFilesArray(){
        $optionsList = $this->optionsLister
            ->fetchInputOptions();
        return $this->imagesLister
            ->fetchInputFileList($optionsList);
    }
    public function getImagesArray(){
        $fileList = $this->getFilesArray();
        return $this->imagesLister->getImageList($fileList, $this->optionsArray);
    }
    public function createBaseImage(){
        return $this->baseSprite->createBaseSprite($this->imagesArray, $this->optionsArray);
    }
    public function createSpriteSheet(){
        return $this->spritesheet->createCSSsheet($this->optionsArray['output_style'], $this->optionsArray['output_image']);
    }
    public function createHTML(){
        return $this->html->createHTMLfile($this->optionsArray['output_style']);
    }
    public function imageConcat(){
        $output = $this->createBaseImage();
        return $this->spriteGen->imageFusion($this->imagesArray, $this->optionsArray, $output);
    }
};

$gen = new CSS_Generator;
$gen->createSpriteSheet();
$gen->createHTML();
$gen->imageConcat();