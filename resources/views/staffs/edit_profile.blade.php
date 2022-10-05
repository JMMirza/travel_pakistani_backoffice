@extends('layouts.master')
@section('content')
    @include('layouts.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Update Profile</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('staffs.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="projectsDetailsForm" novalidate method="POST"
                        action="{{ route('staffs.update', $user_info->id) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="investorName" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="investorName" name="name"
                                    placeholder="Please enter "
                                    value="{{ isset($user_info) ? $user_info->name : old('name') }}" required>
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
                                <label for="investorEmail" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control @if ($errors->has('email')) is-invalid @endif "
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
                            <label for="customer_type">
                                Customer Type<span class="text-danger">*</span>
                            </label>
                            <div class="form-label-group in-border">
                                <label class="radio-inline mr-3">
                                    <input type="radio" name="customer_type" value="individual"
                                        @if ($user_info->customer_type == 'individual') checked @endif checked>
                                    <span></span>{{ __('login.individual') }}
                                </label>
                                <label class="radio-inline mr-3">
                                    <input type="radio" name="customer_type" value="corporate"
                                        @if ($user_info->customer_type == 'corporate') checked @endif>
                                    <span></span>{{ __('login.corporate') }}
                                </label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('customer_type'))
                                        {{ $errors->first('customer_type') }}
                                    @else
                                        Customer Type is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Update Record</button>
                            <a href="{{ route('dashboard') }}"
                                class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Update Password</h4>

                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="projectsDetailsForm" novalidate method="POST"
                        action="{{ route('staffs.update', $user_info->id) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="investorPassword" class="form-label">New Password <span
                                        class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @if ($errors->has('password')) is-invalid @endif"
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
                                <label for="password-confirm" class="form-label">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @if ($errors->has('password_confirmation')) is-invalid @endif"
                                    id="password-confirm" name="password_confirmation" placeholder="Please enter " required
                                    autocomplete="new-password">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('password_confirmation'))
                                        {{ $errors->first('password_confirmation') }}
                                    @else
                                        Confirm Password is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Update Record</button>
                            <a href="{{ route('dashboard') }}"
                                class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('header_scripts')
    @endpush

    @push('footer_scripts')
        <script type="text/javascript">
            $(document).on('blur', '#password-confirm', function(e) {
                var u_p = $('#investorPassword').val();
                var u_c_p = $('#password-confirm').val();

                if (u_p != u_c_p) {
                    alert('Password Miss Match');
                    $('#password-confirm').val('');
                    return false;
                }
            });
        </script>
    @endpush
