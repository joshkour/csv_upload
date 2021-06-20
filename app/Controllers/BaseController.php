<?php

namespace App\Controllers;

/**
 * BaseController class.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class BaseController
{
    /**
     * Base path to the views.
     *
     * @var string
     */
    private $baseViewPath = ROOT_DIR.'/views/';

    /**
     * Store system messages (i.e error or success messages).
     *
     * @var array
     */
    private $messages = [];

    /**
     * Error message type.
     *
     * @var string
     */
    private const MESSAGE_TYPE_ERROR = 'error';

    /**
     * Success message type.
     *
     * @var string
     */
    private const MESSAGE_TYPE_SUCCESS = 'success';

    /**
     * Include view template given path and data.
     *
     * @param string $path
     * @param array $data
     * @return void
     */
    protected function view(string $path, array $data = []) : void
    {

        // Dynamically create variables to be used by the view template.
        foreach ($data as $k => $v) {
            ${$k} = $v;
        }

        include_once($this->baseViewPath.$path);
    }

    /**
     * Get all messages.
     *
     * @param void
     * @return array
     */
    protected function getMessages() : array
    {
        return $this->messages;
    }

    /**
     * Set an error message
     *
     * @param string $message
     * @return void
     */
    protected function setErrorMessage(string $message) : void
    {
        $this->messages[self::MESSAGE_TYPE_ERROR][] = $message;
    }

    /**
     * Set a successful message
     *
     * @param string $message
     * @return void
     */
    protected function setSuccessMessage(string $message) : void
    {
        $this->messages[self::MESSAGE_TYPE_SUCCESS][] = $message;
    }

     /**
     * Retrieve POST value given key.
     *
     * @param string $key
     * @return string
     */
    protected function post(string $key) : string
    {
        return isset($_POST[$key]) ? $_POST[$key] : '';
    }
}
