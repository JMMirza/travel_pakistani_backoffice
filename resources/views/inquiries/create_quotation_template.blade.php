<div class="modal fade" id="InquiryQuotationTemplateModal" tabindex="1" aria-labelledby="quotationHotelModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content pt-2">
            <div class="col-lg-12 p-5">
            <div class="card-header  border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Quotations Templates</h5>

                </div>
            </div>
            </div>
            <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <table id="quotations-table" class="table table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead class="text-muted table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client Name</th>
                            <th>Assigned To</th>
                            <th>Version</th>
                            <th>Inquire Date</th>
                            <th>Created At</th>

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

        </div>

    </div>

</div>

@push('footer_scripts')
    <script>
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
            //show popup
            $(document).on('submit', '#InquiryTemplateForm', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var formData = $(this).serializeArray();
                var targetModal = $(this).data('target-modal');
                var targetRenderTable = $(this).data('render-tbl');

                $.ajax({

                    url: url,
                    data: formData,
                    type: "POST",
                    // dataType: 'json',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(resp) {

                        if (resp.errors) {
                            jQuery(targetModal + ' .alert-danger').html('');

                            jQuery.each(resp.errors, function(key, value) {
                                jQuery(targetModal + ' .alert-danger').show();
                                jQuery(targetModal + ' .alert-danger').append('<li>' + value + '</li>');
                            });

                            $(targetModal).animate({
                                scrollTop: 0
                            }, 'slow');


                        } else {
                            // console.log('#' + targetRenderTable, resp);
                            jQuery(targetModal + ' .alert-danger').hide();
                            $('#' + targetRenderTable).html(resp);
                            showToast('Record added successfully!', 'success');
                            jQuery(targetModal).modal('hide');

                        }

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
