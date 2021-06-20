<?php

namespace App\Services;

use App\Services\Interfaces\FileInputReaderInterface;

/**
 * FileInputReaderCsv class.
 *
 * Service class that provides functionalities around extracting CSV data from file input.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class FileInputReaderCsv implements FileInputReaderInterface
{
    // Current list of valid mime types
    const VALID_MIME_TYPES = ['text/csv', 'application/vnd.ms-excel'];

    // File input field name
    protected $fileInputName;

    /**
     * Set file input name.
     *
     * @param string $fileInputName
     * @return void
     */
    public function setFileInputName(string $fileInputName) : void
    {
        $this->fileInputName = $fileInputName;
    }

    /**
     * Get file input name.
     *
     * @param void
     * @return string
     */
    public function getFileInputName() : string
    {
        return $this->fileInputName;
    }

    /**
     * Check if file upload is valid mime type.
     *
     * @param void
     * @return bool
     */
    public function isValidMimeType() : bool
    {
        $fileInputName = $this->getFileInputName();
        if (isset($_FILES[$fileInputName])) {
            $fileType = $_FILES[$fileInputName]['type'];
            if (in_array($fileType, self::VALID_MIME_TYPES)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract CSV data from file input upload.
     *
     * @param void
     * @return array
     */
    public function extractData() : array
    {
        $data = [];

        $fileInputName = $this->getFileInputName();
        if (!empty($_FILES[$fileInputName]['tmp_name'])) {
            $file = $_FILES[$fileInputName]['tmp_name'];
            $file = fopen($file, 'r');

            $count = 0;
            while (($line = fgetcsv($file)) !== false) {
                // Ignore header
                if ($count === 0) {
                    $count++;
                    continue;
                }

                // Store line data
                $data[] = $line;
            }

            fclose($file);
        }

        return $data;
    }
}
