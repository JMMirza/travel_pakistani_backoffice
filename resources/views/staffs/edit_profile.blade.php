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
                        <input type="hidden" name="user_id" value="{{ $user_info->user->id }}">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="fullName" class="form-label">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    placeholder="Please enter "
                                    value="{{ isset($user_info) ? $user_info->user->name : old('fullName') }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('fullName'))
                                        {{ $errors->first('fullName') }}
                                    @else
                                        Full Name is required!
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
                                    value="{{ isset($user_info) ? $user_info->user->email : old('email') }}" required>
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
                                <label for="username" class="form-label">User Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Please enter "
                                    value="{{ isset($user_info) ? $user_info->user->username : old('username') }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('username'))
                                        {{ $errors->first('username') }}
                                    @else
                                        User Name is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Please enter "
                                    value="{{ isset($user_info) ? $user_info->user->phone : old('phone') }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('phone'))
                                        {{ $errors->first('phone') }}
                                    @else
                                        Phone is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border mb-3">
                                <label for="reportsTo" class="form-label">Reports To</label>
                                <select
                                    class="form-select form-control mb-3 @if ($errors->has('reportsTo')) is-invalid @endif"
                                    name="reportsTo">
                                    <option value="" @if (isset($user_info) ? $user_info->reportsTo : old('reportsTo') == '') {{ 'selected' }} @endif
                                        selected disabled>
                                        Select One
                                    </option>
                                    @foreach ($user->userable->staff as $s)
                                        <option value="{{ $s->user->id }}"
                                            @if (isset($user_info) ? $user_info->reportsTo : old('reportsTo') == $s->user->id) {{ 'selected' }} @endif>
                                            {{ $s->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('reportsTo'))
                                        {{ $errors->first('reportsTo') }}
                                    @else
                                        Select the User!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="image" class="form-label">Image</label>
                                <input type="file"
                                    class="form-control @if ($errors->has('image')) is-invalid @endif" id="image"
                                    name="image" placeholder="Please Enter Account Name" value="{{ old('image') }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('image'))
                                        {{ $errors->first('image') }}
                                    @else
                                        Image is required!
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
                        <input type="hidden" name="user_id" value="{{ $user_info->user->id }}">
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
                                    id="password-confirm" name="password_confirmation" placeholder="Please enter "
                                    required autocomplete="new-password">
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
