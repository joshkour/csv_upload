<?php

namespace App\Services;

use App\Models\BankTransaction;
use App\Traits\DateTrait;

/**
 * BankTransactionService class.
 *
 * Service class that provides functionalities around bank transactions.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class BankTransactionService
{
    use DateTrait;

    // CSV date format
    const DATETIME_FORMAT = 'Y-m-d g:iA';

    // Define index for data array
    const INDEX_DATETIME = 'datetime';
    const INDEX_TRANSACTION_CODE = 'transaction_code';
    const INDEX_CUSTOMER_NUMBER = 'customer_number';
    const INDEX_REFERENCE = 'reference';
    const INDEX_AMOUNT = 'amount';

    // Valid chars for verifying transaction
    const VALID_CHARS = ['2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F',
    'G', 'H', 'J', 'K','L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    /**
     * Parse and clean date time.
     *
     * There are cases where 'month', 'day' may contain only 1 digit (ensure padded to make 2 digits)
     *
     * @param string $dateTime
     * @return string
     */
    protected function cleanDateTime(string $dateTime) : string
    {
        preg_match('/(\d*)-(\d*)-(\d*) (\d*):(\d*)(am|pm)/i', $dateTime, $matches);
        $year = !empty($matches[1]) ? $matches[1] : '';
        $month = !empty($matches[2]) ? str_pad($matches[2], 2, '0', STR_PAD_LEFT) : '';
        $day = !empty($matches[3]) ? str_pad($matches[3], 2, '0', STR_PAD_LEFT) : '';
        $hour = !empty($matches[4]) ? $matches[4] : '';
        $mins = !empty($matches[5]) ? $matches[5] : '';
        $meridiem = !empty($matches[6]) ? strtolower($matches[6]) : '';

        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$mins.$meridiem;
    }

    /**
     * Implementation of algorithm for check digit.
     *
     * @param string $input
     * @return string
     */
    protected function generateCheckCharacter(string $input) : string
    {
        $factor = 2;
        $sum = 0;
        $n = count(self::VALID_CHARS);

        // Starting from the right and working leftwards is easier since
        // the initial "factor" will always be "2"
        for ($i = strlen($input) - 1; $i >= 0; $i--) {
            $codePoint = array_search($input[$i], self::VALID_CHARS);
            $addend = $factor * $codePoint;

            // Alternate the "factor" that each "codePoint" is multiplied by
            $factor = ($factor === 2) ? 1 : 2;

            // Sum the digits of the "addend" as expressed in base "n"
            $addend = intval(($addend / $n) + ($addend % $n));
            $sum += $addend;
        }

        // Calculate the number that must be added to the "sum"
        // to make it divisible by "n"
        $remainder = $sum % $n;
        $checkCodePoint = ($n - $remainder) % $n;

        return self::VALID_CHARS[$checkCodePoint];
    }

    /**
     * Verify if a transaction code is valid.
     *
     * @param string $key
     * @return bool
     */
    public function verifyKey(string $key) : bool
    {
        if (strlen($key) !== 10) {
            return false;
        }

        $input = substr(strtoupper($key), 0, 9);
        $checkDigit = $this->generateCheckCharacter($input);
        return $key[9] === $checkDigit;
    }

    /**
     * Sort bank transactions.
     *
     * i.e sorting by latest date time desc.
     *
     * @param array $bankTransactions
     * @return array
     */
    public function sortBankTransactions(array $bankTransactions) : array
    {
        usort($bankTransactions, function ($x, $y) {
            return $y->getDateTime() <=> $x->getDateTime();
        });

        return $bankTransactions;
    }

    /**
     * Create a bank transaction object (model).
     *
     * @param array $bankTransactionArr
     * @return BankTransaction
     */
    public function createBankTransaction(array $bankTransactionArr) : BankTransaction
    {
        $bankTransaction = new BankTransaction();

        // Set date time
        $dateTimeStr = $bankTransactionArr[self::INDEX_DATETIME];
        $dateTimeStr = $this->cleanDateTime($dateTimeStr);
        $dateTime = $this->createDateTime($dateTimeStr, self::DATETIME_FORMAT);
        $bankTransaction->setDateTime($dateTime);

        // Set transaction code and valid transaction
        $transactionCode = $bankTransactionArr[self::INDEX_TRANSACTION_CODE];
        $bankTransaction->setTransactionCode($transactionCode);

        $isValidTransaction = $this->verifyKey($transactionCode);
        $bankTransaction->setIsValidTransaction($isValidTransaction);

        // Set customer number
        $customerNumber = intval($bankTransactionArr[self::INDEX_CUSTOMER_NUMBER]);
        $bankTransaction->setCustomerNumber($customerNumber);

        // Set reference
        $bankTransaction->setReference($bankTransactionArr[self::INDEX_REFERENCE]);

        // Set amount
        $amount = intval($bankTransactionArr[self::INDEX_AMOUNT]);
        $bankTransaction->setAmount($amount);

        return $bankTransaction;
    }

    /**
     * Convert bank transactions into bank transaction objects (models).
     *
     * @param array $bankTransactionsArr
     * @return array
     */
    public function createBankTransactions(array $bankTransactionsArr) : array
    {
        $bankTransactions = [];
        foreach ($bankTransactionsArr as $bankTransactionArr) {
            $bankTransactions[] = $this->createBankTransaction($bankTransactionArr);
        }

        return $bankTransactions;
    }
}
