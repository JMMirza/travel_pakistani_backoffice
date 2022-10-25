@extends('layouts.master')
@section('content')
@include('layouts.flash_message')
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header  border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Quotations</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('quotation-save', ['tab' => 1]) }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Quotation
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form>
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search" placeholder="Search for customer name or something..." id="mySearch">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-2 col-sm-6">
                            <div>
                                <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" id="created" placeholder="Select date">
                            </div>
                        </div>

                    </div>
                    <!--end row-->
                </form>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-tabs-custom nav-success" role="tablist">

                    @foreach ($status as $s)
                    <li class="nav-item">
                        <a data-status="{{ $s->id }}" class="nav-link All py-3 status-type" data-bs-toggle="tab" role="tab" aria-selected="true">
                            <i class="{{ $s->iconName }} me-1 align-bottom"></i> {{ $s->label }}
                        </a>
                    </li>
                    @endforeach


                </ul>
                <table id="quotations-table" class="table table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead class="text-muted table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client Name</th>
                            <th>Assigned To</th>
                            <th>Version</th>
                            <th>Inquire Date</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
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
    var status = 0;

    $(document).ready(function() {

        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#quotations-table').dataTable({
            searching: false,
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
                url: "{{ route('quotations') }}",
                data: function(d) {
                    d.status = status;
                    d.search_text = $('#mySearch').val();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'clientName',
                    name: 'clientName'
                },
                {
                    data: 'processedByName',
                    name: 'processedByName'
                },
                {
                    data: 'versionNo',
                    name: 'versionNo',
                    sClass: 'text-center'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'status',
                    name: 'status'
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

    $(document).on('click', '.status-type', function(e) {
        e.preventDefault();
        status = $(this).data('status');
        console.log(status);
        $('#quotations-table').DataTable().ajax.reload(null, false);
    });

    $(document).on("keyup", '#mySearch', function() {
        var value = $(this).val().toLowerCase();
        if (value.length > 0 || value.length == 0) {
            $('#quotations-table').DataTable().ajax.reload(null, false);
        }
    });
</script>
@endpush