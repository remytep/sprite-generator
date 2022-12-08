<?php
class ImagesLister
{
    public $imageArray = array();
    public function fetchInputFileList($options)
    {
        // Récupération de la liste des fichiers et dossiers sans les options
        global $argv;
        $fileList = array();
        foreach ($argv as $key1 => $value1) {
            if ($key1 == 0) {
                continue;
            }
            $match = false;
            foreach ($options as $key2 => $value2) {
                if ("-r" === $value1 || "-" . $key2 . "=" . $value2 === $value1 || "--recursive" === $value1 || "--" . $key2 . "=" . $value2 === $value1) {
                    $match = true;
                    break;
                }
            }
            if (!$match) {
                $fileList[]
                    = $value1;
            }
        }
        return $fileList;
    }
    public function getImageList($fileList, $optionsArray, $folderName = "")
    {
        // Récupère la liste des images, récursivement ou non
        foreach ($fileList as $path) {
            $currentPath = $folderName . $path;
            if ($path === "." || $path === "..") {
                continue;
            } else if (is_file($currentPath) && preg_match('/image\/(jpeg|png|bmp)/', mime_content_type($currentPath))) {
                list(0 => $width, 1 => $height, 2 => $type, 3 => $attribute, 'bits' => $bits, 'mime' => $mime) = getimagesize($currentPath);
                $tempImageArray = array('path' => $currentPath, 'width' => $width, 'height' => $height, 'type' => $type, 'attribute' => $attribute, 'bits' => $bits, 'mime' => $mime);
                $this->imageArray[] = $tempImageArray;
                sort($this->imageArray);
            } else if (is_dir($currentPath)) {
                if ($dh = opendir($currentPath)) {
                    if ($optionsArray['recursivity']) {
                        while (($file = readdir($dh)) !== false) {
                            $tempDir[] = $file;
                        }
                        $this->getImageList($tempDir, $optionsArray, $currentPath . "/");
                        sort($this->imageArray);
                    } else {
                        while (($file = readdir($dh)) !== false) {
                            $currentPath = $path . "/" . $file;
                            if (is_file($currentPath) && preg_match('/image\/(jpeg|png|bmp)/', mime_content_type($currentPath))) {
                                list(0 => $width, 1 => $height, 2 => $type, 3 => $attribute, 'bits' => $bits, 'mime' => $mime) = getimagesize($currentPath);
                                $tempImageArray = array('path' => $currentPath, 'width' => $width, 'height' => $height, 'type' => $type, 'attribute' => $attribute, 'bits' => $bits, 'mime' => $mime);
                                $this->imageArray[] = $tempImageArray;
                                sort($this->imageArray);
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $this->imageArray;
    }
}
