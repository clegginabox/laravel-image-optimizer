<?php namespace Approached\LaravelImageOptimizer;

use ImageOptimizer\OptimizerFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageOptimizer extends OptimizerFactory
{

    /**
     * Opitimize a image
     *
     * @param $filepath
     * @param null $fileExtension
     * @throws \Exception
     */
    public function optimizeImage($filepath, $fileExtension = null)
    {
        if (is_null($fileExtension)) {
            $fileExtension = $this->getFileExtensionFromFilepath($filepath);
        }

        $transformHandler = config('imageoptimizer.transform_handler');

        if (!isset($transformHandler[$fileExtension])) {
            throw new \Exception('TransformHandler for file extension: "' . $fileExtension . '"" was not found');
        }

        $this->get($transformHandler[$fileExtension])->optimize($filepath);
    }

    /**
     * Opitimize a image from a UploadedFile
     *
     * @param UploadedFile $image
     * @throws \Exception
     */
    public function optimizeUploadedImageFile(UploadedFile $image)
    {
        $this->optimizeImage($image->getRealPath(), $image->getClientOriginalExtension());
    }

    /**
     * Get extension from a file
     *
     * @param $filepath
     * @return string
     * @throws \Exception
     */
    private function getFileExtensionFromFilepath($filepath)
    {
        $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);

        if (empty($fileExtension)) {
            throw new \Exception('File extension not found');
        }
        return strtolower($fileExtension);
    }
}