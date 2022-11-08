@extends('layouts.master')
@push('header_scripts')
<link href="{{ asset('theme/dist/default/assets/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('theme/dist/default/assets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
@include('layouts.flash_message')
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create Itinerary Template</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('itinerary-templates.index') }}" class="btn btn-success btn-label btn-sm">
                        <i class="ri-file-list-3-line label-icon align-middle fs-16 me-2"></i> View Itinerary Templates
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
                        <a class="nav-link disabled" data-bs-toggle="tab" href="">
                            Details
                        </a>
                    </li>
                </ul>
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="nav-border-top-01" role="tabpanel">
                        <form class="row needs-validation row g-3" action="{{ route('itinerary-templates.store') }}" method="POST" enctype='multipart/form-data' novalidate>
                            @csrf
                            <div class="col-md-8 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="templateTitle" class="form-label">Template Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @if ($errors->has('templateTitle')) is-invalid @endif" id="templateTitle" name="templateTitle" placeholder="Please enter template title" value="{{ old('templateTitle') }}" required>
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
                                    <input type="number" class="form-control @if ($errors->has('days')) is-invalid @endif" id="templateTitle" name="days" placeholder="Please enter days" value="{{ old('days') }}" required>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('days'))
                                        {{ $errors->first('days') }}
                                        @else
                                        Day is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="templateType" class="form-label">Template Type <span class="text-danger">*</span></label>
                                    <select name="templateType" id="templateType" class="form-select form-control @if ($errors->has('templateType')) is-invalid @endif" required>
                                        <option value=""> Select one</option>
                                        <option value="basic" @if (old('templateType'=='basic' )) selected @endif> Basic</option>
                                        <option value="detailed" @if (old('templateType'=='detailed' )) selected @endif>Detailed</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('templateType'))
                                        {{ $errors->first('templateType') }}
                                        @else
                                        Select the template type!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="categoryId" class="form-label">Categories <span class="text-danger">*</span></label>
                                    <select class="form-select form-control @if ($errors->has('categoryId')) is-invalid @endif" name="categoryId" required>
                                        <option value="" @if (old('categoryId')=='' ) {{ 'selected' }} @endif selected disabled>
                                            Select One
                                        </option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if (old('categoryId')==$category->id) {{ 'selected' }} @endif>
                                            {{ $category->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @if ($errors->has('categoryId'))
                                        {{ $errors->first('categoryId') }}
                                        @else
                                        Select a category!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="" class="form-select form-control @if ($errors->has('status')) is-invalid @endif">
                                        <option value="0" @if (old('status'=='0' )) selected @endif>
                                            In Active</option>
                                        <option value="1" @if (old('status'=='1' )) selected @endif>Active
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
                            <div class="col-12 text-end">
                                <button id="submit_btn" class="btn btn-primary" type="submit">Save Changes</button>
                                <a href="{{ route('itinerary-templates.index') }}" type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @push('footer_scripts')
    <script src="{{ asset('theme/dist/default/assets/libs/quill/quill.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('modules/operators.js') }}"></script>
    @endpush