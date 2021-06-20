<?php

namespace App\Controllers;

use App\Services\Interfaces\FileInputReaderInterface;
use App\Facades\BankTransactionFacade;

/**
 * BankTransactionUploaderController class.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class BankTransactionUploaderController extends BaseController
{
    protected $fileInputReader;
    protected $bankTransactionFacade;

    /**
     * Class constructor.
     *
     * @param DashboardFacade $importerFacade
     * @return void
     */
    public function __construct(
        FileInputReaderInterface $fileInputReader,
        BankTransactionFacade $bankTransactionFacade
    ) {
        $this->fileInputReader = $fileInputReader;
        $this->bankTransactionFacade = $bankTransactionFacade;
    }

    /**
     * Index method.
     *
     * @param void
     * @return void
     */
    public function index()
    {
        $bankTransactions = [];
        if (intval($this->post('form-submit')) === 1) {
            $this->fileInputReader->setFileInputName('file-csv');
            if (!$this->fileInputReader->isValidMimeType()) {
                $this->setErrorMessage('Please upload a CSV file.');
            } else {
                // Extract CSV data from file input
                $bankTransactionsArr = $this->fileInputReader->extractData();

                // Facade is used to hide complexities and to ensure cleaner and maintainable code
                $bankTransactions = $this->bankTransactionFacade->getBankTransactions($bankTransactionsArr);
            }
        }

        // Pass data and display in the view
        $messages = $this->getMessages();
        return $this->view('import_bank_transactions.php', compact('messages', 'bankTransactions'));
    }
}
