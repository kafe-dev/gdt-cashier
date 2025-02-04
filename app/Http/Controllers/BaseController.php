<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * Class BaseController.
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 *
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController
{
    /**
     * Base constructor.
     */
    public function __construct() {}

    /**
     * Filter by date range.
     *
     * @param $dataTable
     *
     * @return void
     */
    protected function filterDateRange($dataTable) {
        if (!empty($_GET['dateToFilter']) && !empty($_GET['minDate']) && !empty('maxDate')) {
            $columns = $dataTable->getColumns();

            $dataTable->dateToFilter = $columns[$_GET['dateToFilter']]['data'];
            $dataTable->minDate = $_GET['minDate'];
            $dataTable->maxDate = $_GET['maxDate'];
        }
    }
}
