<?php
namespace App\Transformers;

use App\Transformers\Interfaces\BankTransactionTransformerInterface;
use App\Services\BankTransactionService;

/**
 * BankTransactionCsvTransformer class.
 *
 * Transform data from CSV to an array with consistent keys.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class BankTransactionCsvTransformer implements BankTransactionTransformerInterface
{
    // File upload CSV indexes
    const CSV_INDEX_DATETIME = 0;
    const CSV_INDEX_TRANSACTION_CODE = 1;
    const CSV_INDEX_CUSTOMER_NUMBER = 2;
    const CSV_INDEX_REFERENCE = 3;
    const CSV_INDEX_AMOUNT = 4;

    /**
     * Transform the data.
     *
     * @param array $data
     * @return array
     */
    public function transform(array $data) : array
    {
        return [
            BankTransactionService::INDEX_DATETIME =>
                !empty($data[self::CSV_INDEX_DATETIME]) ? $data[self::CSV_INDEX_DATETIME] : '',
            BankTransactionService::INDEX_TRANSACTION_CODE =>
                !empty($data[self::CSV_INDEX_TRANSACTION_CODE]) ? $data[self::CSV_INDEX_TRANSACTION_CODE] : '',
            BankTransactionService::INDEX_CUSTOMER_NUMBER =>
                !empty($data[self::CSV_INDEX_CUSTOMER_NUMBER]) ? $data[self::CSV_INDEX_CUSTOMER_NUMBER] : '',
            BankTransactionService::INDEX_REFERENCE =>
                !empty($data[self::CSV_INDEX_REFERENCE]) ? $data[self::CSV_INDEX_REFERENCE] : '',
            BankTransactionService::INDEX_AMOUNT =>
                !empty($data[self::CSV_INDEX_AMOUNT]) ? $data[self::CSV_INDEX_AMOUNT] : '',
        ];
    }
}
