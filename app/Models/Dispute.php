<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Dispute
 *
 * @property int                             $id
 * @property int                             $paygate_id
 * @property string                          $dispute_id
 * @property \Illuminate\Support\Carbon|null $create_time
 * @property \Illuminate\Support\Carbon|null $update_time
 * @property string|null                     $buyer_transaction_id
 * @property int                             $merchant_id
 * @property string|null                     $reason
 * @property string|null                     $status
 * @property string|null                     $dispute_state
 * @property string|null                     $dispute_amount_currency
 * @property float|null                      $dispute_amount_value
 * @property string|null                     $dispute_life_cycle_stage
 * @property string|null                     $dispute_channel
 * @property \Illuminate\Support\Carbon|null $seller_response_due_date
 * @property string|null                     $link
 * @property int|null                        $created_at
 * @property int|null                        $updated_at
 * @method errors()
 */
class Dispute extends Model {

    use HasFactory;

    public final const string STATUS_DENIED                                  = 'DENIED';

    public final const string STATUS_CLOSED                                  = 'CLOSED';

    public final const string STATUS_EXPIRED                                 = 'EXPIRED';

    public final const string STATUS_RESOLVED                                = 'RESOLVED';

    public final const string STATUS_WAITING_FOR_BUYER_RESPONSE              = 'WAITING_FOR_BUYER_RESPONSE';

    public final const string STATUS_WAITING_FOR_SELLER_RESPONSE             = 'WAITING_FOR_SELLER_RESPONSE';

    public final const string STATUS_UNDER_REVIEW                            = 'UNDER_REVIEW';

    public final const string STATUS_OPEN                                    = 'OPEN';

    public final const array  STATUSES                                       = [
        self::STATUS_DENIED                      => 'DENIED',
        self::STATUS_CLOSED                      => 'CLOSED',
        self::STATUS_EXPIRED                     => 'EXPIRED',
        self::STATUS_RESOLVED                    => 'RESOLVED',
        self::STATUS_WAITING_FOR_BUYER_RESPONSE  => 'WAITING_FOR_BUYER_RESPONSE',
        self::STATUS_WAITING_FOR_SELLER_RESPONSE => 'WAITING_FOR_SELLER_RESPONSE',
        self::STATUS_UNDER_REVIEW                => 'UNDER_REVIEW',
        self::STATUS_OPEN                        => 'OPEN',
    ];

    const                     REASON_MERCHANDISE_OR_SERVICE_NOT_RECEIVED     = 'MERCHANDISE_OR_SERVICE_NOT_RECEIVED';

    const                     REASON_MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED = 'MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED';

    const                     REASON_UNAUTHORISED                            = 'UNAUTHORISED';

    const                     REASON                                         = [
        self::REASON_MERCHANDISE_OR_SERVICE_NOT_RECEIVED     => 'MERCHANDISE_OR_SERVICE_NOT_RECEIVED',
        self::REASON_MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED => 'MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED',
        self::REASON_UNAUTHORISED                            => 'UNAUTHORISED',
    ];

    const                     EVIDENCE_TYPE_PROOF_OF_FULFILLMENT             = 'PROOF_OF_FULFILLMENT';

    const                     EVIDENCE_TYPE_PROOF_OF_REFUND                  = 'PROOF_OF_REFUND';

    const                     EVIDENCE_TYPE_OTHER                            = 'OTHER';

    const                     EVIDENCE_TYPE                                  = [
        self::EVIDENCE_TYPE_PROOF_OF_FULFILLMENT => 'PROOF_OF_FULFILLMENT',
        self::EVIDENCE_TYPE_PROOF_OF_REFUND      => 'PROOF_OF_REFUND',
        self::EVIDENCE_TYPE_OTHER                => 'OTHER',
    ];

    protected $fillable = [
        'dispute_id',
        'create_time',
        'update_time',
        'buyer_transaction_id',
        'merchant_id',
        'reason',
        'status',
        'dispute_state',
        'dispute_amount_currency',
        'dispute_amount_value',
        'dispute_life_cycle_stage',
        'dispute_channel',
        'seller_response_due_date',
        'link',
        'paygate_id',
    ];

    protected $casts    = [
        'create_time'              => 'datetime',
        'update_time'              => 'datetime',
        'seller_response_due_date' => 'datetime',
    ];

    /**
     * Validate if dispute_id already exists in the database.
     *
     * @param string $disputeId
     *
     * @return bool
     */
    public static function isUniqueDispute(string $disputeId): bool
    {
        return !self::where('dispute_id', $disputeId)->exists();
    }

    public static function getLabelStatus($status): string {
        $statusLabels = [
            'WAITING_FOR_SELLER_RESPONSE' => 'warning',
        ];
        $labelClass   = $statusLabels[$status] ?? 'secondary';
        return '<span class="badge bg-' . $labelClass . ' text-dark">' . ucfirst(strtolower(str_replace('_', ' ', $status))) . '</span>';
    }

}
