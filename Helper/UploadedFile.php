<?php

namespace Helper;


class UploadedFile
{
    private $fileName;
    private $tmpFileName;
    private $type;
    private $error;
    private $size;
    private $uploadDirectory;

    /**
     * UploadedFile constructor.
     * @param $fileData array
     * @param $uploadDirectory string
     */
    public function __construct(array $fileData, $uploadDirectory)
    {
        if ($fileData['error'] === 0) {
            $this->fileName = $fileData['name'];
            $this->tmpFileName = $fileData['tmp_name'];
            $this->type = $fileData['type'];
            $this->error = $fileData['error'];
            $this->size = $fileData['size'];
            $this->uploadDirectory = 'Assets' . DIRECTORY_SEPARATOR . $uploadDirectory . DIRECTORY_SEPARATOR;
        } else {
            throw new \Error('no file uploaded');
        }
    }

    /**
     * @param $fileName string
     */
    public function move($fileName)
    {
        $this->setFileName($fileName);

        move_uploaded_file($this->tmpFileName, $this->uploadDirectory . $fileName);
    }

    /**
     * @return string
     */
    public function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        $ext = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->tmpFileName);
        switch ($ext) {
            case 'image/png':
                return 'png';
            case 'image/jpg':
                return 'jpg';
            case 'image/gif':
                return 'gif';
            case 'image/jpeg':
                return 'jpeg';
            default:
                throw new \Error('The format ' . $ext . ' is not supported');
        }
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return './' . $this->uploadDirectory . $this->fileName;
    }

    /**
     * @param $fileName string
     * @return string
     */
    private function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
}
