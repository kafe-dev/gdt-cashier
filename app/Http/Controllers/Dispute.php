<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {
        $query = \App\Models\Dispute::query();

        // Thêm điều kiện tìm kiếm
        if ($request->filled('dispute_id')) {
            $query->where('dispute_id', 'like', '%' . $request->dispute_id . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('merchant_id')) {
            $query->where('merchant_id', $request->merchant_id);
        }
        if ($request->filled('reason')) {
            $query->where('reason', 'like', '%' . $request->reason . '%');
        }
        if ($request->filled('create_date_range') && str_contains($request->create_date_range, ' - ')) {
            [$startDate, $endDate] = explode(' - ', $request->create_date_range);
            $query->whereBetween('created_at', [
                trim($startDate) . ' 00:00:00',
                trim($endDate) . ' 23:59:59'
            ]);
        }



        // Phân trang với 20 bản ghi mỗi trang
        $disputes = $query->paginate(20);

        return view('dispute.index', compact('disputes'));
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
