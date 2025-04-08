<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 18:52
 */

namespace App\Console\Commands;

use App\Models\OrderTracking;
use App\Models\Paygate;
use App\Models\PaypalTransaction;
use App\Paygate\PayPalAPI;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PaypalTransactionCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:update-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update list of transactions.';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $lastSyncFile = storage_path('app/paypal_last_sync.json');
        $t_codesFile = storage_path('app/tcodes.json');

        if (file_exists($t_codesFile)) {
            $t_codes = json_decode(file_get_contents($t_codesFile), true, 512, JSON_THROW_ON_ERROR);
        } else {
            $t_codes = [];
        }

        if (!file_exists($lastSyncFile)) {
            file_put_contents(
                $lastSyncFile,
                json_encode(['last_sync' => '2025-01-01T00:00:00.000Z'], JSON_THROW_ON_ERROR)
            );
        } else {
            file_put_contents(
                $lastSyncFile,
                json_encode([], JSON_THROW_ON_ERROR)
            );
        }

        $lastSyncData = json_decode(file_get_contents($lastSyncFile), true, 512, JSON_THROW_ON_ERROR);
        $startDate = $lastSyncData['last_sync'] ?? '2025-01-01T00:00:00.000Z';

        $now = Carbon::now()->setTimezone('UTC');
        $endDate = $now->format('Y-m-d\TH:i:s\Z');

        $paygates = Paygate::all();

        foreach ($paygates as $paygate) {
            try {
                $paypalApi = new PayPalAPI($paygate);

                $currentStart = Carbon::parse($startDate);
                $currentEnd = clone $currentStart ?? $startDate;

                while ($currentStart->lt($now)) {
                    $currentEnd->addDays(30);

                    if ($currentEnd->gt($now)) {
                        $currentEnd = $now;
                    }

                    $currentStartFormatted = $currentStart->format('Y-m-d\TH:i:s\Z');
                    $currentEndFormatted = $currentEnd->format('Y-m-d\TH:i:s\Z');

                    Log::info('Querying PayPal API for transactions', [
                        'start_date' => $startDate === '2025-01-01T00:00:00.000Z' ? $currentStart->toISOString()
                            : $currentStart,
                        'end_date'   => $currentEnd->toISOString(),
                    ]);

                    $response = $paypalApi->listTransaction(
                        $currentStartFormatted,
                        $currentEndFormatted,
                    );

                    if (isset($response['error'])) {
                        Log::error('PayPal API error', ['error' => $response['error']]);
                    } else {
                        Log::info('PayPal API Response:', ['response' => $response]);
                        // Process the response data here

                        if (!empty($response['transaction_details']) && is_array($response['transaction_details'])) {
                            foreach ($response['transaction_details'] as $transaction) {
                                $transaction_id = data_get($transaction, 'transaction_info.transaction_id');

                                $existingTransaction = PaypalTransaction::where('transaction_id', $transaction_id)
                                    ->first();

                                if (!$existingTransaction) {
                                    $dateTime = Carbon::parse(
                                        data_get($transaction, 'transaction_info.transaction_initiation_date')
                                    );
                                    $itemListName = "";
                                    $itemListCode = "";
                                    $itemQuantity = 0;
                                    if (isset($transaction['cart_info']['item_details'])) {
                                        foreach ($transaction['cart_info']['item_details'] as $item) {
                                            $itemListName .= data_get($item, 'item_name', '').', ';
                                            $itemListCode .= data_get($item, 'item_code', '').', ';
                                            $itemQuantity += data_get($item, 'item_quantity', 0);
                                        }

                                        $itemListName = rtrim($itemListName, ', ');
                                        $itemListCode = rtrim($itemListCode, ', ');
                                    }

                                    $typeString = "";
                                    foreach ($t_codes as $t_code) {
                                        if (data_get(
                                                $transaction,
                                                'transaction_info.transaction_event_code'
                                            ) === $t_code['code']
                                        ) {
                                            $typeString = $t_code['subCategory'];
                                        }
                                    }

                                    PaypalTransaction::create([
                                        'datetime'                     => $dateTime,
                                        'paygate_id'                   => $paygate->id,
                                        'paygate_name'                 => $paygate->name,
                                        'name'                         => data_get(
                                            $transaction,
                                            'payer_info.payer_name.alternate_full_name'
                                        ),
                                        'type'                         => $typeString ?? "Unknown",
                                        'event_code'                   => data_get(
                                            $transaction,
                                            'transaction_info.transaction_event_code'
                                        ),
                                        'status'                       => data_get(
                                            $transaction,
                                            'transaction_info.transaction_status'
                                        ),
                                        'currency'                     => data_get(
                                            $transaction,
                                            'transaction_info.transaction_amount.currency_code'
                                        ),
                                        'gross'                        => data_get(
                                            $transaction,
                                            'transaction_info.transaction_amount.value'
                                        ),
                                        'fee'                          => data_get(
                                            $transaction,
                                            'transaction_info.fee_amount.value'
                                        ),
                                        'net'                          => ((int)data_get(
                                                $transaction,
                                                'transaction_info.transaction_amount.value',
                                                0
                                            ))
                                            + ((int)data_get($transaction, 'transaction_info.fee_amount.value', 0)),
                                        'from_email_address'           => data_get(
                                            $transaction,
                                            'payer_info.email_address'
                                        ),
                                        'to_email_address'             => null,
                                        'transaction_id'               => $transaction_id,
                                        'shipping_address'             => collect(
                                            data_get($transaction, 'shipping_info.address', [])
                                        )
                                            ->only(['line1', 'city', 'state', 'postal_code', 'country_code'])
                                            ->filter()
                                            ->implode(', '),
                                        'address_status'               => data_get(
                                            $transaction,
                                            'shipping_info.address.line1'
                                        ) ? 'Confirmed' : 'Not-Confirmed',
                                        'item_title'                   => $itemListName,
                                        'item_id'                      => $itemListCode,
                                        'shipping_and_handling_amount' => data_get(
                                            $transaction,
                                            'transaction_info.shipping_amount.value'
                                        ),
                                        'insurance_amount'             => data_get(
                                            $transaction,
                                            'transaction_info.insurance_amount.value'
                                        ),
                                        'sales_tax'                    => data_get(
                                            $transaction,
                                            'transaction_info.sales_tax_amount.value'
                                        ),
                                        'reference_txn_id'             => data_get(
                                            $transaction,
                                            'transaction_info.transaction_status'
                                        ) === 'S'
                                            ? null
                                            : data_get($transaction, 'transaction_info.paypal_reference_id'),
                                        'invoice_number'               => data_get(
                                            $transaction,
                                            'transaction_info.invoice_id'
                                        ),
                                        'custom_number'                => data_get(
                                            $transaction,
                                            'transaction_info.custom_field'
                                        ),
                                        'quantity'                     => $itemQuantity,
                                        'receipt_id'                   => null,
                                        'balance'                      => data_get(
                                            $transaction,
                                            'transaction_info.ending_balance.value'
                                        ),
                                        'address_line_1'               => data_get(
                                            $transaction,
                                            'shipping_info.address.line1'
                                        ),
                                        'address_line_2'               => data_get(
                                            $transaction,
                                            'shipping_info.address.line2'
                                        ),
                                        'town_city'                    => data_get(
                                            $transaction,
                                            'shipping_info.address.city'
                                        ),
                                        'state_province'               => data_get(
                                            $transaction,
                                            'shipping_info.address.state'
                                        ),
                                        'zip_postal_code'              => data_get(
                                            $transaction,
                                            'shipping_info.address.postal_code'
                                        ),
                                        'country'                      => data_get(
                                            $transaction,
                                            'shipping_info.address.country_code'
                                        ),
                                        'contact_phone_number'         => data_get(
                                            $transaction,
                                            'payer_info.phone_number.national_number'
                                        ),
                                        'subject'                      => $itemListName,
                                        'note'                         => data_get(
                                            $transaction,
                                            'transaction_info.transaction_note'
                                        ),
                                        'country_code'                 => data_get(
                                            $transaction,
                                            'shipping_info.address.country_code'
                                        ),
                                        'balance_impact'               => null,
                                        'closed_at'                    => null,
                                        'last_checked_at'              => Carbon::now(),
                                        'exported_at'                  => null,
                                    ]);

                                    $response = $paypalApi->getTrackingInfo($transaction_id);
                                    $trackingInfo = data_get($response, 'trackers', []);

                                    if (!empty($trackingInfo)) {
                                        foreach ($trackingInfo as $tracking) {
                                            if (!empty($tracking)) {
                                                OrderTracking::create([
                                                    'paygate_id'          => $paygate->id ?? null,
                                                    'paygate_name'        => $paygate->name ?? null,
                                                    'invoice_number'      => data_get(
                                                        $transaction,
                                                        'transaction_info.invoice_id'
                                                    ),
                                                    'transaction_id'      => $transaction_id,
                                                    'tracking_number'     => data_get($tracking, 'tracking_number'),
                                                    'has_tracking_number' => true,
                                                    'courier_code'        => str_contains(data_get($tracking, 'carrier'), 'OTHER') ? data_get($tracking, 'carrier_name_other') : data_get($tracking, 'carrier'),
                                                    'ordered_at'          =>
                                                        data_get(
                                                            $transaction,
                                                            'transaction_info.transaction_initiation_date'
                                                        ),
                                                ]);
                                            } else {
                                                OrderTracking::create([
                                                    'paygate_id'     => $paygate->id ?? null,
                                                    'paygate_name'   => $paygate->name ?? null,
                                                    'invoice_number' => data_get(
                                                        $transaction,
                                                        'transaction_info.invoice_id'
                                                    ),
                                                    'transaction_id' => $transaction_id,
                                                    'ordered_at'     => Carbon::parse(
                                                        data_get(
                                                            $transaction,
                                                            'transaction_info.transaction_initiation_date'
                                                        )
                                                    ),
                                                ]);
                                            }
                                        }
                                    } else {
                                        OrderTracking::create([
                                            'paygate_id'     => $paygate->id ?? null,
                                            'paygate_name'   => $paygate->name ?? null,
                                            'invoice_number' => data_get($transaction, 'transaction_info.invoice_id'),
                                            'transaction_id' => $transaction_id,
                                            'ordered_at'     => Carbon::parse(
                                                data_get(
                                                    $transaction,
                                                    'transaction_info.transaction_initiation_date'
                                                )
                                            ),
                                        ]);
                                    }
                                }
                            }
                        } else {
                            Log::warning('No transaction details found in PayPal response', ['response' => $response]);
                        }
                    }

                    $currentStart = clone $currentEnd;
                }

                $this->info("Transaction Updated with Paygate: " . $paygate->name);
            } catch (Exception $e) {
                $this->info('Error: ' . $e->getMessage());
                continue;
            }
        }

        file_put_contents($lastSyncFile, json_encode(['last_sync' => $endDate], JSON_THROW_ON_ERROR));
    }

}
