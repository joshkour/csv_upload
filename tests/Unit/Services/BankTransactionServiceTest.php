<?php
namespace Tests\Unit\Services;

use DateTime;
use PHPUnit\Framework\TestCase;
use App\Services\BankTransactionService;
use App\Models\BankTransaction;

/**
 * Defines a class for testing BankTransactionService.
 *
 * @group Unit
 */
final class BankTransactionServiceTest extends TestCase
{
    /**
     * Service under test.
     *
     * @var App\Services\BankTransactionService
     */
    private $bankTransactionService;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->bankTransactionService = new BankTransactionService();
    }

    /**
     * Data provider for testVerifyKey().
     *
     * @return array
     */
    public function providerTestVerifyKey()
    {
        return [
            ['', false],
            ['asd', false],
            ['ASD123!', false],
            ['ASD123ABZYHA', false],
            ['2345XYZ2345XYZ', false],
            ['MPNQYKVJ3G', true],
            ['U6BD3M75FD', false],
            ['ZUFBQGCKTK', true],
            ['NUF5V6PT3U', false],
        ];
    }

    /**
     * Test method testVerifyKey.
     *
     * @dataProvider providerTestVerifyKey
     */
    public function testVerifyKey(string $key, bool $expected)
    {
        $results = $this->bankTransactionService->verifyKey($key);
        $this->assertEquals($expected, $results);
    }

    /**
     * Test method convertCsvBankTransaction.
     */
    public function testSortBankTransactions()
    {
        $bankTransactions = [];
        $bankTransaction1 = new BankTransaction();
        $bankTransaction1->setDateTime(DateTime::createFromFormat(
            BankTransactionService::DATETIME_FORMAT,
            '2000-02-01 2:00PM'
        ));
        $bankTransaction1->setTransactionCode('sometranscode');
        $bankTransaction1->setIsValidTransaction(false);
        $bankTransaction1->setCustomerNumber(1234);
        $bankTransaction1->setReference('someref');
        $bankTransaction1->setAmount(5678);
        $bankTransactions[] = $bankTransaction1;

        $bankTransaction2 = new BankTransaction();
        $bankTransaction2->setDateTime(DateTime::createFromFormat(
            BankTransactionService::DATETIME_FORMAT,
            '2001-03-02 1:00PM'
        ));
        $bankTransaction2->setTransactionCode('sometranscode');
        $bankTransaction2->setIsValidTransaction(false);
        $bankTransaction2->setCustomerNumber(1234);
        $bankTransaction2->setReference('someref');
        $bankTransaction2->setAmount(5678);
        $bankTransactions[] = $bankTransaction2;

        $results = $this->bankTransactionService->sortBankTransactions($bankTransactions);
        $this->assertEquals($bankTransaction2, $results[0]);
        $this->assertEquals($bankTransaction1, $results[1]);
    }

    /**
     * Test method testCreateCsvBankTransaction.
     */
    public function testCreateCsvBankTransaction()
    {
        $bankTransactionTransformed = [
            'datetime' => '2000-01-01 1:00PM',
            'transaction_code' => 'sometranscode',
            'customer_number' => '1234',
            'reference' => 'someref',
            'amount' => '5678'
        ];

        $bankTransaction = new BankTransaction();
        $bankTransaction->setDateTime(DateTime::createFromFormat(
            BankTransactionService::DATETIME_FORMAT,
            '2000-01-01 1:00PM'
        ));
        $bankTransaction->setTransactionCode('sometranscode');
        $bankTransaction->setIsValidTransaction(false);
        $bankTransaction->setCustomerNumber(1234);
        $bankTransaction->setReference('someref');
        $bankTransaction->setAmount(5678);

        $result = $this->bankTransactionService->createBankTransaction($bankTransactionTransformed);
        $this->assertInstanceOf(BankTransaction::class, $result);
        $this->assertEquals($bankTransaction, $result);
    }

    /**
     * Test method testCreateBankTransactions.
     */
    public function testCreateBankTransactions()
    {
        $bankTransactionTransformed = [
            'datetime' => '2000-02-01 2:00PM',
            'transaction_code' => 'sometranscode',
            'customer_number' => '1234',
            'reference' => 'someref',
            'amount' => '5678'
        ];
        $bankTransactionTransformed2 = [
            'datetime' => '2001-03-02 1:00PM',
            'transaction_code' => 'sometranscode2',
            'customer_number' => '2345',
            'reference' => 'someref2',
            'amount' => '6789'
        ];
        $bankTransactionsTransformed = [
            $bankTransactionTransformed,
            $bankTransactionTransformed2
        ];

        $result = $this->bankTransactionService->createBankTransactions($bankTransactionsTransformed);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(BankTransaction::class, $result[0]);
        $this->assertInstanceOf(BankTransaction::class, $result[1]);
    }
}
