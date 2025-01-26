<?php

declare(strict_types=1);

namespace App\Models\Filters;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class StoreFilter.
 *
 * This class represents a filter for stores.
 */
class StoreFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {
        return $dataTable
            ->searchPane(
                'user_id',
                fn () => self::filterUserId(),
            )
            ->searchPane(
                'name',
                fn () => self::filterName(),
            )
            ->searchPane(
                'url',
                fn () => self::filterUrl(),
            )
            ->searchPane(
                'description',
                fn () => self::filterDescription(),
            )
            ->searchPane(
                'status',
                fn () => self::filterStatus(),
            )
            ->searchPane(
                'created_at',
                fn () => self::filterByCreatedAt(),
            )
            ->searchPane(
                'updated_at',
                fn () => self::filterByUpdatedAt(),
            );
    }

    /**
     * Filter stores by user id.
     */
    private static function filterUserId(): Collection
    {
        return Store::query()
            ->select(DB::raw('`user_id` as value, `user_id` as label, COUNT(*) as total'))
            ->groupBy('user_id')
            ->get();
    }

    /**
     * Filter stores by name.
     */
    private static function filterName(): Collection
    {
        return Store::query()
            ->select(DB::raw('`name` as value, `name` as label, COUNT(*) as total'))
            ->groupBy('name')
            ->get();
    }

    /**
     * Filter stores by url.
     */
    private static function filterUrl(): Collection
    {
        return Store::query()
            ->select(DB::raw('`url` as value, `url` as label, COUNT(*) as total'))
            ->groupBy('url')
            ->get();
    }

    /**
     * Filter stores by description.
     */
    private static function filterDescription(): Collection
    {
        return Store::query()
            ->select(DB::raw('`description` as value, `description` as label, COUNT(*) as total'))
            ->groupBy('description')
            ->get();
    }

    /**
     * Filter stores by status.
     */
    private static function filterStatus(): array
    {
        $collection = [];

        foreach (Store::STATUSES as $key => $value) {
            $data['value'] = $key;
            $data['label'] = $value;
            $data['total'] = Store::query()->where('status', $key)->count();

            $collection[] = $data;
        }

        return $collection;
    }

    /**
     * Filter stores by created at.
     */
    private static function filterByCreatedAt(): Collection
    {
        return Store::query()
            ->select(DB::raw('`created_at` as value, `created_at` as label'))
            ->get();
    }

    /**
     * Filter stores by updated at.
     */
    private static function filterByUpdatedAt(): Collection
    {
        return Store::query()
            ->select(DB::raw('`updated_at` as value, `updated_at` as label'))
            ->get();
    }
}
