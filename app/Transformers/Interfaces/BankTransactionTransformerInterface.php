<?php

namespace App\Transformers\Interfaces;

/**
 * BankTransactionTransformerInterface interface.
 *
 * Interface for transforming data.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
interface BankTransactionTransformerInterface
{
    /**
     * Transform the data.
     *
     * @param array $data
     * @return array
     */
    public function transform(array $data) : array;
}
