<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Paygate as PaygateModel;
use App\Services\DataTables\PaygateDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Paygate.
 *
 * This controller is responsible for managing paygate-related operations.
 */
class Paygate extends BaseController
{
    /**
     * Action `index`.
     */
    public function index(PaygateDataTable $dataTable)
    {
        $this->filterDateRange($dataTable);

        return $dataTable->render('paygate.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  Paygate ID to be shown
     */
    public function show(int|string $id): View
    {
        return view('paygate.show', [
            'paygate' => PaygateModel::findOrFail($id),
        ]);
    }

    /**
     * Action `create`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function create(): View
    {
        $paygate = new PaygateModel;

        return view('paygate.create', compact('paygate'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'url' => 'required|url',
                'api_data' => 'required|string',
                'vps_data' => 'required|string',
                'type' => 'required|string',
                'limitation' => 'nullable|integer',
                'mode' => 'required|in:0,1',
            ]);
            $attributes = $request->all();
            $attributes['created_at'] = time();
            $attributes['updated_at'] = time();
            $attributes['status'] = PaygateModel::STATUS_ACTIVE; // Set status = active trước khi tạo bản ghi
            PaygateModel::create($attributes);

            return redirect()->route('app.paygate.index'); // Sau khi thêm thành công, quay lại trang danh sách
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Block Paygate.
     */
    public function block(int|string $id): RedirectResponse
    {
        try {
            $paygate = PaygateModel::findOrFail($id);
            $paygate->update(['status' => PaygateModel::STATUS_INACTIVE]);

            return redirect()->route('app.paygate.index');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Unblock Paygate.
     */
    public function unblock(int|string $id): RedirectResponse
    {
        try {
            $paygate = PaygateModel::findOrFail($id);
            $paygate->update(['status' => PaygateModel::STATUS_ACTIVE]);

            return redirect()->route('app.paygate.index');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Action `update`.
     *
     * @param  int|string  $id  Paygate ID to be updated
     * @param  Request  $request  Illuminate request object
     */
    public function update(int|string $id, Request $request): View|RedirectResponse
    {

        $paygate = PaygateModel::findOrFail($id);

        return view('paygate.update', compact('paygate'));
    }

    /**
     * @return RedirectResponse|void
     */
    public function updated(int|string $id, Request $request)
    {
        try {
            $paygate = PaygateModel::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:255',
                'url' => 'required|url',
                'api_data' => 'required|string',
                'vps_data' => 'required|string',
                'type' => 'required|string',
                'limitation' => 'nullable|integer',
                'mode' => 'required|in:0,1',
            ]);
            $attributes = $request->all();
            $paygate->update($attributes);

            return redirect()->route('app.paygate.index'); // Sau khi cập nhật thành công, quay lại trang danh sách
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  Paygate ID to be deleted
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        try {
            $paygate = PaygateModel::findOrFail($id);
            $paygate->delete();

            return redirect()->route('app.paygate.index')->with('success', 'Paygate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('app.paygate.index')->with('error', 'Error deleting Paygate: '.$e->getMessage());
        }
    }

    /**
     * Action `changeStatus`.
     *
     * Change the status of the paygate to active or inactive.
     *
     * @param int|string $id Paygate ID
     * @param Request $request Illuminate request object
     */
    public function changeStatus(Request $request, int|string $id): RedirectResponse
    {
        try {
            $paygate = PaygateModel::findOrFail($id);

            if ($paygate->status == PaygateModel::STATUS_ACTIVE) {
                $paygate->update([
                    'status' => PaygateModel::STATUS_INACTIVE,
                ]);
            } else {
                $paygate->update([
                    'status' => PaygateModel::STATUS_ACTIVE,
                ]);
            }
            flash()->success('Change successfully.');
        } catch (\Exception $e) {
            flash()->error('Had an error while updating the status of the paygate.');

            return redirect()->route('app.paygate.index');
        }

        return redirect()->route('app.paygate.index');
    }
}
