@extends('layouts.master')
@section('content')
    @include('layouts.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    @if (isset($user_info))
                        <h4 class="card-title mb-0 flex-grow-1">Update Staff</h4>
                    @else
                        <h4 class="card-title mb-0 flex-grow-1">Add New Staff</h4>
                    @endif
                    <div class="flex-shrink-0">
                        <a href="{{ route('staffs.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (isset($user_info))
                        <form class="row g-3 needs-validation" id="projectsDetailsForm" novalidate method="POST"
                            action="{{ route('staffs.update', isset($user_info) ? $user_info->id : '') }}">
                            @method('PATCH')
                        @else
                            <form class="row g-3 needs-validation" id="projectsDetailsForm" novalidate method="POST"
                                action="{{ route('staffs.store') }}">
                    @endif
                    @csrf


                    <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <label for="investorName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="investorName" name="name"
                                placeholder="Please enter " value="{{ isset($user_info) ? $user_info->name : old('name') }}"
                                required>
                            <div class="invalid-tooltip">
                                @if ($errors->has('name'))
                                    {{ $errors->first('name') }}
                                @else
                                    Name is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <label for="investorEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif "
                                id="investorEmail" name="email" placeholder="Please enter "
                                value="{{ isset($user_info) ? $user_info->email : old('email') }}" required>
                            <div class="invalid-tooltip">
                                @if ($errors->has('email'))
                                    {{ $errors->first('email') }}
                                @else
                                    Email is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <label for="investorPassword" class="form-label">New Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control @if ($errors->has('password')) is-invalid @endif"
                                id="investorPassword" name="password" placeholder="Please enter "
                                value="{{ old('password') }}" required>
                            <div class="invalid-tooltip">
                                @if ($errors->has('password'))
                                    {{ $errors->first('password') }}
                                @else
                                    Password is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <label for="investorConfirmPassword" class="form-label">Confirm Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control @if ($errors->has('conf_password')) is-invalid @endif"
                                id="investorConfirmPassword" name="conf_password" placeholder="Please enter "
                                value="{{ old('conf_password') }}" required>
                            <div class="invalid-tooltip">
                                @if ($errors->has('conf_password'))
                                    {{ $errors->first('conf_password') }}
                                @else
                                    Confirm Password is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <input type="hidden" class="form-control" id="form_info" name="form_info" value="profile">
                        <button class="btn btn-primary" type="submit">Save Record</button>
                        <a href="{{ route('dashboard') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).on('blur', '#investorConfirmPassword', function(e) {
            var u_p = $('#investorPassword').val();
            var u_c_p = $('#investorConfirmPassword').val();

            if (u_p != u_c_p) {
                alert('Password Miss Match');
                $('#investorConfirmPassword').val('');
                return false;
            }
        });
    </script>
@endpush
