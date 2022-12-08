<?php
class HTMLcreator
{
    public function createHTMLfile($output_style)
    {
        fopen('index.html', 'w');
        file_put_contents(
            'index.html',
            '<!DOCTYPE html>
            <html lang="en">
            
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="' . $output_style . '">
                <title>Document</title>
            </head>
            <body>'
        );
    }
    public function writeInHTMLfile($imagePath)
    {
        $htmlInput = PHP_EOL . '<div class="sprite" id="' . $imagePath . '"></div>';
        return $htmlInput;
    }
    public function closeHTMLfile(){
        file_put_contents('index.html', '</body>' . PHP_EOL . '</html>', FILE_APPEND);
    }
}