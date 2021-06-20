<?php

namespace App\Services\Interfaces;

/**
 * FileInputReaderInterface interface.
 *
 * Interface for extracting data from form file input upload.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
interface FileInputReaderInterface
{
    /**
     * Set file input name.
     *
     * @param string $fileInputName
     * @return void
     */
    public function setFileInputName(string $fileInputName) : void;

    /**
     * Get file input name.
     *
     * @param void
     * @return string
     */
    public function getFileInputName() : string;

    /**
     * Check if file upload is valid mime type.
     *
     * @param void
     * @return bool
     */
    public function isValidMimeType() : bool;

    /**
     * Extract data from file input upload.
     *
     * @param void
     * @return array
     */
    public function extractData() : array;
}
