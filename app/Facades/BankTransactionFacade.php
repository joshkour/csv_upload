<?php

namespace App\Facades;

use App\Transformers\Interfaces\BankTransactionTransformerInterface;
use App\Services\BankTransactionService;

/**
 * BankTransactionFacade class.
 *
 * Facade for transforming data for use, creating bank transaction objects and sorting.
 * Any other related funtionalities can be added in the future.
 *
 * This may grow and allows us to hide complexities from a Controller.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class BankTransactionFacade
{
    protected $bankTransactionTransformer;
    protected $bankTransactionService;

    /**
     * Class constructor.
     *
     * @param BankTransactionService $bankTransactionService
     * @return void
     */
    public function __construct(
        BankTransactionTransformerInterface $bankTransactionTransformer,
        BankTransactionService $bankTransactionService
    ) {
        $this->bankTransactionTransformer = $bankTransactionTransformer;
        $this->bankTransactionService = $bankTransactionService;
    }
    

    /**
     * Get bank transactions.
     *
     * Convert bank transaction objects from data array and sort bank transactions accordingly (i.e date).
     *
     * @param array $bankTransactionsArr
     * @return array
     */
    public function getBankTransactions(array $bankTransactionsArr) : array
    {
        $bankTransactions = [];

        // Tranform the data for use
        $bankTransactionsTransformedArr = [];
        foreach ($bankTransactionsArr as $bankTransactionArr) {
            $bankTransactionsTransformedArr[] = $this->bankTransactionTransformer->transform($bankTransactionArr);
        }

        // Convert CSV data into bank transaction objects (i.e models)
        $bankTransactions = $this->bankTransactionService->createBankTransactions($bankTransactionsTransformedArr);

        // Sort bank transactions (i.e date time)
        $bankTransactions = $this->bankTransactionService->sortBankTransactions($bankTransactions);

        return $bankTransactions;
    }
}
