<?php include(ROOT_DIR.'/views/layouts/header.php') ?>

<div class="container">
    <h1>Upload new CSV</h1>
    <form class="csv-upload" action="index.php" method="post" enctype="multipart/form-data" />
        <input type="hidden" name="form-submit" value="1" />
        <label>Select CSV to upload</label><br />
        <input type="file" name="file-csv" /><br />
        <input type="submit" name="submit" value="Upload CSV" />
    </form>

    <?php if (!empty($messages['error']) && count($messages['error'])) : ?>
            <div class="error-messages"><?php echo implode('<br />', $messages['error']); ?></div>
    <?php else : ?>
        <?php if (!empty($bankTransactions)) : ?>
            <h2>Bank Transactions from CSV</h2>
            <table class="bank-transactions" cellspacing="0" cellpadding="10" align="center">
                <th>Date</th>
                <th>Transaction Code</th>
                <th>Valid Transaction?</th>
                <th>Customer Number</th>
                <th>Reference</th>
                <th>Amount</th>
            <?php foreach ($bankTransactions as $bankTransaction) : ?>
                <tr>
                    <td><?php echo $bankTransaction->getDisplayableDateTime(); ?></td>
                    <td><?php echo $bankTransaction->getTransactionCode(); ?></td>
                    <td><?php echo $bankTransaction->getDisplayableValidTransaction(); ?></td>
                    <td><?php echo $bankTransaction->getCustomerNumber(); ?></td>
                    <td><?php echo $bankTransaction->getReference(); ?></td>
                    <td><span class="amount <?php echo $bankTransaction->getDisplayableAmountClass(); ?>">
                        <?php echo $bankTransaction->getDisplayableAmount(); ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div><!-- .container -->

<?php include(ROOT_DIR.'/views/layouts/footer.php') ?>
