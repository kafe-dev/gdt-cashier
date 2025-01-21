<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Paygate as PaygateModel;
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
    public function index()
    {
        $paygates = PaygateModel::all();

        return view('paygate.index', compact('paygates'));
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  Paygate ID to be shown
     */
    public function show(int|string $id): View
    {
        return view('paygate.show');
    }

    /**
     * Action `create`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function create(): View
    {
        return view('paygate.create');
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
            $attributes['status'] = PaygateModel::STAUTS_ACTIVE; // Set status = active trước khi tạo bản ghi
            PaygateModel::create($attributes);

            return redirect()->route('app.paygate.index'); // Sau khi thêm thành công, quay lại trang danh sách
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
        return view('paygate.update');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  Paygate ID to be deleted
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
