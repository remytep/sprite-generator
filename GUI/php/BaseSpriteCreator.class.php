<?php
class BaseSpriteCreator {
    public $outputWidth = 0;
    public $outputHeight = 0;
    public function getOutputWidth()
    {
        return $this->outputWidth;
    }
    public function getOutputHeight()
    {
        return $this->outputHeight;
    }
    public function OutputFormat()
    {
        if ($this->columns_number > 0 && $this->columns_number < count($this->imagesList)) {
            $currentRowMaxHeight = 0;
            $currentRowWidth = 0;
            foreach ($this->imagesList as $key => $image) {
                if ($key % $this->columns_number == 0) {
                    $currentRowMaxHeight = 0;
                    $currentRowWidth = 0;
                }
                $currentRowWidth += $image['width'];
                if ($image['height'] > $currentRowMaxHeight) {
                    $this->outputHeight -= $currentRowMaxHeight;
                    $currentRowMaxHeight = $image['height'];
                    $this->outputHeight += $currentRowMaxHeight;
                }
                if ($currentRowWidth > $this->outputWidth) {
                    $this->outputWidth = $currentRowWidth;
                }
            }
            $this->outputWidth += $this->padding * ($this->columns_number - 1);
            $this->outputHeight += $this->padding * (ceil(count($this->imagesList) / $this->columns_number) - 1);
        } else {
            foreach ($this->imagesList as $image) {
                $this->outputWidth += $image['width'] + $this->padding;
                if ($image['height'] > $this->outputHeight) {
                    $this->outputHeight = $image['height'];
                }
            }
            $this->outputWidth -= $this->padding;
        }
    }
    public function OutputFormatResized($override_size)
    {
        if ($this->columns_number > 0) {
            $this->outputHeight = $override_size;
            $currentRowWidth = 0;
            foreach ($this->imagesList as $key => $image) {
                if ($key !== 0 && $key % $this->columns_number == 0) {
                    $this->outputHeight += $override_size;
                    $currentRowWidth = 0;
                }
                $currentRowWidth += $override_size;
                if ($currentRowWidth > $this->outputWidth) {
                    $this->outputWidth = $currentRowWidth;
                }
            }
            $this->outputWidth += $this->padding * ($this->columns_number - 1);
            $this->outputHeight += $this->padding * (ceil(count($this->imagesList) / $this->columns_number) - 1);
        } else {
            foreach ($this->imagesList as $image) {
                $this->outputWidth += $override_size + $this->padding;
            }
            $this->outputHeight = $override_size;
            $this->outputWidth -= $this->padding;
        }
    }
    public function createBaseSprite($imagesArray, $optionsArray)
    {
        $this->imagesList = $imagesArray;
        $this->columns_number = $optionsArray['columns_number'];
        $this->padding = $optionsArray['padding'];
        if ($optionsArray['override_size'] == 0) {
            $this->OutputFormat();
        } else {
            $this->OutputFormatResized($optionsArray['override_size']);
        }
        $outputImage = imagecreatetruecolor($this->outputWidth, $this->outputHeight);
        $transparencyColor = imagecolorallocatealpha($outputImage, 0, 0, 0, 127);
        imagefill($outputImage, 0, 0, $transparencyColor);
        imagealphablending($outputImage, false);
        imagesavealpha($outputImage, true);
        return $outputImage;
    }
}