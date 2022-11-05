@extends('layouts.master')

@section('content')
@include('layouts.flash_message')
<div class="row">
    <div class="col-lg-12">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Inquiries</h4>
            {{-- @permission('add-course') --}}
            <div class="flex-shrink-0">
                <a href="{{ route('inquiries.create') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                </a>
            </div>
            {{-- @endpermission --}}
        </div>
        <div class="card">
            <div class="card-body">
                <table id="inquiries-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Preferred Dates</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Assigned To</th>
                            <th>Inquiry Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<input id="ajaxRoute" value="{{ route('inquiries.index') }}" hidden />

@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript" src="{{ asset('modules/inquiries.js') }}"></script>

<script>
    $(document).ready(function() {

        $(document).on('click', '.show-modal-inquiry-quotation', function(e) {

            e.preventDefault();

            var target = $(this).data('target');
            var url = $(this).data('url');
            console.log('show modal', target, url);

            $.ajax({

                url: url,
                type: "GET",
                dataType: 'html',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function(data) {
                    $('#modal-div').html(data);

                    $.extend($.fn.dataTableExt.oStdClasses, {
                        "sFilterInput": "form-control",
                        "sLengthSelect": "form-control"
                    });

                    var table = $('#quotations-templates-table').dataTable({
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
                            url: "{{ route('list-quotation-templates') }}",
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
                                data: 'created_at',
                                name: 'created_at'
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



                    $(target).modal('show');
                    $.fn.dataTable.tables({
                        visible: true,
                        api: true
                    }).columns.adjust().draw();
                },
                error: function() {

                },
                beforeSend: function() {

                },
                complete: function() {

                }
            });
        });


    });
</script>
@endpush
