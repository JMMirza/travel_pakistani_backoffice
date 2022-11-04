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
                    <table id="inquiries-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
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

   @include('inquiries.create_quotation_template')
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script>

        $(document).ready(function() {

            $(document).on('click', '.show-modal-inquiry-quotation', function(e) {

                e.preventDefault();

                var target = $(this).data('target');
                var url = $(this).data('url');
                console.log(url);

                $.ajax({

                    url: url,
                    type: "GET",
                    // dataType: 'html',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {

                       // $('#modal-div').html(data);


                        //$(target).modal('show');
                        $('#InquiryQuotationTemplateModal').modal('show');
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('modules/inquiries.js') }}"></script>
@endpush
