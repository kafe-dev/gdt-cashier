@extends('_layouts.main')

@push('page')
    Manage Paypal Transactions
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('manage-paypal-transactions') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Paypal Transaction List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all paypal transactions in the system.
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                    <input type="text" id="daterange_input" class="form-control d-none">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function() {
            $('#daterange_input').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Export',
                    cancelLabel: 'Cancel'
                }
            });

            $('#daterange_input').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');

                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("app.paypal-transaction.export") }}';

                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                var startDateInput = document.createElement('input');
                startDateInput.type = 'hidden';
                startDateInput.name = 'start_date';
                startDateInput.value = startDate;
                form.appendChild(startDateInput);

                var endDateInput = document.createElement('input');
                endDateInput.type = 'hidden';
                endDateInput.name = 'end_date';
                endDateInput.value = endDate;
                form.appendChild(endDateInput);

                document.body.appendChild(form);
                form.submit();

                $.ajax({
                    type: 'POST',
                    url: form.action,
                    data: $(form).serialize(),
                    success: function(response) {
                        alert('Exported successfully');
                        $('#reset-btn').click();
                    },
                    error: function() {
                    }
                });
            });
        });
    </script>
@endpush
