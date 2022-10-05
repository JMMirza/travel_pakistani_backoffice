{{-- <div class="row"> --}}
<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add Hotel</h4>
        </div>

        <div class="card-body">
            <form class="row  needs-validation" action="{{ route('hotels.store') }}" method="POST" novalidate>
                @csrf
                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="hotelName" class="form-label">Hotel Name</label>
                        <input type="text" class="form-control @if ($errors->has('hotelName')) is-invalid @endif"
                            id="hotelName" name="hotelName" placeholder="Please Enter" value="{{ old('hotelName') }}"
                            required>
                        <div class="invalid-tooltip">
                            @if ($errors->has('hotelName'))
                                {{ $errors->first('hotelName') }}
                            @else
                                Hotel Name is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="cityId" class="form-label">City</label>
                        <select
                            class="form-select form-control mb-3 @if ($errors->has('cityId')) is-invalid @endif"
                            name="cityId" required>
                            <option value="" @if (old('cityId') == '') {{ 'selected' }} @endif selected
                                disabled>
                                Select One
                            </option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    @if (old('cityId') == $city->id) {{ 'selected' }} @endif>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-tooltip">
                            @if ($errors->has('cityId'))
                                {{ $errors->first('cityId') }}
                            @else
                                City is required!
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="website" class="form-label">Website</label>
                        <input type="text" class="form-control @if ($errors->has('website')) is-invalid @endif"
                            id="website" name="website" placeholder="Please Enter" value="{{ old('website') }}">
                        <div class="invalid-tooltip">
                            @if ($errors->has('website'))
                                {{ $errors->first('website') }}
                            @else
                                Website is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control @if ($errors->has('email')) is-invalid @endif"
                            id="email" name="email" placeholder="Please Enter" value="{{ old('email') }}">
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
                        <input type="text" class="form-control @if ($errors->has('contactNo')) is-invalid @endif"
                            id="contactNo" name="contactNo" placeholder="Please Enter" value="{{ old('contactNo') }}">
                        <div class="invalid-tooltip">
                            @if ($errors->has('contactNo'))
                                {{ $errors->first('contactNo') }}
                            @else
                                Phone Number is required!
                            @endif
                        </div>
                    </div>
                </div>



                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="lat" class="form-label">Latitude</label>
                        <input type="decimal" step="0.00001"
                            class="form-control @if ($errors->has('lat')) is-invalid @endif" id="lat"
                            name="lat" placeholder="Please enter Latitude" value="{{ old('lat') }}">
                        <div class="invalid-tooltip">
                            @if ($errors->has('lat'))
                                {{ $errors->first('lat') }}
                            @else
                                Latitude is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="long" class="form-label">Longitude</label>
                        <input type="decimal" step="0.00001"
                            class="form-control @if ($errors->has('long')) is-invalid @endif" name="long"
                            placeholder="Please enter longitude" value="{{ old('long') }}">
                        <div class="invalid-tooltip">
                            @if ($errors->has('long'))
                                {{ $errors->first('long') }}
                            @else
                                Longitude is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="hotelAddress" class="form-label">Hotel Address</label>
                        <input type="text" class="form-control @if ($errors->has('hotelAddress')) is-invalid @endif"
                            id="hotelAddress" name="hotelAddress" placeholder="Please Enter"
                            value="{{ old('hotelAddress') }}" required>
                        <div class="invalid-tooltip">
                            @if ($errors->has('hotelAddress'))
                                {{ $errors->first('hotelAddress') }}
                            @else
                                Hotel Address is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                    <a href="{{ route('hotels.index') }}" type="button"
                        class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- </div> --}}
