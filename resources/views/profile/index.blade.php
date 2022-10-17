@extends('layouts.master')

@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ asset('theme/dist/default/assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">

        </div>
    </div>
    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ asset('theme/dist/default/assets/images/users/avatar-1.jpg') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-0">{{ Auth::user()->userable_type }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Personal Info</h5>
                        </div>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-dark text-light">
                                <i class="ri-mail-fill"></i>
                            </span>
                        </div>
                        <input type="email" class="form-control" id="gitUsername" placeholder="Username"
                            value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-primary">
                                <i class="ri-phone-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="websiteInput" placeholder="www.example.com"
                            value="{{ Auth::user()->phone }}" readonly>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-success">
                                <i class="ri-map-pin-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="dribbleName" placeholder="Username"
                            value="{{ Auth::user()->city->title }}" readonly>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#experience" role="tab">
                                <i class="far fa-envelope"></i>
                                Payment Method
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#business_info" role="tab">
                                <i class="far fa-envelope"></i>
                                Business Information
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="javascript:void(0);">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-label-group in-border mb-3">
                                            <label for="username" class="form-label">User
                                                Name</label>
                                            <input type="text"
                                                class="form-control @if ($errors->has('username')) is-invalid @endif"
                                                id="username" name="username" placeholder="Please Enter"
                                                value="{{ Auth::user()->username }}">
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('username'))
                                                    {{ $errors->first('username') }}
                                                @else
                                                    User Name is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="form-label-group in-border mb-3">
                                            <label for="fullnameInput" class="form-label">Full
                                                Name</label>
                                            <input type="text"
                                                class="form-control @if ($errors->has('name')) is-invalid @endif"
                                                id="name" name="name" placeholder="Please Enter"
                                                value="{{ Auth::user()->username }}">
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('name'))
                                                    {{ $errors->first('name') }}
                                                @else
                                                    Name is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="form-label-group in-border mb-3">
                                            <label for="phoneNumber" class="form-label">Phone Number</label>
                                            <input type="text"
                                                class="form-control @if ($errors->has('phone')) is-invalid @endif"
                                                id="phone" name="phone" placeholder="Please Enter"
                                                value="{{ Auth::user()->phone }}">
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('phone'))
                                                    {{ $errors->first('phone') }}
                                                @else
                                                    Phone Number is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="form-label-group in-border mb-3">
                                            <label for="phoneNumber" class="form-label">Email</label>
                                            <input type="text"
                                                class="form-control @if ($errors->has('email')) is-invalid @endif"
                                                id="email" name="email" placeholder="Please Enter"
                                                value="{{ Auth::user()->email }}">
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('email'))
                                                    {{ $errors->first('email') }}
                                                @else
                                                    Email is required!
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        {{-- <label for="cityInput" class="form-label">City</label> --}}
                                        <div class="form-label-group in-border mb-3">
                                            <label for="cityId" class="form-label">City</label>
                                            <select
                                                class="form-select form-control mb-3 @if ($errors->has('cityId')) is-invalid @endif"
                                                name="cityId">
                                                <option value=""
                                                    @if (Auth::user()->cityId == '') {{ 'selected' }} @endif selected
                                                    disabled>
                                                    Select One
                                                </option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->city_id }}"
                                                        @if (Auth::user()->cityId == $city->city_id) {{ 'selected' }} @endif>
                                                        {{ $city->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-tooltip">
                                                @if ($errors->has('cityId'))
                                                    {{ $errors->first('cityId') }}
                                                @else
                                                    Select the City!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Updates</button>
                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="javascript:void(0);">
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Old
                                                Password*</label>
                                            <input type="password" class="form-control" id="oldpasswordInput"
                                                name="oldPassword" placeholder="Enter current password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New
                                                Password*</label>
                                            <input type="password" class="form-control" id="newpasswordInput"
                                                name="nPassword" placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                            <input type="password" class="form-control" id="confirmpasswordInput"
                                                name="cPassword" placeholder="Confirm password">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Change
                                                Password</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                            <div class="mt-4 mb-3 border-bottom pb-2">
                                <div class="float-end">
                                    <a href="javascript:void(0);" class="link-primary">All
                                        Logout</a>
                                </div>
                                <h5 class="card-title">Login History</h5>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 avatar-sm">
                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">
                                        <i class="ri-smartphone-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>iPhone 12 Pro</h6>
                                    <p class="text-muted mb-0">Los Angeles, United States - March 16
                                        at 2:47PM</p>
                                </div>
                                <div>
                                    <a href="javascript:void(0);">Logout</a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 avatar-sm">
                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">
                                        <i class="ri-tablet-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Apple iPad Pro</h6>
                                    <p class="text-muted mb-0">Washington, United States - November 06
                                        at 10:43AM</p>
                                </div>
                                <div>
                                    <a href="javascript:void(0);">Logout</a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 avatar-sm">
                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">
                                        <i class="ri-smartphone-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Galaxy S21 Ultra 5G</h6>
                                    <p class="text-muted mb-0">Conneticut, United States - June 12 at
                                        3:24PM</p>
                                </div>
                                <div>
                                    <a href="javascript:void(0);">Logout</a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 avatar-sm">
                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">
                                        <i class="ri-macbook-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Dell Inspiron 14</h6>
                                    <p class="text-muted mb-0">Phoenix, United States - July 26 at
                                        8:10AM</p>
                                </div>
                                <div>
                                    <a href="javascript:void(0);">Logout</a>
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="experience" role="tabpanel">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Bank Details</h4>
                                {{-- @permission('add-course') --}}
                                <div class="flex-shrink-0">
                                    <a href="{{ route('itinerary-templates.create') }}"
                                        class="btn btn-success btn-label btn-sm">
                                        <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                                    </a>
                                </div>
                                {{-- @endpermission --}}
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form>
                                        <table id="payment-mode-data-table"
                                            class="table table-bordered table-striped align-middle table-nowrap "
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Bank Title</th>
                                                    <th>Account No</th>
                                                    <th>Account Title</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($bank_details as $bank_detail)
                                                    <tr>
                                                        <td>{{ $bank_detail->bankName }}</td>
                                                        <td>{{ $bank_detail->accountNo }}
                                                        </td>
                                                        <td>{{ $bank_detail->accountTitle }}</td>
                                                        <td><a href=""
                                                                class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                                                <i class="mdi mdi-lead-pencil"></i>
                                                            </a>
                                                            <a href="" data-table="payment-mode-data-table"
                                                                class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
                                                                <i class="ri-delete-bin-5-line"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4">No Record Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="business_info" role="tabpanel">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Bank Details</h4>
                                {{-- @permission('add-course') --}}
                                <div class="flex-shrink-0">
                                    <a href="{{ route('itinerary-templates.create') }}"
                                        class="btn btn-success btn-label btn-sm">
                                        <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                                    </a>
                                </div>
                                {{-- @endpermission --}}
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form action="javascript:void(0);">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="companyTitle" class="form-label">Company Title</label>
                                                    <input type="text"
                                                        class="form-control @if ($errors->has('companyTitle')) is-invalid @endif"
                                                        id="companyTitle" name="companyTitle" placeholder="Please Enter"
                                                        value="{{ Auth::user()->companyTitle }}">
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('companyTitle'))
                                                            {{ $errors->first('companyTitle') }}
                                                        @else
                                                            Company Title is required!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                {{-- <label for="cityInput" class="form-label">City</label> --}}
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="cityId" class="form-label">City</label>
                                                    <select
                                                        class="form-select form-control mb-3 @if ($errors->has('cityId')) is-invalid @endif"
                                                        name="cityId">
                                                        <option value=""
                                                            @if (Auth::user()->cityId == '') {{ 'selected' }} @endif
                                                            selected disabled>
                                                            Select One
                                                        </option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->city_id }}"
                                                                @if (Auth::user()->cityId == $city->city_id) {{ 'selected' }} @endif>
                                                                {{ $city->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('cityId'))
                                                            {{ $errors->first('cityId') }}
                                                        @else
                                                            Select the City!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="companyAddress" class="form-label">Address</label>
                                                    <input type="text"
                                                        class="form-control @if ($errors->has('companyAddress')) is-invalid @endif"
                                                        id="companyAddress" name="companyAddress"
                                                        placeholder="Please Enter"
                                                        value="{{ Auth::user()->companyAddress }}">
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('companyAddress'))
                                                            {{ $errors->first('companyAddress') }}
                                                        @else
                                                            Address is required!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="contactNumber" class="form-label">Phone Number</label>
                                                    <input type="text"
                                                        class="form-control @if ($errors->has('contactNumber')) is-invalid @endif"
                                                        id="contactNumber" name="contactNumber"
                                                        placeholder="Please Enter"
                                                        value="{{ Auth::user()->contactNumber }}">
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('contactNumber'))
                                                            {{ $errors->first('contactNumber') }}
                                                        @else
                                                            Phone Number is required!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="businessEmail" class="form-label">Email</label>
                                                    <input type="text"
                                                        class="form-control @if ($errors->has('businessEmail')) is-invalid @endif"
                                                        id="businessEmail" name="businessEmail"
                                                        placeholder="Please Enter"
                                                        value="{{ Auth::user()->businessEmail }}">
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('businessEmail'))
                                                            {{ $errors->first('businessEmail') }}
                                                        @else
                                                            Email is required!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="contactPerson" class="form-label">Contact Person</label>
                                                    <input type="text"
                                                        class="form-control @if ($errors->has('contactPerson')) is-invalid @endif"
                                                        id="contactPerson" name="contactPerson"
                                                        placeholder="Please Enter"
                                                        value="{{ Auth::user()->contactPerson }}">
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('contactPerson'))
                                                            {{ $errors->first('contactPerson') }}
                                                        @else
                                                            Contact Person is required!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                {{-- <label for="cityInput" class="form-label">City</label> --}}
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="businessType" class="form-label">Business Type</label>
                                                    <select
                                                        class="form-select form-control mb-3 @if ($errors->has('businessType')) is-invalid @endif"
                                                        name="businessType">
                                                        <option
                                                            @if (Auth::user()->businessType == '') {{ 'selected' }} @endif
                                                            value="">Select</option>
                                                        <option
                                                            @if (Auth::user()->businessType == '0') {{ 'selected' }} @endif
                                                            value="0">FIT and Small GROUP Business</option>
                                                        <option
                                                            @if (Auth::user()->businessType == '1') {{ 'selected' }} @endif
                                                            value="1">Mainly MICE Business</option>
                                                        <option
                                                            @if (Auth::user()->businessType == '2') {{ 'selected' }} @endif
                                                            value="2">Mainly Group Business</option>
                                                    </select>
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('businessType'))
                                                            {{ $errors->first('businessType') }}
                                                        @else
                                                            Select the Business Type!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                {{-- <label for="cityInput" class="form-label">City</label> --}}
                                                <div class="form-label-group in-border mb-3">
                                                    <label for="typeDescription" class="form-label">Description</label>
                                                    <select
                                                        class="form-select form-control mb-3 @if ($errors->has('typeDescription')) is-invalid @endif"
                                                        name="typeDescription">
                                                        <option
                                                            @if (Auth::user()->typeDescription == '') {{ 'selected' }} @endif
                                                            value="">Select</option>
                                                        <option
                                                            @if (Auth::user()->typeDescription == '1') {{ 'selected' }} @endif
                                                            value="1">Tour Operator mainly focusing on Inbound
                                                        </option>
                                                        <option
                                                            @if (Auth::user()->typeDescription == '2') {{ 'selected' }} @endif
                                                            value="2">Travel Agent mainly focusing on Outbound
                                                        </option>
                                                    </select>
                                                    <div class="invalid-tooltip">
                                                        @if ($errors->has('businessType'))
                                                            {{ $errors->first('businessType') }}
                                                        @else
                                                            Select the Description!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-sm-12 mb-3">
                                                <label for="about" class="form-label">About</label>
                                                <div id="snow-editor" style="height: 300px;">{!! Auth::user()->about !!}</div>
                                                <input type="hidden" name="about" id="about"
                                                    value="{{ Auth::user()->about }}">
                                                {{-- <div class="form-label-group in-border">
                                    <label for="description" class="form-label">Description (物品描述)</label>
                                    <textarea class="form-control mb-3" name="description" id="description" placeholder="Enter product description here...">{{ $paymentMethod->description') }}</textarea>
                                </div> --}}
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Updates</button>
                                                    <button type="button" class="btn btn-soft-success">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
@endpush
