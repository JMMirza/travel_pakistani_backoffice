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
                        <a class="nav-link {{ isset($tab) && $tab == 1 ? 'active' : '' }}" href="{{ route('itinerary-templates.edit', $itinerary_template->id) }}?tab=1">
                            Basic Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($tab) && $tab == 2 ? 'active' : '' }}" href="{{ route('itinerary-templates.edit', $itinerary_template->id) }}?tab=2">
                            Details
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane {{ isset($tab) && $tab == 1 ? 'active' : '' }}" id="nav-border-top-01" role="tabpanel">
                        <form class="needs-validation row g-3" action="{{ route('itinerary-templates.update', $itinerary_template->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="col-md-8 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="hidden" name="itinerary_id" value="{{ $itinerary_template->id }}">
                                    <label for="templateTitle" class="form-label">Template Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @if ($errors->has('templateTitle')) is-invalid @endif" id="templateTitle" name="templateTitle" placeholder="Please Enter" value="{{ $itinerary_template->templateTitle }}" required>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('templateTitle'))
                                        {{ $errors->first('templateTitle') }}
                                        @else
                                        Template Title is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="templateTitle" class="form-label">Days <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @if ($errors->has('totalDays')) is-invalid @endif" id="templateTitle" name="totalDays" placeholder="Please Enter" value="{{ $itinerary_template->totalDays }}" required>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('totalDays'))
                                        {{ $errors->first('totalDays') }}
                                        @else
                                        Day is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="templateType" class="form-label">Template Type <span class="text-danger">*</span></label>
                                    <select name="templateType" id="templateType" class="form-select form-control @if ($errors->has('templateType')) is-invalid @endif">
                                        <option value="basic" @if ($itinerary_template->templateType == 'basic') selected @endif>
                                            Basic</option>
                                        <option value="detailed" @if ($itinerary_template->templateType == 'detailed') selected @endif>Detailed
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('templateType'))
                                        {{ $errors->first('templateType') }}
                                        @else
                                        Select the Type!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="categoryId" class="form-label">Categories <span class="text-danger">*</span></label>
                                    <select class="form-select form-control mb-3 @if ($errors->has('categoryId')) is-invalid @endif" name="categoryId" required>
                                        <option value="" @if ($itinerary_template->categoryId == '') {{ 'selected' }} @endif selected
                                            disabled>
                                            Select One
                                        </option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if ($itinerary_template->categoryId == $category->id) {{ 'selected' }} @endif>
                                            {{ $category->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
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
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="" class="form-select form-control mb-3 @if ($errors->has('status')) is-invalid @endif">
                                        <option value="0" @if ($itinerary_template->status == '0') selected @endif>
                                            In Active</option>
                                        <option value="1" @if ($itinerary_template->status == '1') selected @endif>Active
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('status'))
                                        {{ $errors->first('status') }}
                                        @else
                                        Select the Type!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button id="submit_btn" class="btn btn-primary" type="submit">Save Changes</button>
                                <a href="{{ route('itinerary-templates.index') }}" type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane {{ isset($tab) && $tab == 2 ? 'active' : '' }}" id="nav-border-top-02" role="tabpanel">
                        <form class="needs-validation row g-3 mb-3" action="{{ route('save-itinerary-detail',$itinerary_template->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" value="{{ isset($itinerary_template_details_obj->id) ? $itinerary_template_details_obj->id :''  }}" name="itineraryTemplateId" />
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="itineraryCities" class="form-label">City</label>
                                    <select class="form-control landmark-filters" name="cityId" id="itineraryCities" required>
                                        @foreach ($cities as $city)
                                        <option value="{{ $city->city_id }}"@if(isset($itinerary_template_details_obj->cityId) && $city->city_id==$itinerary_template_details_obj->cityId) selected @endif>{{ $city->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
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
                                    <label for="pickupTime" class="form-label">Day Number</label>
                                    <input type="number" class="form-control @if ($errors->has('dayNo')) is-invalid @endif" id="templateTitle" name="dayNo" placeholder="Please Enter" value="@if(isset($itinerary_template_details_obj->dayNo)){{$itinerary_template_details_obj->cityId}}@endif" required>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('dayNo'))
                                        {{ $errors->first('dayNo') }}
                                        @else
                                        Day is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="pickupTime" class="form-label">Pickup Time</label>
                                    <input type="time" class="form-control @if ($errors->has('pickupTime')) is-invalid @endif" id="templateTitle" name="pickupTime" placeholder="Please Enter" value="@if(isset($itinerary_template_details_obj->pickupTime)){{$itinerary_template_details_obj->pickupTime}}@else{{ old('pickupTime') }}@endif">
                                    <div class="invalid-feedback">
                                        @if ($errors->has('pickupTime'))
                                        {{ $errors->first('pickupTime') }}
                                        @else
                                        Pickup Time is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description" placeholder="Enter description here...">@if(isset($itinerary_template_details_obj->description)){{$itinerary_template_details_obj->description}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file" class="form-control" name="photo" id="photo">
                                </div>
                            </div>
                            <div class="col-12">
                                <button id="submit_btn" class="btn btn-primary" type="submit">Save Changes</button>
                                <a href="{{ route('itinerary-templates.index') }}" type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                            </div>
                        </form>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-bordered table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 5%;">Day</th>
                                        <th scope="col" style="width: 15%;">City</th>
                                        <th scope="col" style="width: 12%;">Pickup Time</th>
                                        <th scope="col">Description</th>
                                        <th scope="col" style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($itinerary_template->templateDetails as $d)
                                    <tr>
                                        <td>{{ $d->dayNo ?? "N/A" }}</td>
                                        <td>{{ $d->city->title ?? "N/A" }}</td>
                                        <td>{{ $d->pickupTime ?? "N/A" }}</td>
                                        <td>{{ $d->description ?? "N/A" }}</td>
                                        <td>
{{--                                            <form method="PUT" action="/tasks/update">--}}
{{--                                                @csrf--}}
{{--                                                <input type="hidden" name="TempDetailId" value="{{ $d->id }}">--}}
{{--                                               --}}
{{--                                                <button type="submit" class="btn btn-primary" name="updateTask">Update Task</button>--}}
{{--                                            </form>--}}

                                            <a href="{{ url('/edit-itinerary-templates-detail/'.$d->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                                <i class="mdi mdi-lead-pencil"></i>
                                            </a>
                                            <a href="{{ url('/delete-itinerary-templates-detail/'.$d->id) }}" data-table="itinerary-templates-data-table" class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">No records found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('footer_scripts')
<script src="{{ asset('theme/dist/default/assets/js/pages/form-input-spin.init.js') }}"></script>
<script type="text/javascript" src="{{ asset('modules/itinerary_templates.js') }}"></script>
@endpush
