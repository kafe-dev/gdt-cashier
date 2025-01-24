<?php

/**
 * @project gdt-cashier
 *
 * @author hoepjhsha
 *
 * @email hiepnguyen3624@gmail.com
 *
 * @date 24/01/2025
 *
 * @time 21:28
 */

namespace App\Services\TrackingMore;

class ErrorMessages
{
    const string ErrEmptyAPIKey = 'API Key is missing';

    const string ErrMissingTrackingNumber = 'Tracking number cannot be empty';

    const string ErrMissingCourierCode = 'Courier Code cannot be empty';

    const string ErrMissingAwbNumber = 'Awb number cannot be empty';

    const string ErrMaxTrackingNumbersExceeded = 'Max. 40 tracking numbers create in one call';

    const string ErrEmptyId = 'Id cannot be empty';

    const string ErrInvalidAirWaybillFormat = 'The air waybill number format is invalid';
}
