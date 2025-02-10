<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Store as StoreModel;
use App\Models\User as UserModel;
use App\Services\DataTables\StoreDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Automattic\WooCommerce\Client;

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
    public function index(StoreDataTable $dataTable)
    {
        $this->filterDateRange($dataTable);

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
            'user' => UserModel::query()->where('id', $this->getStore($id)->user_id)->first(['username', 'email']),
        ]);
    }

    /**
     * Action `create`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function create(Request $request): View|RedirectResponse
    {
        return view('store.create', [
            'users' => UserModel::query()->select('id', 'username', 'email')->get()->toArray(),
        ]);
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
            'users' => UserModel::query()->select('id', 'username', 'email')->get()->toArray(),
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
     * @param  Request  $request  Illuminate request object
     */
    public function store(Request $request, ?int $id = null): RedirectResponse
    {
        try {
            $this->validateStoreData($request);

            if ($request->input('api_data')) {
                if (! json_validate($request->input('api_data'))) {
                    flash()->error('The API data you entered is invalid.');

                    return redirect()->route('app.store.create');
                }
            }
            $data = $this->getData($request);
            if (! $id) {
                $this->storeModel->create($data);
                flash()->success('Store created successfully.');
            } else {
                $store = $this->getStore($id);
                $store->update($data);
                flash()->success('Store updated successfully.');
            }
        } catch (\Exception $e) {
            flash()->error('The entered information is invalid.');
            if (! $id) {
                return redirect()->route('app.store.create');
            } else {
                return redirect()->route('app.store.update', ['id' => $id]);
            }
        }

        return redirect()->route('app.store.index');
    }

    /**
     * Action `changeStatus`.
     *
     * Change the status of the user to active or inactive.
     *
     * @param  int|string  $id  User ID
     * @param  Request  $request  Illuminate request object
     */
    public function changeStatus(Request $request, int|string $id): RedirectResponse
    {
        try {
            $user = $this->getStore($id);

            if ($user->status == StoreModel::STATUS_ACTIVE) {
                $user->update([
                    'status' => StoreModel::STATUS_INACTIVE,
                ]);
            } else {
                $user->update([
                    'status' => StoreModel::STATUS_ACTIVE,
                ]);
            }
        } catch (\Exception $e) {
            flash()->error('Had an error while updating the status of the store');

            return redirect()->route('app.user.index');
        }

        return redirect()->route('app.store.index');
    }

    public function testConnection(Request $request, int|string $id): RedirectResponse
    {
        $store = $this->getStore($id);
        $storeUrl = $store->url;
        $api_data= json_decode($store->api_data, true);
        $consumerKey = $api_data['consume_key'];
        $consumerSecret = $api_data['consume_secret'];

        try {
            $woocommerce = new Client(
                $storeUrl,
                $consumerKey,
                $consumerSecret,
                [
                    'version' => 'wc/v3',
                ]
            );

            $response = $woocommerce->get('system_status');

            flash()->success("Connection successful!");

        } catch (\Exception $e) {
            flash()->error("Connection failed!");
        }
        return redirect()->route('app.store.index');
    }

    /**
     * Returns the specific store based on the given ID.
     *
     * @param  int|string  $id  Store ID
     */
    private function getStore(int|string $id): StoreModel
    {
        return $this->storeModel->query()->findOrFail($id);
    }

    /**
     * Validates the store data before storage.
     *
     * @param  Request  $request  Illuminate request object
     */
    private function validateStoreData(Request $request): void
    {
        $request->validate([
            'name' => 'required|string|max:50|regex:/^[a-zA-Z0-9_]+(?: [a-zA-Z0-9_]+)*$/',
            'url' => 'required|url',
            'description' => 'nullable|string|max:500|regex:/^[a-zA-Z0-9!@#$%^&*()_+ ]*$/',
            'api_data' => 'nullable|string',
        ]);
    }

    /**
     * Return all the store data.
     */
    private function getData(Request $request): array
    {
        return [
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'description' => $request->input('description'),
            'api_data' => $request->input('api_data'),
        ];
    }

}
