<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // Generate a new name for the image
        $file = md5(uniqid(rand(), true)) . '.webp';

        // Get image information
        $pictureInfo = getimagesize($picture);

        if ($pictureInfo === false) {
            throw new Exception('Taille d\'image incorrecte');
        }

        // Check the image format
        switch ($pictureInfo['mime']) {
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $pictureSource = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception('Format d\'image incorrecte');
        }

        // Crop the image
        // Get image dimensions
        $imageWidth = $pictureInfo[0];
        $imageHeight = $pictureInfo[1];

        // Check the orientation of the image
        switch ($imageWidth <=> $imageHeight) {
            case -1: // portrait
                $squareSize = $imageWidth;
                $srcX = 0;
                $srcY = ($imageHeight - $squareSize) / 2;
                break;
            case 0: // square
                $squareSize = $imageWidth;
                $srcX = 0;
                $srcY = 0;
                break;
            case 1: // landscape
                $squareSize = $imageHeight;
                $srcX = ($imageWidth - $squareSize) / 2;
                $srcY = 0;
                break;
        }

        // Create a new "blank" image
        $resizedPicture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resizedPicture, $pictureSource, 0, 0, $srcX, $srcY, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory') . $folder;

        // Create the destination folder if it doesn't exist
        if (!file_exists($path . '/mini/')) {
            mkdir($path . '/mini/', 0755, true);
        }

        // Store the cropped image
        imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $height . '-' . $file);

        $picture->move($path . '/', $file);

        return $file;
    }

    public function delete(string $file, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if ($file !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $file;

            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $file;

            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}
