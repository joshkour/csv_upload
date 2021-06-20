<?php

namespace App\Models;

use DateTime;

/**
 * BankTransaction class.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class BankTransaction
{
    protected $dateTime;
    protected $transactionCode;
    protected $customerNumber;
    protected $reference;
    protected $amount;

    /**
     * Set date time.
     *
     * @param string $dateTime
     * @return void
     */
    public function setDateTime(DateTime $dateTime) : void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * Get date time.
     *
     * @param void
     * @return DateTime
     */
    public function getDateTime() : DateTime
    {
        return $this->dateTime;
    }

    /**
     * Set transaction code.
     *
     * @param string $transactionCode
     * @return void
     */
    public function setTransactionCode(string $transactionCode) : void
    {
        $this->transactionCode = $transactionCode;
    }

    /**
     * Get transaction code.
     *
     * @param void
     * @return string
     */
    public function getTransactionCode() : string
    {
        return $this->transactionCode;
    }

    /**
     * Set transaction validitiy.
     *
     * @param bool $isValidTransaction
     * @return void
     */
    public function setIsValidTransaction(bool $isValidTransaction) : void
    {
        $this->isValidTransaction = $isValidTransaction;
    }

    /**
     * Get transaction validitiy.
     *
     * @param void
     * @return string
     */
    public function getIsValidTransaction() : bool
    {
        return $this->isValidTransaction;
    }

    /**
     * Set customer number.
     *
     * @param int $customerNumber
     * @return void
     */
    public function setCustomerNumber(int $customerNumber) : void
    {
        $this->customerNumber = $customerNumber;
    }

    /**
     * Get customer number.
     *
     * @param void
     * @return int
     */
    public function getCustomerNumber() : int
    {
        return $this->customerNumber;
    }

    /**
     * Set reference.
     *
     * @param string $reference
     * @return void
     */
    public function setReference(string $reference) : void
    {
        $this->reference = $reference;
    }

    /**
     * Get reference.
     *
     * @param void
     * @return string
     */
    public function getReference() : string
    {
        return $this->reference;
    }

    /**
     * Set amount.
     *
     * @param int $amount
     * @return void
     */
    public function setAmount(int $amount) : void
    {
        $this->amount = $amount;
    }

    /**
     * Get amount.
     *
     * @param void
     * @return int
     */
    public function getAmount() : int
    {
        return $this->amount;
    }

    /**
     * Get displayable date time.
     *
     * @param string $format
     * @return string
     */
    public function getDisplayableDateTime(string $format = 'd/m/Y g:iA') : string
    {
        return $this->dateTime->format($format);
    }

    /**
     * Get displayable valid transaction.
     *
     * @param void
     * @return string
     */
    public function getDisplayableValidTransaction() : string
    {
        return $this->isValidTransaction ? 'Yes' : 'No';
    }

    /**
     * Get displayable amount.
     *
     * @param void
     * @return string
     */
    public function getDisplayableAmount() : string
    {
        $amountFormatted = number_format($this->amount/100, 2);
        return $amountFormatted < 0 ? '-$'.abs($amountFormatted) : '$'.$amountFormatted;
    }

    /**
     * Get class name for displayable amount.
     *
     * @param void
     * @return string
     */
    public function getDisplayableAmountClass() : string
    {
        return $this->amount < 0 ? 'red' : 'green';
    }
}
