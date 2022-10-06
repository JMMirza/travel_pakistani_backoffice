@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Update Inquiry</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('inquiries.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="bx bx-arrow-back label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row  needs-validation" action="{{ route('inquiries.update', $inquiry->id) }}"
                        method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')


                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="name" class="form-label">Name</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                                    name="name" placeholder="Please Enter" value="{{ $inquiry->name }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('name'))
                                        {{ $errors->first('name') }}
                                    @else
                                        Name is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                    class="form-control @if ($errors->has('email')) is-invalid @endif" id="email"
                                    name="email" placeholder="Please Enter" value="{{ $inquiry->email }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @else
                                        Email is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="contactNo" class="form-label">Phone</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('contactNo')) is-invalid @endif" id="contactNo"
                                    name="contactNo" placeholder="Please Enter" value="{{ $inquiry->contactNo }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('contactNo'))
                                        {{ $errors->first('contactNo') }}
                                    @else
                                        Phone Number is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="cityId" class="form-label">City</label>
                                <select
                                    class="form-select form-control mb-3 @if ($errors->has('cityId')) is-invalid @endif"
                                    name="cityId" required>
                                    <option value="" @if ($inquiry->cityId == '') {{ 'selected' }} @endif
                                        selected disabled>
                                        Select One
                                    </option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            @if ($inquiry->cityId == $city->id) {{ 'selected' }} @endif>
                                            {{ $city->name }}
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

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="source" class="form-label">Source</label>
                                <select name="source" id="source"
                                    class="form-control form-select @if ($errors->has('source')) is-invalid @endif">
                                    <option @if ($inquiry->source == '') {{ 'selected' }} @endif value=""
                                        selected>Select Source...</option>
                                    <option value="Direct Call"
                                        @if ($inquiry->source == 'Direct Call') {{ 'selected' }} @endif>Direct Call</option>
                                    <option value="Desktop" @if ($inquiry->source == 'Desktop') {{ 'selected' }} @endif>
                                        Desktop</option>
                                    <option value="B2C Website"
                                        @if ($inquiry->source == 'B2C Website') {{ 'selected' }} @endif>B2C Website</option>
                                    <option value="Chat" @if ($inquiry->source == 'Chat') {{ 'selected' }} @endif>
                                        Chat</option>
                                    <option value="Message" @if ($inquiry->source == 'Message') {{ 'selected' }} @endif>
                                        Message</option>
                                </select>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('source'))
                                        {{ $errors->first('source') }}
                                    @else
                                        Select the Source!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="tourDates" class="form-label">Preferred Date</label>
                                <input class="form-control  @if ($errors->has('tourDates')) is-invalid @endif"
                                    name="tourDates" id="tourDates" placeholder="Please Enter"
                                    value="{{ $inquiry->tourDates }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('tourDates'))
                                        {{ $errors->first('tourDates') }}
                                    @else
                                        Select the Dates!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="cities" class="form-label">Cities To Visit</label>
                                <select class="form-select @if ($errors->has('cities')) is-invalid @endif select2"
                                    id="cities" name="cities[]" multiple>
                                    <option value="">Please select</option>

                                </select>
                                <div class="invalid-tooltip">Cities is required!</div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="cities" class="form-label">Other Areas</label>
                                <tags class="tagify form-control form-control-solid tagify--noTags tagify--empty"
                                    tabindex="-1">
                                    <span contenteditable="" data-placeholder="&ZeroWidthSpace;" aria-placeholder=""
                                        class="tagify__input" role="textbox" aria-autocomplete="both"
                                        aria-multiline="false"></span>
                                </tags><input id="otherAreas" class="form-control form-control-solid tagify"
                                    name="otherAreas" value="">
                                <div class="invalid-tooltip">Cities is required!</div>
                            </div>
                        </div> --}}

                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="adults" class="form-label">Adults</label>
                                <input type="number"
                                    class="form-control @if ($errors->has('adults')) is-invalid @endif"
                                    id="adults" name="adults" placeholder="Please Enter" value="{{ $inquiry->adults }}"
                                    required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('adults'))
                                        {{ $errors->first('adults') }}
                                    @else
                                        Adults is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="children" class="form-label">Childern</label>
                                <input type="number"
                                    class="form-control @if ($errors->has('children')) is-invalid @endif"
                                    id="children" name="children" placeholder="Please Enter"
                                    value="{{ $inquiry->children }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('children'))
                                        {{ $errors->first('children') }}
                                    @else
                                        Children is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 mb-3">
                            <label class="form-check-label" for="hotel">Hotel</label>
                            <div
                                class="form-label-group in-border form-check form-switch form-switch-custom form-switch-secondary">
                                <input class="form-check-input" type="checkbox" role="switch" id="hotel"
                                    name="hotel" checked required
                                    @if ($inquiry->hotel == 'on') {{ 'checked' }} @endif>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 mb-3">
                            <label class="form-check-label" for="meal">Meal</label>
                            <div
                                class="form-label-group in-border form-check form-switch form-switch-custom form-switch-secondary">
                                <input class="form-check-input" type="checkbox" role="switch" id="meal"
                                    name="meal" @if ($inquiry->meal == 'on') {{ 'checked' }} @endif>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 mb-3">
                            <label class="form-check-label" for="transport">Transport</label>
                            <div
                                class="form-label-group in-border form-check form-switch form-switch-custom form-switch-secondary">
                                <input class="form-check-input" type="checkbox" role="switch" id="transport"
                                    name="transport" @if ($inquiry->transport == 'on') {{ 'checked' }} @endif>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 mb-3">
                            <label class="form-check-label" for="activities">Activities</label>
                            <div
                                class="form-label-group in-border form-check form-switch form-switch-custom form-switch-secondary">
                                <input class="form-check-input" type="checkbox" role="switch" id="activities"
                                    name="activities" @if ($inquiry->activities == 'on') {{ 'checked' }} @endif>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="processedByName" class="form-label">Assigned To</label>
                                <select
                                    class="form-select form-control mb-3 @if ($errors->has('processedByName')) is-invalid @endif"
                                    name="processedByName">
                                    <option value="" @if ($inquiry->processedByName == '') {{ 'selected' }} @endif
                                        selected disabled>
                                        Select One
                                    </option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @if ($inquiry->processedByName == $user->id) {{ 'selected' }} @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('processedByName'))
                                        {{ $errors->first('processedByName') }}
                                    @else
                                        Select the City!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="specialRequest" class="form-label">Special Request</label>
                                <textarea class="form-control @if ($errors->has('specialRequest')) is-invalid @endif" name="specialRequest"
                                    id="specialRequest" cols="30" rows="10">{{ $inquiry->specialRequest }}</textarea>

                                <div class="invalid-tooltip">
                                    @if ($errors->has('specialRequest'))
                                        {{ $errors->first('specialRequest') }}
                                    @else
                                        Special Request is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="staffRemarks" class="form-label">Staff Remarks</label>
                                <textarea class="form-control @if ($errors->has('staffRemarks')) is-invalid @endif" name="staffRemarks"
                                    id="staffRemarks" cols="30" rows="10">{{ $inquiry->staffRemarks }}</textarea>

                                <div class="invalid-tooltip">
                                    @if ($errors->has('staffRemarks'))
                                        {{ $errors->first('staffRemarks') }}
                                    @else
                                        Staff Remarks is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                            <a href="{{ route('inquiries.index') }}" type="button"
                                class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('footer_scripts')
    <script></script>
@endpush
