<?php

declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\Paygate;
use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 *
 * This class is responsible for transforming user data for DataTables.
 */
class TransactionTransformer extends TransformerAbstract
{
    /**
     * Data transformer.
     */
    public function transform(Transaction $transaction): array
    {

        return [
            'id' => '<span class="fw-bold float-start">'.($transaction->id ?? '').'</span>',
            'transaction_id' => $transaction->transaction_id,
            'transaction_event_code' => $transaction->transaction_event_code,
            'transaction_initiation_date' => $transaction->transaction_initiation_date,
            'transaction_updated_date' => $transaction->transaction_updated_date,
            'transaction_amount_currency' => $transaction->transaction_amount_currency,
            'transaction_amount_value' => $transaction->transaction_amount_value,
            'transaction_status' => $transaction->transaction_status,
            'action' => $this->renderActions($transaction),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(Transaction $transaction): string
    {
        return '';
    }
}
