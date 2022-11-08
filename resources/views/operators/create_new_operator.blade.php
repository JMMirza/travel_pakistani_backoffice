@extends('layouts.master')

@push('header_scripts')
    <link href="{{ asset('theme/dist/default/assets/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/dist/default/assets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    {{-- <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ asset('theme/dist/default/assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">

        </div>
    </div> --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 flex-grow-1">Create Operator</h4>
                </div>
                <div class="card-body p-4">
                    {{-- <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
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
                    </div> --}}
                    <form class="row g-3 needs-validation" action="{{ route('create-new-operator') }}" method="POST"
                        novalidate>
                        @csrf
                        {{-- <div class="row"> --}}

                        <div class="col-lg-4">
                            <div class="form-label-group in-border mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                                    name="name" placeholder="Please Enter" value="{{ old('name') }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('name'))
                                        {{ $errors->first('name') }}
                                    @else
                                        Name is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-label-group in-border mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('phone')) is-invalid @endif" id="phone"
                                    name="phone" placeholder="Please Enter" value="{{ old('phone') }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('phone'))
                                        {{ $errors->first('phone') }}
                                    @else
                                        Phone Number is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-label-group in-border mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('email')) is-invalid @endif" id="email"
                                    name="email" placeholder="Please Enter" value="{{ old('email') }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @else
                                        Email is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-label-group in-border mb-3">
                                <label for="companyTitle" class="form-label">Company Title</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('companyTitle')) is-invalid @endif"
                                    id="companyTitle" name="companyTitle" placeholder="Please Enter"
                                    value="{{ old('companyTitle') }}">
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
                            {{-- <label for"cityInput" class="form-label">City</label> --}}
                            <div class="form-label-group in-border mb-3">
                                <label for="cityId" class="form-label">City</label>
                                <select
                                    class="form-select form-control mb-3 @if ($errors->has('cityId')) is-invalid @endif"
                                    name="cityId">
                                    <option value="" @if (old('cityId') == '') {{ 'selected' }} @endif
                                        selected disabled>
                                        Select One
                                    </option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->city_id }}"
                                            @if (old('cityId') == $city->city_id) {{ 'selected' }} @endif>
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
                                <label for="contactPerson" class="form-label">Contact Person</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('contactPerson')) is-invalid @endif"
                                    id="contactPerson" name="contactPerson" placeholder="Please Enter"
                                    value="{{ old('contactPerson') }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('contactPerson'))
                                        {{ $errors->first('contactPerson') }}
                                    @else
                                        Contact Person is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-label-group in-border mb-3">
                                <label for="businessEmail" class="form-label">Business Email</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('businessEmail')) is-invalid @endif"
                                    id="businessEmail" name="businessEmail" placeholder="Please Enter"
                                    value="{{ old('businessEmail') }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('businessEmail'))
                                        {{ $errors->first('businessEmail') }}
                                    @else
                                        Business Email is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            {{-- <label for="cityInput" class="form-label">City</label> --}}
                            <div class="form-label-group in-border mb-3">
                                <label for="businessType" class="form-label">Business Type</label>
                                <select
                                    class="form-select form-control mb-3 @if ($errors->has('businessType')) is-invalid @endif"
                                    name="businessType">
                                    <option @if (old('businessType') == '') {{ 'selected' }} @endif value="">
                                        Select</option>
                                    <option @if (old('businessType') == '0') {{ 'selected' }} @endif value="0">
                                        FIT and Small GROUP Business</option>
                                    <option @if (old('businessType') == '1') {{ 'selected' }} @endif value="1">
                                        Mainly MICE Business</option>
                                    <option @if (old('businessType') == '2') {{ 'selected' }} @endif value="2">
                                        Mainly Group Business</option>
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

                        <div class="col-lg-4">
                            {{-- <label for="cityInput" class="form-label">City</label> --}}
                            <div class="form-label-group in-border mb-3">
                                <label for="typeDescription" class="form-label">Description</label>
                                <select
                                    class="form-select form-control mb-3 @if ($errors->has('typeDescription')) is-invalid @endif"
                                    name="typeDescription">
                                    <option @if (old('typeDescription') == '') {{ 'selected' }} @endif value="">
                                        Select</option>
                                    <option @if (old('typeDescription') == '1') {{ 'selected' }} @endif value="1">
                                        Tour Operator mainly focusing on Inbound</option>
                                    <option @if (old('typeDescription') == '2') {{ 'selected' }} @endif value="2">
                                        Travel Agent mainly focusing on Outbound</option>
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
                        <div class="col-lg-12">
                            <div class="form-label-group in-border mb-3">
                                <label for="companyAddress" class="form-label">Address</label>
                                <textarea type="text" class="form-control @if ($errors->has('companyAddress')) is-invalid @endif"
                                    id="companyAddress" name="companyAddress" placeholder="Please Enter">{{ old('companyAddress') }}</textarea>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('companyAddress'))
                                        {{ $errors->first('companyAddress') }}
                                    @else
                                        Address is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label for="operatorAbout" class="form-label">About</label>
                            <div id="snow-editor" style="height: 300px;">{!! old('operatorAbout') !!}</div>
                            <input type="hidden" name="operatorAbout" id="operatorAbout"
                                value="{{ old('operatorAbout') }}">
                            {{-- <div class="form-label-group in-border">
                                    <label for="description" class="form-label">Description (物品描述)</label>
                                    <textarea class="form-control mb-3" name="description" id="description" placeholder="Enter product description here...">{{ $paymentMethod->description') }}</textarea>
                                </div> --}}
                        </div>

                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-soft-success">Cancel</button>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('footer_scripts')
    <script src="{{ asset('theme/dist/default/assets/libs/quill/quill.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('modules/operators.js') }}"></script>
@endpush
