<?php

include(__DIR__ . '/ImagesLister.class.php');
include(__DIR__ . '/OptionsLister.class.php');
include(__DIR__ . '/BaseSpriteCreator.class.php');
include(__DIR__ . '/Spritesheet.class.php');
include(__DIR__ . '/HTMLcreator.class.php');
include(__DIR__ . '/SpriteGenerator.class.php');

class CSS_Generator {
    public function __construct($optionsArray,$fileArray)
    {
        $this->optionsLister = new OptionsLister;
        $this->imagesLister = new ImagesLister;
        $this->baseSprite = new BaseSpriteCreator;
        $this->spritesheet = new Spritesheet;
        $this->html = new HTMLcreator;
        $this->spriteGen = new SpriteGenerator($this->html, $this->spritesheet,$this->baseSprite);
        $this->optionsArray = $optionsArray;
        $this->fileList = $fileArray;
        $this->imagesArray = $this->getImagesArray();
    }
    public function getOptionsArray(){
        return $this->optionsList;
    }
    public function getFilesArray(){
        return $this->fileList;
    }
    public function getImagesArray(){
        return $this->imagesLister->getImageList($this->fileList, $this->optionsArray);
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

