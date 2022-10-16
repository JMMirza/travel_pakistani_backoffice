<div class="tab-pane {{ $tab == 2 ? 'active' : '' }}" id="nav-border-top-02" role="tabpanel">

    <div class="align-items-center d-flex mb-3">
        <h4 class="card-title mb-0 flex-grow-1">Itinerary</h4>
        <div class="flex-shrink-0">
            <a href="{{ route('add-quotation-itinerary-modal') }}" data-url="{{ route('add-quotation-itinerary-modal') }}" class="btn btn-success btn-label btn-sm show-modal-itinerary" data-target="#quotationItineraryModal">
                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create New
            </a>
            <a href="{{ route('itinerary-list-modal') }}" data-url="{{ route('itinerary-list-modal') }}" class="btn btn-success btn-label btn-sm show-modal" data-target="#itineraryListModal">
                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add From Existing
            </a>
        </div>
    </div>

    <div>
        @foreach ($quotation->itineraryBasic as $itinerary)
        @include('quotations.itinerary_card')
        @endforeach
    </div>
</div>

@push('footer_scripts')
<script>
    $(document).ready(function() {

        $(document).on('click', '.show-modal-itinerary', function(e) {

            e.preventDefault();

            var target = $(this).data('target');
            var url = $(this).data('url');

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
</script>
@endpush