@extends('layouts.master')

@section('content')
    <div class="row">
        @if (isset($city))
            @include('cities.edit_city')
        @else
            {{-- @permission('add-country') --}}
            @include('cities.add_new_city')
            {{-- @endpermission --}}
        @endif
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Cities</h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="cities-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Abbreviation</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <input id="ajaxRoute" value="{{ route('cities.index') }}" hidden />
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript" src="{{ asset('modules/cities.js') }}"></script>
@endpush
