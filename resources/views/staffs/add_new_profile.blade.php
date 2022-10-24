@extends('layouts.master')
@section('content')
    @include('layouts.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Add New Staff</h4>

                    <div class="flex-shrink-0">
                        <a href="{{ route('staffs.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="projectsDetailsForm" novalidate method="POST"
                        enctype='multipart/form-data' action="{{ route('staffs.store') }}">
                        @csrf

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="fullName" class="form-label">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    placeholder="Please enter " value="{{ old('fullName') }}" required>
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
                                <label for="username" class="form-label">User Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Please enter "
                                    value="{{ isset($user_info) ? $user_info->username : old('username') }}" required>
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
                                    value="{{ isset($user_info) ? $user_info->phone : old('phone') }}" required>
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
                                    <option value="" @if (old('reportsTo') == '') {{ 'selected' }} @endif
                                        selected disabled>
                                        Select One
                                    </option>
                                    @foreach ($data as $user)
                                        <option value="{{ $user->id }}"
                                            @if (old('reportsTo') == $user->id) {{ 'selected' }} @endif>
                                            {{ $user->user }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('reportsTo'))
                                        {{ $errors->first('reportsTo') }}
                                    @else
                                        Select the City!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="image" class="form-label">Image</label>
                                <input type="file"
                                    class="form-control @if ($errors->has('image')) is-invalid @endif" id="image"
                                    name="image" placeholder="Please Enter Account Name" value="{{ old('image') }}"
                                    required>
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
    {{-- <script type="text/javascript">
        $(document).on('blur', '#password-confirm', function(e) {
            var u_p = $('#investorPassword').val();
            var u_c_p = $('#password-confirm').val();

            if (u_p != u_c_p) {
                alert('Password Miss Match');
                $('#password-confirm').val('');
                return false;
            }
        });
    </script> --}}
@endpush
