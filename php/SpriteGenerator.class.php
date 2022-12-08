<?php
class SpriteGenerator
{
    public function __construct($html, $spritesheet, $baseSprite)
    {
        $this->html = $html;
        $this->spritesheet = $spritesheet;
        $this->outputHeight = $baseSprite->getOutputHeight();
        $this->outputWidth = $baseSprite->getOutputWidth();
    }
    public function copyMode($optionsArray, $tmp, $outputPosX, $outputPosY, $width, $height, $output)
    {
        // Mode de imagecopy selon l'override
        if ($optionsArray['override_size'] == 0) {
            imagecopy($output, $tmp, $outputPosX, $outputPosY, 0, 0, $width, $height);
        } else {
            imagecopyresized($output, $tmp, $outputPosX, $outputPosY, 0, 0, $optionsArray['override_size'], $optionsArray['override_size'], $width, $height);
        }
    }
    public function imageFusion($imageArray, $optionsArray, $output)
    {
        // Concaténation des images avec tous les bonus
        $outputPosX = 0;
        $outputPosY = 0;
        $override_size = $optionsArray['override_size'];
        $currentLineMaxHeight = $override_size;
        foreach ($imageArray as $key => $image) {
            if ($optionsArray['override_size'] == 0) {
                $width = $image['width'];
                $height = $image['height'];
            } else {
                $width = $override_size;
                $height = $override_size;
            }
            $imagePath = str_replace('.', '-', str_replace('/', '-', $image['path']));
            $styleInput = $this->spritesheet->writeInCSSsheet($imagePath, $width, $height, $outputPosX, $outputPosY);
            $htmlInput = $this->html->writeInHTMLfile($imagePath);
            switch ($image['type']) {
                case 1:
                    $tmp = imagecreatefromgif($image['path']);
                    break;
                case 2:
                    $tmp = imagecreatefromjpeg($image['path']);
                    break;
                case 3:
                    $tmp = imagecreatefrompng($image['path']);
                    break;
            }
            file_put_contents($optionsArray['output_style'], $styleInput, FILE_APPEND);
            file_put_contents('index.html', $htmlInput, FILE_APPEND);
            if ($optionsArray['columns_number'] > 1) {
                if ($height > $currentLineMaxHeight) {
                    $currentLineMaxHeight = $height;
                }
                switch (true) {
                    case ($key % $optionsArray['columns_number'] == ($optionsArray['columns_number'] - 1)):
                        $this->copyMode($optionsArray, $tmp, $outputPosX, $outputPosY, $image['width'], $image['height'], $output);
                        $outputPosY += $currentLineMaxHeight + $optionsArray['padding'];
                        $currentLineMaxHeight = 0;
                        $outputPosX = 0;
                        break;
                    default:
                        $this->copyMode($optionsArray, $tmp, $outputPosX, $outputPosY, $image['width'], $image['height'], $output);
                        $outputPosX += $width + $optionsArray['padding'];
                        break;
                }
            } else if ($optionsArray['columns_number'] == 1) {
                $this->copyMode($optionsArray, $tmp, $outputPosX, $outputPosY, $image['width'], $image['height'],$output);
                $outputPosY += $height + $optionsArray['padding'];
            } else {
                $this->copyMode($optionsArray, $tmp, $outputPosX, $outputPosY, $image['width'], $image['height'],$output);
                $outputPosX += $width + $optionsArray['padding'];
            }
            imagedestroy($tmp);
        }
        $this->html->closeHTMLfile();
        imagepng($output, $optionsArray['output_image']);
    }
}