<div class="tab-pane {{ isset($tab) && $tab == 2 ? 'active' : '' }}" id="nav-border-top-02" role="tabpanel">

    <div class="align-items-center d-flex mb-3">
        <h4 class="card-title mb-0 flex-grow-1">Itinerary</h4>
        <div class="flex-shrink-0">
            <a href="{{ route('add-quotation-itinerary-modal') }}" data-url="{{ route('add-quotation-itinerary-modal', ['quotationId' => $quotation->id]) }}" class="btn btn-success btn-label btn-sm show-modal-itinerary" data-target="#quotationItineraryModal">
                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create New
            </a>
            <a href="{{ route('itinerary-list-modal') }}" data-url="{{ route('itinerary-list-modal') }}" class="btn btn-success btn-label btn-sm show-modal" data-target="#itineraryListModal">
                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add From Existing
            </a>
        </div>
    </div>

    <div id="itineraryCardSection">
        @forelse ($quotation->itineraryBasic as $itinerary)
        @include('quotations.itinerary_card')
        @empty
        <div class="alert alert-light alert-dismissible alert-solid alert-label-icon fade show" role="alert">
            <i class="ri-question-line label-icon"></i><strong>Info</strong> -
            No itinerary added to quotation.
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endforelse
    </div>
</div>

@push('footer_scripts')
<script>
    $(document).ready(function() {

        $(document).on('click', '.show-modal-itinerary', function(e) {

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

                    $('#modal-div').html(data);

                    $('#landmarkList').select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        allowClear: true,
                        dropdownParent: $(target)
                    });

                    $('.duration').flatpickr({
                        // minDate: "today",
                        mode: "range",
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                    });

                    $('.single-date').flatpickr({
                        minDate: "today",
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                    });

                    $(target).modal('show');
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

    $(document).on('submit', '#itineraryQuotationForm', function(e) {

        e.preventDefault();

        var url = $(this).attr('action');

        // var formData = $(this).serializeArray();
        // var formData = new FormData(document.getElementById("itineraryQuotationForm"));

        var form = $('#itineraryQuotationForm')[0];
        var formData = new FormData(form);
        var targetModal = $(this).data('target-modal');
        // var targetRenderTable = $(this).data('render-tbl');

        $.ajax({

            url: url,
            data: formData,
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false, // Important!
            contentType: false,
            cache: false,
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
                    showToast('Record added successfully!', 'success');
                    jQuery(targetModal).modal('hide');
                    $('#itineraryCardSection').html(resp);
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

    $(document).on('click', '.add-existing-itinerary', function(e) {

        e.preventDefault();

        var itinerary_detail_id = $(this).data('itinerary_detail_id');
        var target = $(this).data('target');

        var quotation_id = $('#quotationId').val();
        var url = "{{ route('add-quotation-itinerary') }}";

        $.ajax({

            url: url,
            type: "POST",
            data: {
                itinerary_detail_id: itinerary_detail_id,
                quotation_id: quotation_id
            },
            // dataType: 'html',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            success: function(data) {
                // console.log(data, target);
                $(target).remove();
                $('#itineraryCardSection').html(data);

            },
            error: function() {

            },
            beforeSend: function() {

            },
            complete: function() {

            }
        });
    });

    $(document).on('click', '.remove-quotation-itinerary', function(e) {

        e.preventDefault();

        var quotation_itinerary_id = $(this).data('quotation_itinerary_id');
        var target = $(this).data('target');

        var quotation_id = $('#quotationId').val();
        var url = $(this).attr('href');

        $.ajax({

            url: url,
            type: "DELETE",
            // dataType: 'html',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            success: function(data) {
                console.log(data, target);
                $(target).remove();
                // Swal.fire({
                //     position: "top-end",
                //     icon: "warning",
                //     title: "Removed successfully.",
                //     showConfirmButton: !1,
                //     timer: 1500,
                //     showCloseButton: !0
                // })
            },
            error: function() {

            },
            beforeSend: function() {

            },
            complete: function() {

            }
        });
    });
</script>
@endpush