<?php
namespace Tests\Unit\Facades;

use PHPUnit\Framework\TestCase;
use App\Facades\BankTransactionFacade;
use App\Models\BankTransaction;

/**
 * Defines a class for testing BankTransactionFacade.
 *
 * @group Unit
 */
final class BankTransactionFacadeTest extends TestCase
{
    /**
     * Facade under test.
     *
     * @var App\Facades\BankTransactionFacade
     */
    private $bankTransactionFacade;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->mockBankTransactionCsvTransformer =
        $this->getMockBuilder('App\Transformers\BankTransactionCsvTransformer')
            ->disableOriginalConstructor()
            ->setMethods([
                'transform',
            ])
            ->getMock();

        $this->mockBankTransactionService = $this->getMockBuilder('App\Services\BankTransactionService')
            ->disableOriginalConstructor()
            ->setMethods([
                'sortBankTransactions',
                'createBankTransactions'
            ])
            ->getMock();

        $this->bankTransactionFacade = new BankTransactionFacade(
            $this->mockBankTransactionCsvTransformer,
            $this->mockBankTransactionService
        );
    }

    /**
     * Test method testGetBankTransactionsEmpty.
     *
     * Test case where csv upload return empty results.
     */
    public function testGetBankTransactionsEmpty()
    {
        $csvBankTransactions = [];

        $this->mockBankTransactionCsvTransformer->expects($this->never())
            ->method('transform');

        $this->mockBankTransactionService->expects($this->once())
            ->method('createBankTransactions')
            ->with($this->equalTo([]))
            ->willReturn([]);

        $this->mockBankTransactionService->expects($this->once())
            ->method('sortBankTransactions')
            ->with($this->equalTo([]))
            ->willReturn([]);

        $result = $this->bankTransactionFacade->getBankTransactions($csvBankTransactions);
        $this->assertEquals([], $result);
    }

    /**
     * Test method testGetBankTransactionsOneResult.
     *
     * Test case where csv upload return with one data result.
     */
    public function testGetBankTransactionsOneResult()
    {
        $csvBankTransaction = ['2000-01-01 1:00PM', 'sometranscode', 'somercustnumber', 'someref', 'someamount'];
        $csvBankTransactions = [
            $csvBankTransaction
        ];

        $bankTransactionTransformed = [
            'datetime' => '2000-01-01 1:00PM',
            'transaction_code' => 'sometranscode',
            'customer_number' => 'somercustnumber',
            'reference' => 'someref',
            'amount' => 'someamount'
        ];
        $bankTransactionsTransformed = [
            $bankTransactionTransformed
        ];

        $bankTransaction = new BankTransaction();
        $bankTransactions = [
            $bankTransaction
        ];

        $this->mockBankTransactionCsvTransformer->expects($this->once())
            ->method('transform')
            ->with($this->equalTo($csvBankTransaction))
            ->willReturn($bankTransactionTransformed);

        $this->mockBankTransactionService->expects($this->once())
            ->method('createBankTransactions')
            ->with($this->equalTo($bankTransactionsTransformed))
            ->willReturn($bankTransactions);

        $this->mockBankTransactionService->expects($this->once())
            ->method('sortBankTransactions')
            ->with($this->equalTo($bankTransactions))
            ->willReturn($bankTransactions);

        $result = $this->bankTransactionFacade->getBankTransactions($csvBankTransactions);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(BankTransaction::class, $result[0]);
    }

    /**
     * Test method testGetBankTransactionsMoreThanOneResult.
     *
     * Test case where csv upload return with more than one data result.
     */
    public function testGetBankTransactionsMoreThanOneResult()
    {
        $csvBankTransaction = ['2000-02-01 2:00PM', 'sometranscode', '1234', 'someref', '5678'];
        $csvBankTransaction2 = ['2001-03-02 1:00PM', 'sometranscode2', '2345', 'someref2', '6789'];
        $csvBankTransactions = [
            $csvBankTransaction,
            $csvBankTransaction2,
        ];

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

        $bankTransaction = new BankTransaction();
        $bankTransaction2 = new BankTransaction();
        $bankTransactions = [
            $bankTransaction,
            $bankTransaction2
        ];

        $this->mockBankTransactionCsvTransformer->expects($this->at(0))
            ->method('transform')
            ->with($this->equalTo($csvBankTransaction))
            ->willReturn($bankTransactionTransformed);

        $this->mockBankTransactionCsvTransformer->expects($this->at(1))
            ->method('transform')
            ->with($this->equalTo($csvBankTransaction2))
            ->willReturn($bankTransactionTransformed2);

        $this->mockBankTransactionService->expects($this->once())
            ->method('createBankTransactions')
            ->with($this->equalTo($bankTransactionsTransformed))
            ->willReturn($bankTransactions);

        $this->mockBankTransactionService->expects($this->once())
            ->method('sortBankTransactions')
            ->with($this->equalTo($bankTransactions))
            ->willReturn($bankTransactions);

        $result = $this->bankTransactionFacade->getBankTransactions($csvBankTransactions);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(BankTransaction::class, $result[0]);
        $this->assertInstanceOf(BankTransaction::class, $result[1]);
    }
}
