<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Paygate\PayPalAPI;
use App\Services\DataTables\DisputeDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Dispute.
 *
 * This controller is responsible for managing dispute-related operations.
 */
class Dispute extends BaseController
{
    /**
     * Action `index`.
     */
    public function index(DisputeDataTable $dataTable)
    {
//        $this->filterDateRange($dataTable);
//
//        return $dataTable->render('dispute.index');
        $clientId = 'AfGFZ63l-30heXk1Xf2iNiO0SnhhIKeaEq9uIsqQt4kPenxBk_ZNwFhLTDDRDsX1bdV8_uVTMPnBgLnK';
        $clientSecret = "EECgn7P9B5dgKFFvQWFQ6AH0AGqmm1ibbl7G_7njz59SKX-EKvZWCeY9beP-a8TU64WoC6FwPqdreAak";

        $paypal = new PayPalAPI($clientId, $clientSecret, true);
        $response = $paypal->provideSupportingInfo("PP-R-GQM-10106357", "Additional supporting details for the dispute.");
        echo "<pre>";
        print_r($response);

    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  Dispute ID to show
     */
    public function show(int|string $id): View
    {
        $dispute = \App\Models\Dispute::find($id);

        return view('dispute.show', compact('dispute'));
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  Dispute ID to delete
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
