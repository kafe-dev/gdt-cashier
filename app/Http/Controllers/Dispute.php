<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DataTables\DisputeDataTable;
use App\Services\DataTables\PaygateDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Dispute.
 *
 * This controller is responsible for managing dispute-related operations.
 */
class Dispute extends BaseController {

    /**
     * Action `index`.
     */
    public function index(DisputeDataTable $dataTable) {
        return $dataTable->render('dispute.index');
    }

    /**
     * Action `show`.
     *
     * @param int|string $id Dispute ID to show
     */
    public function show(int|string $id): View {
        $dispute = \App\Models\Dispute::find($id);
        return view('dispute.show', compact('dispute'));
    }

    /**
     * Action `delete`.
     *
     * @param int|string $id      Dispute ID to delete
     * @param Request    $request Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse {
        //
    }
}
