<?php

class OptionsLister {
    public $optionsArray = array();
    public function __construct()
    {
        $this->optionsArray = ['recursivity' => false, 'output_image' => 'sprite.png', 'output_style' => 'style.css', 'padding' => 0, 'override_size' => 0, 'columns_number' =>0];
    }
    public function fetchInputOptions()
    {
        $shortOpts = "r::i:s:p:o:c:";
        $longOpts = array("recursive", "output-image:", "output-style:", "padding:", "override-size:", "columns-number:");
        $options = getopt($shortOpts, $longOpts);
        return $options;
    }
    public function getOptions(){
        // Récupère les options entrées par l'utilisateur
        $optionsInputList = $this->fetchInputOptions();
        foreach ($optionsInputList as $option => $value) {
            switch (true) {
                case ($option === 'r' || $option === 'recursive'):
                    $this->optionsArray['recursivity'] = true;
                    break;
                case ($option === 'i' || $option === 'output-image'):
                    if (preg_match('/([^\s]+(\.(?i)(png))$)/', $value)) {
                        $this->optionsArray['output_image'] = $value;
                    } else {
                        $this->optionsArray['output_image'] = $value . ".png";
                    }
                    break;
                case ($option === 's' || $option === 'output-style'):
                    if (preg_match('/([^\s]+(\.(?i)(css))$)/', $value)) {
                        $this->optionsArray['output_style'] = $value;
                    } else {
                        $this->optionsArray['output_style'] = $value . ".css";
                    }
                    break;
                case ($option === 'p' || $option === 'padding'):
                    $this->optionsArray['padding'] = $value;
                    break;
                case ($option === 'o' || $option === 'override-size'):
                    $this->optionsArray['override_size'] = $value;
                    break;
                case ($option === 'c' || $option === 'columns_number'):
                    $this->optionsArray['columns_number'] = $value;
                    break;
                default:
                    exit;
            }
        }
        return $this->optionsArray;
    }
}