@extends('layouts.master')
@section('content')
@include('layouts.flash_message')
<div class="row">
    <div class="col-lg-12">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Staff </h4>
            <div class="flex-shrink-0">
                <a href="{{ route('staffs.create') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="employee-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Eamil</th>

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
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#employee-table').dataTable({
            searching: true,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                searchPlaceholder: "Search..."
            },
            ajax: {
                url: "{{ route('staffs.index') }}",
            },
            columns: [{
                    data: 'user.id',
                    name: 'user.id'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'user.email',
                    name: 'user.email'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "5%",
                    sClass: 'text-center'
                }
            ]
        });
    });

    // $(document).on('change', '.filter', function() {
    //     $('#employee-table').DataTable().ajax.reload(null, false);
    // });

    $(document).on("keyup", '#mySearch', function() {
        var value = $(this).val().toLowerCase();
        if (value.length > 0 || value.length == 0) {
            $('#employee-table').DataTable().ajax.reload(null, false);
        }
    });
</script>
@endpush