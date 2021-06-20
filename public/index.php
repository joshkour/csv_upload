<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));

require ROOT_DIR . '/vendor/autoload.php';

use App\Traits\Date;
use App\Transformers\BankTransactionCsvTransformer;
use App\Services\FileInputReaderCsv;
use App\Services\BankTransactionService;
use App\Facades\BankTransactionFacade;
use App\Controllers\BankTransactionUploaderController;

// CSV File Input Reader class
$fileInputReaderCsv = new FileInputReaderCsv();

// Bank transaction transformer class
$bankTransactionCsvTransformer = new BankTransactionCsvTransformer();

// Bank transaction service class
$bankTransactionService = new BankTransactionService();

// Facade for abstraction to complex sub systems used for
// 1. Transforming bank transactions data from CSV
// 2. Creating BankTransaction objects,
// 3. Sorting BankTransactions
$bankTransactionFacade = new BankTransactionFacade($bankTransactionCsvTransformer, $bankTransactionService);

// A router should be implemented to handle request.
// (Frameworks will make this easier i.e Laravel etc)
$bankTransactionUploaderController = new BankTransactionUploaderController($fileInputReaderCsv, $bankTransactionFacade);
$bankTransactionUploaderController->index();
