@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Update LandMark</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('landmarks.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="bx bx-arrow-back label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row  needs-validation" action="{{ route('landmarks.update', $landmark->id) }}"
                        method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')


                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="title" class="form-label">Title</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('title')) is-invalid @endif" id="title"
                                    name="title" placeholder="Please Enter" value="{{ $landmark->title }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('title'))
                                        {{ $errors->first('title') }}
                                    @else
                                        Title is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="cityId" class="form-label">City</label>
                                <select class="form-select form-control mb-3" name="cityId" required>
                                    <option value="" @if ($landmark->cityId == '') {{ 'selected' }} @endif
                                        selected disabled>
                                        Select One
                                    </option>
                                    {{-- @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if ($landmark->cityId == $category->id) {{ 'selected' }} @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach --}}
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

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="number"
                                    class="form-control @if ($errors->has('phone')) is-invalid @endif" id="phone"
                                    name="phone" placeholder="Please enter Phone Number" value="{{ $landmark->phone }}">

                                <div class="invalid-tooltip">
                                    @if ($errors->has('phone'))
                                        {{ $errors->first('phone') }}
                                    @else
                                        Phone Number is empty or incorrect!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="email" class="form-label">Email</label>
                                <input type="number"
                                    class="form-control @if ($errors->has('email')) is-invalid @endif" id="email"
                                    name="email" placeholder="Please enter Email" value="{{ $landmark->email }}">

                                <div class="invalid-tooltip">
                                    @if ($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @else
                                        Email is empty or incorrect!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="website" class="form-label">Website</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('website')) is-invalid @endif" id="website"
                                    name="website" placeholder="Please enter Website" value="{{ $landmark->website }}">

                                <div class="invalid-tooltip">
                                    @if ($errors->has('website'))
                                        {{ $errors->first('website') }}
                                    @else
                                        Website is empty or incorrect!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="lat" class="form-label">Latitude</label>
                                <input type="decimal" step="0.00001"
                                    class="form-control @if ($errors->has('lat')) is-invalid @endif" id="lat"
                                    name="lat" placeholder="Please enter Latitude" value="{{ $landmark->lat }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('lat'))
                                        {{ $errors->first('lat') }}
                                    @else
                                        Latitude is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="long" class="form-label">Longitude</label>
                                <input type="decimal" step="0.00001"
                                    class="form-control @if ($errors->has('long')) is-invalid @endif" name="long"
                                    placeholder="Please enter longitude" value="{{ $landmark->long }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('long'))
                                        {{ $errors->first('long') }}
                                    @else
                                        Longitude is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="ZoomLevel" class="form-label">Zoom Level</label>
                                <input type="number"
                                    class="form-control @if ($errors->has('ZoomLevel')) is-invalid @endif"
                                    name="ZoomLevel" placeholder="Please enter zoom level"
                                    value="{{ $landmark->ZoomLevel }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('ZoomLevel'))
                                        {{ $errors->first('ZoomLevel') }}
                                    @else
                                        Zoom Level is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="categoryId" class="form-label">Category
                                    Level</label>
                                <select id="categoryId" class="form-select form-control mb-3" name="categoryId" required>
                                    <option value="" @if ($landmark->categoryId == '') {{ 'selected' }} @endif
                                        selected disabled>
                                        Select One
                                    </option>
                                    <option value="yes" @if ($landmark->categoryId == 'yes') {{ 'selected' }} @endif>
                                        Yes
                                    </option>
                                    <option value="no" @if ($landmark->categoryId == 'no') {{ 'selected' }} @endif>
                                        No
                                    </option>
                                </select>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('categoryId'))
                                        {{ $errors->first('categoryId') }}
                                    @else
                                        Category Level is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="subCategories" class="form-label">Sub Category</label>
                                <input type=text class="form-control @if ($errors->has('subCategories')) is-invalid @endif"
                                    id="subCategories" name="subCategories" placeholder="Please Enter Sub Category"
                                    value="{{ $landmark->subCategories }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('subCategories'))
                                        {{ $errors->first('subCategories') }}
                                    @else
                                        Sub Category is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="payment" class="form-label">Payment Type</label>
                                <input type=text class="form-control @if ($errors->has('payment')) is-invalid @endif"
                                    id="payment" name="payment" placeholder="Please Enter Payment Type"
                                    value="{{ $landmark->payment }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('payment'))
                                        {{ $errors->first('payment') }}
                                    @else
                                        Payment Type is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="accessType" class="form-label">Access Type</label>
                                <input type=text class="form-control @if ($errors->has('accessType')) is-invalid @endif"
                                    id="accessType" name="accessType" placeholder="Please Enter Access Type"
                                    value="{{ $landmark->accessType }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('accessType'))
                                        {{ $errors->first('accessType') }}
                                    @else
                                        Access Type is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="bestTimeToVisit" class="form-label">Best Time to Visit</label>
                                <input type=text class="form-control @if ($errors->has('bestTimeToVisit')) is-invalid @endif"
                                    id="bestTimeToVisit" name="bestTimeToVisit"
                                    placeholder="Please Enter Best Time to Visit"
                                    value="{{ $landmark->bestTimeToVisit }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('bestTimeToVisit'))
                                        {{ $errors->first('bestTimeToVisit') }}
                                    @else
                                        Best Time to Visit is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="averageTimeOnSpot" class="form-label">Average Time On Spot</label>
                                <input type=text class="form-control @if ($errors->has('averageTimeOnSpot')) is-invalid @endif"
                                    id="averageTimeOnSpot" name="averageTimeOnSpot"
                                    placeholder="Please Enter Average Time On Spot"
                                    value="{{ $landmark->averageTimeOnSpot }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('averageTimeOnSpot'))
                                        {{ $errors->first('averageTimeOnSpot') }}
                                    @else
                                        Average Time On Spot is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="services" class="form-label">Services</label>
                                <input type=text class="form-control @if ($errors->has('services')) is-invalid @endif"
                                    id="services" name="services" placeholder="Please Enter Services"
                                    value="{{ $landmark->services }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('services'))
                                        {{ $errors->first('services') }}
                                    @else
                                        Services is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="seating" class="form-label">Seating</label>
                                <input type=text class="form-control @if ($errors->has('seating')) is-invalid @endif"
                                    id="seating" name="seating" placeholder="Please Enter Seating"
                                    value="{{ $landmark->seating }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('seating'))
                                        {{ $errors->first('seating') }}
                                    @else
                                        Seating is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="cuisines" class="form-label">Cuisines</label>
                                <input type=text class="form-control @if ($errors->has('cuisines')) is-invalid @endif"
                                    id="cuisines" name="cuisines" placeholder="Please Enter Cuisines"
                                    value="{{ $landmark->cuisines }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('cuisines'))
                                        {{ $errors->first('cuisines') }}
                                    @else
                                        Cuisines is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="menu" class="form-label">Menu</label>
                                <input type=text class="form-control @if ($errors->has('menu')) is-invalid @endif"
                                    id="menu" name="menu" placeholder="Please Enter Menu"
                                    value="{{ $landmark->menu }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('menu'))
                                        {{ $errors->first('menu') }}
                                    @else
                                        Menu is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="certification" class="form-label">Certification</label>
                                <input type=text class="form-control @if ($errors->has('certification')) is-invalid @endif"
                                    id="certification" name="certification" placeholder="Please Enter Certification"
                                    value="{{ $landmark->certification }}">
                                <div class="invalid-tooltip">
                                    @if ($errors->has('certification'))
                                        {{ $errors->first('certification') }}
                                    @else
                                        Certification is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 col-sm-12 mb-3">
                            <div class="form-label-group in-border">
                                <label for="image" class="form-label">Image</label>
                                <input type="file"
                                    class="form-control @if ($errors->has('image')) is-invalid @endif"
                                    id="image" name="image" placeholder="Please Enter Account Name"
                                    value="{{ $landmark->image }}" required>
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
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                            <a href="{{ route('landmarks.index') }}" type="button"
                                class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal flipInUp" id="domicile-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated flipInUp">
                <div class="modal-body">
                    <div class="text-center">
                        <img class="d-block w-100" src="{{ $product->product_picture_url }}" alt="domicile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('footer_scripts')
    <script></script>
@endpush
