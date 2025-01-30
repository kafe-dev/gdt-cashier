@extends('_layouts.main')

@push('page')
    Change Password
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('change-password') }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Change Password</h4>
                    <p class="text-muted mb-0">Update your password by filling out the form below.</p>
                    <p class="text-muted mb-0">Field with the (<span class="text-danger">*</span>) is required.</p>
                </div>
                <form action="{{ route('app.user.changePassword')}}"
                      method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="current_password">Current Password <span class="text-danger">*</span></label>
                            <input type="password" minlength="8" class="form-control" id="current_password"
                                   name="current_password"
                                   value="" required placeholder="Enter current password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_password">New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                   value="" required placeholder="Enter new password" minlength="8" maxlength="100"
                                   pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,100}$">
                            <small id="passwordHelp" class="form-text text-muted">Your password must be at least 8
                                characters long and include uppercase, lowercase, numbers, and symbols.<br>Never share
                                your password with anyone else.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="confirm_password">Confirm New Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                   value="" required placeholder="Confirm new password" minlength="8" maxlength="100"
                                   pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,100}$">
                            <small id="passwordHelp" class="form-text text-muted">Re-enter your new password to
                                confirm.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        {{ \App\Utils\ActionWidget::renderGoBackBtn('Cancel', 'btn btn-danger') }}
                    </div>
                </form>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        let newPassword = document.getElementById("new_password");
        newPassword.addEventListener("input", function (e) {
            newPassword.setCustomValidity('');
        });
        newPassword.addEventListener("invalid", function (e) {
            newPassword.setCustomValidity('Your password must be at least 8 characters long and include uppercase, lowercase, numbers, and symbols.');
        });

        // Custom validation for confirm_password field
        let confirmPassword = document.getElementById("confirm_password");
        confirmPassword.addEventListener("input", function (e) {
            confirmPassword.setCustomValidity('');
        });
        confirmPassword.addEventListener("invalid", function (e) {
            confirmPassword.setCustomValidity('The password confirmation does not match the new password.');
        });
    </script>
@endpush
