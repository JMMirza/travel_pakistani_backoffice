@extends('layouts.master')

@section('content')
@include('layouts.flash_message')
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create Itinerary</h4>

                <div class="flex-shrink-0">
                    <a href="{{ route('itinerary-templates.index') }}" class="btn btn-success btn-label btn-sm">
                        <i class="ri-file-list-3-line label-icon align-middle fs-16 me-2"></i> View Itinerary
                    </a>

                </div>

            </div>
            <div class="card-body">


                <ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link  active" data-bs-toggle="tab" href="#nav-border-top-01">
                            Basic Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  " data-bs-toggle="tab" href="#nav-border-top-02">
                            Details
                        </a>
                    </li>

                </ul>
                <form class="needs-validation row g-3"
                      action="{{ route('itinerary-templates.update', $itinerary_template->id) }}" method="POST"
                      enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                <div class="tab-content text-muted">

                    <div class="tab-pane active" id="nav-border-top-01" role="tabpanel">
                        <div class="row  needs-validation row g-3">

                            <div class="col-md-6 col-sm-12 mb-3">
                                <div class="form-label-group in-border">
                                    <input type="hidden" name="itinerary_id" value="{{ $itinerary_template->id }}">
                                    <label for="templateTitle" class="form-label">Template Title</label>
                                    <input type="text" class="form-control @if ($errors->has('templateTitle')) is-invalid @endif"
                                           id="templateTitle" name="templateTitle" placeholder="Please Enter"
                                           value="{{ $itinerary_template->templateTitle }}" required>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('templateTitle'))
                                            {{ $errors->first('templateTitle') }}
                                        @else
                                            Template Title is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <div class="form-label-group in-border">
                                    <label for="templateTitle" class="form-label">Days</label>
                                    <input type="number" class="form-control @if ($errors->has('totalDays')) is-invalid @endif"
                                           id="templateTitle" name="totalDays" placeholder="Please Enter"
                                           value="{{ $itinerary_template->totalDays }}" required>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('totalDays'))
                                            {{ $errors->first('totalDays') }}
                                        @else
                                            Day is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="templateType" class="form-label">Template Type</label>
                                    <select name="templateType" id="templateType"
                                            class="form-select form-control mb-3 @if ($errors->has('templateType')) is-invalid @endif">
                                        <option value="basic" @if ($itinerary_template->templateType == 'basic') selected @endif>
                                            Basic</option>
                                        <option value="detailed" @if ($itinerary_template->templateType == 'detailed') selected @endif>Detailed
                                        </option>
                                    </select>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('templateType'))
                                            {{ $errors->first('templateType') }}
                                        @else
                                            Select the Type!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="categoryId" class="form-label">Categories</label>
                                    <select
                                        class="form-select form-control mb-3 @if ($errors->has('categoryId')) is-invalid @endif"
                                        name="categoryId" required>
                                        <option value="" @if ($itinerary_template->categoryId == '') {{ 'selected' }} @endif selected
                                                disabled>
                                            Select One
                                        </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                            @if ($itinerary_template->categoryId == $category->id) {{ 'selected' }} @endif>
                                                {{ $category->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('categoryId'))
                                            {{ $errors->first('categoryId') }}
                                        @else
                                            Select the Category!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id=""
                                            class="form-select form-control mb-3 @if ($errors->has('status')) is-invalid @endif">
                                        <option value="0" @if ($itinerary_template->status == '0') selected @endif>
                                            In Active</option>
                                        <option value="1" @if ($itinerary_template->status == '1') selected @endif>Active
                                        </option>
                                    </select>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('status'))
                                            {{ $errors->first('status') }}
                                        @else
                                            Select the Type!
                                        @endif
                                    </div>
                                </div>
                            </div>


                    </div>
                    </div>

                    <div class="tab-pane" id="nav-border-top-02" role="tabpanel">

<div class="row  needs-validation row g-3">

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="itineraryCities" class="form-label">City</label>
                                    <select class="form-control landmark-filters" name="itineraryCities" id="itineraryCities">
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->city_id }}">{{ $city->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('itineraryCities'))
                                            {{ $errors->first('itineraryCities') }}
                                        @else
                                            Select the City!
                                        @endif
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="pickupTime" class="form-label">Day Number</label>
                                    <input type="number" class="form-control @if ($errors->has('numberDays')) is-invalid @endif"
                                           id="templateTitle" name="numberDays" placeholder="Please Enter"
                                           value="{{ old('numberDays') }}" required>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('numberDays'))
                                            {{ $errors->first('numberDays') }}
                                        @else
                                            Day is required!
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="pickupTime" class="form-label">Pickup Time</label>
                                    <input type="time" class="form-control @if ($errors->has('pickupTime')) is-invalid @endif"
                                           id="templateTitle" name="pickupTime" placeholder="Please Enter"
                                           value="{{ old('pickupTime') }}" required>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('pickupTime'))
                                            {{ $errors->first('pickupTime') }}
                                        @else
                                            Pickup Time is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 mb-3">
                                 <div class="form-label-group in-border">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control mb-3" name="discription" id="description" placeholder="Enter description here..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 mb-3">
                                <div class="form-label-group in-border">
                                    <label for="description" class="form-label">Photo</label>
                                    <input type="file" class="form-control mb-3" name="photo" id="description"></input>
                                </div>
                            </div>


</div>


                </div>


            </div>
                    <div class="col-12 text-end">
                        <button id="submit_btn" class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('itinerary-templates.index') }}" type="button"
                           class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
        </div>


    </div>
</div>
</div>




            @if (isset($itinerary_template))

            @else
                {{-- @permission('add-country') --}}
                {{--            @include('itinerary_templates.add_new_itinerary_template')--}}
                {{-- @endpermission --}}
            @endif
            <div class="col-lg-12">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Custom Template</h4>
                    {{-- @permission('add-course') --}}
{{--                    <div class="flex-shrink-0">--}}
{{--                        <a href="{{ route('itinerary-templates.create') }}" class="btn btn-success btn-label btn-sm">--}}
{{--                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New--}}
{{--                        </a>--}}
{{--                    </div>--}}
                    {{-- @endpermission --}}
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="itinerary-templates-data-table"
                               class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Total Days</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        <input id="ajaxRoute" value="{{ route('itinerary-templates.index') }}" hidden />

@endsection
@push('footer_scripts')
    <script src="{{ asset('theme/dist/default/assets/js/pages/form-input-spin.init.js') }}"></script>
    <script type="text/javascript" src="{{ asset('modules/itinerary_templates.js') }}"></script>
@endpush
