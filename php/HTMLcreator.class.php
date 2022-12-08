<?php
class HTMLcreator
{
    public function createHTMLfile($output_style)
    {
        // Créer un index.html permettant de visionner les images sur un site.
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
        // Ecrit dans le fichier HTML index.html
        $htmlInput = PHP_EOL . '<div class="sprite" id="' . $imagePath . '"></div>';
        return $htmlInput;
    }
    public function closeHTMLfile(){
        // Clos le fichier HTML index.html
        file_put_contents('index.html', '</body>' . PHP_EOL . '</html>', FILE_APPEND);
    }
}