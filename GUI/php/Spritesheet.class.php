<?php
class Spritesheet
{
    public function createCSSsheet($output_style, $output_image)
    {
        fopen($output_style, 'w');
        file_put_contents(
            $output_style,
            '.sprite {
background-image : url(' . $output_image . ');
background-repeat: no-repeat;
display:block;
}'
        );
    }
    public function writeInCSSsheet($imagePath, $width, $height, $outputPosX, $outputPosY)
    {
        $styleInput = PHP_EOL .
            '#' . $imagePath .
            "{
width : " . $width .
            "px;
height: " . $height .
            "px;
background-position: -" . $outputPosX .
            "px -" . $outputPosY . "px;
}";
        return $styleInput;
        
    }
}
