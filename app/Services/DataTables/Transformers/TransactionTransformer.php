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
            // 'name'       => $paygate->name ?? '',
            'transaction_id'=>$transaction->transaction_id,
            'action' => $this->renderActions($transaction),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(Paygate $paygate): string
    {
        return '';
    }
}
