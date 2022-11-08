@extends('layouts.master')

@section('content')
@include('layouts.flash_message')
<div class="row">

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Custom Template</h4>
            <div class="flex-shrink-0">
                <a href="{{ route('itinerary-templates.create') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="itinerary-templates-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
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
</div>
<input id="ajaxRoute" value="{{ route('itinerary-templates.index') }}" hidden />
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
<script src="{{ asset('theme/dist/default/assets/js/pages/form-input-spin.init.js') }}"></script>
<script type="text/javascript" src="{{ asset('modules/itinerary_templates.js') }}"></script>
@endpush