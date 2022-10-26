<div class="tab-pane {{ $tab == 4 ? 'active' : '' }}" id="nav-border-top-04" role="tabpanel">

    <div class="row">
        <div class="col-sm-12">
            <form id="userNotesForm">
                <input type="hidden" value="{{ $quotation->id }}" name="quotationId" />
                <div class="mt-4 mb-4">
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="cancellationPolicy" type="checkbox" class="form-check-input user-notes-check" data-target="#cancellation-policy-card" id="cancellation-policy-card-switch" {{ isset($userNotes->cancellationPolicy) && $userNotes->cancellationPolicy == true ? 'checked' : '' }}>
                        <label for="cancellation-policy-card-switch" class="form-check-label">Cancellation Policy</label>
                    </div>
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="bookingNotes" type="checkbox" class="form-check-input user-notes-check" data-target="#booking-notes-card" id="booking-notes-card form-check-switch" {{ isset($userNotes->bookingNotes) && $userNotes->bookingNotes == true ? 'checked' : '' }}>
                        <label for="booking-notes-card form-check-switch" class="form-check-label">Booking Notes</label>
                    </div>
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="paymentTerms" type="checkbox" class="form-check-input user-notes-check" data-target="#payment-terms-card" id="payment-terms-card form-check-switch" {{ isset($userNotes->paymentTerms) && $userNotes->paymentTerms == true ? 'checked' : '' }}>
                        <label for="payment-terms-card form-check-switch" class="form-check-label">Payment Terms</label>
                    </div>
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="freeText" type="checkbox" class="form-check-input user-notes-check" data-target="#free-text-card" id="free-text-card form-check-switch" {{ isset($userNotes->freeText) && $userNotes->freeText == true ? 'checked' : '' }}>
                        <label for="free-text-card form-check-switch" class="form-check-label">Free Text</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="display: {{ isset($userNotes->cancellationPolicy) && $userNotes->cancellationPolicy == true ? 'block;' : 'none;' }}" id="cancellation-policy-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Hotel</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-policies-modal', ['quotationId' => $quotation->id, 'noteType' => 'cancellationPolicy']) }}" data-target="#quotationPoliciesModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Cancellation Policy
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="cancellationpolicy-tbl">
                        @foreach ($quotation->cancellationPolicy as $noteRow)
                        @include('quotations.notes_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="display: {{ isset($userNotes->bookingNotes) && $userNotes->bookingNotes == true ? 'block;' : 'none;' }}" id="booking-notes-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Booking Notes</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-policies-modal', ['quotationId' => $quotation->id, 'noteType' => 'bookingNotes']) }}" data-target="#quotationPoliciesModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Booking Notes
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="bookingnotes-tbl">
                        @foreach ($quotation->bookingNotes as $noteRow)
                        @include('quotations.notes_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="display: {{ isset($userNotes->paymentTerms) && $userNotes->paymentTerms == true ? 'block;' : 'none;' }}" id="payment-terms-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Payment Terms</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-policies-modal', ['quotationId' => $quotation->id, 'noteType' => 'paymentTerms']) }}" data-target="#quotationPoliciesModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Payment Terms
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="paymentterms-tbl">
                        @foreach ($quotation->paymentTerms as $noteRow)
                        @include('quotations.notes_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="display: {{ isset($userNotes->freeText) && $userNotes->freeText == true ? 'block;' : 'none;' }}" id="free-text-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Free Text</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-policies-modal', ['quotationId' => $quotation->id, 'noteType' => 'freeText']) }}" data-target="#quotationPoliciesModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Free Text
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="freetext-tbl">
                        @foreach ($quotation->freeText as $noteRow)
                        @include('quotations.notes_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('footer_scripts')
<script>
    $(document).ready(function() {

        $(document).on('click', '.user-notes-check', function(e) {
            var target = $(this).data('target');
            if ($(this).is(":checked")) {
                $(target).show(1000);
            } else {
                $(target).hide(1000);
            }

            $.ajax({

                url: "{{ route('save-quotation-notes') }}",
                type: "POST",
                data: $('#userNotesForm').serializeArray(),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function(data) {
                    console.log(data);
                },
                error: function() {

                },
                beforeSend: function() {

                },
                complete: function() {

                }
            });

        });

        $(document).on('submit', '#notesForm', function(e) {

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

    $(document).on('click', '.delete-quotation-note', function(e) {
        e.preventDefault();

        var url = $(this).data('url');
        var target = $(this).data('target');

        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15 mx-5">' +
                '<h4>Are you sure?</h4>' +
                '<p class="text-muted mx-4 mb-0">Are you Sure You want to Delete this Record ?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {

            if (result.isConfirmed) {

                $.ajax({

                    url: url,
                    type: "DELETE",
                    // data : filters,
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        $(target).remove();
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });
            }
        });
    });

    $(document).on('change', '#noteTemplates', function(e) {
        e.preventDefault();

        var description = $('#noteTemplates option:selected').data('description');
        var title = $('#noteTemplates option:selected').text();

        console.log(title, description);

        $('#quotationPoliciesModal #title').val(title);
        $('#quotationPoliciesModal #description').val(description);

    });

    $(document).on('click', '.view-note-description', function(e) {
        e.preventDefault();

        showDetailsModal({
            title: $(this).data('title'),
            description: $(this).data('description')
        });
    });
</script>
@endpush