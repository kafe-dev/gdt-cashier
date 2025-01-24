<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Store as StoreModel;
use App\Services\DataTables\UserDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Store.
 *
 * This controller is responsible for managing store-related operations.
 */
class Store extends BaseController
{
    /**
     * @var StoreModel Instance of the Store model
     */
    private StoreModel $storeModel;

    /**
     * Construct a new Store controller instance.
     */
    public function __construct(StoreModel $storeModel)
    {
        parent::__construct();

        $this->storeModel = $storeModel;
    }

    /**
     * Action `index`.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('store.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  The store ID
     */
    public function show(int|string $id): View
    {
        return view('store.show', [
            'store' => $this->getStore($id),
        ]);
    }

    /**
     * Action `create`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function create(Request $request): View|RedirectResponse
    {
        return view('store.create');
    }

    /**
     * Action `update`.
     *
     * @param  int|string  $id  The store ID
     * @param  Request  $request  Illuminate request object
     */
    public function update(int|string $id, Request $request): View|RedirectResponse
    {
        return view('store.update', [
            'store' => $this->getStore($id),
        ]);
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  The store ID
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        if ($request->isMethod('POST')) {
            $store = $this->getStore($id);

            if ($store->delete()) {
                flash()->success('Deleted successfully.');
            }
        }

        return redirect()->route('app.store.index');
    }

    /**
     * Action `store`.
     *
     * Handles the storage of a new store.
     * Handles update store.
     *
     * @param Request $request Illuminate request object
     *
     */

    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Returns the specific store based on the given ID.
     *
     * @param int|string $id Store ID
     */
    private function getStore(int|string $id): StoreModel
    {
        return $this->storeModel->query()->findOrFail($id);
    }
}
