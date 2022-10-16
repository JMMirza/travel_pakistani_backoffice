<div class="tab-pane {{ $tab == 3 ? 'active' : '' }}" id="nav-border-top-03" role="tabpanel">

    <div class="row">
        <div class="col-sm-12">
            <form id="serviceTypesForm">
                <input type="hidden" value="{{ $quotation->id }}" name="quotationId" />
                <div class="mt-4 mb-4">
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="hotelService" type="checkbox" class="form-check-input services-check" data-target="#hotel-card" id="hotel-card-switch" {{ isset($services->Hotel) && $services->Hotel == true ? 'checked' : '' }}>
                        <label for="hotel-card-switch" class="form-check-label">Hotel</label>
                    </div>
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="mealService" type="checkbox" class="form-check-input services-check" data-target="#meal-card" id="meal-cardform-check-switch" {{ isset($services->Meal) && $services->Meal == true ? 'checked' : '' }}>
                        <label for="meal-cardform-check-switch" class="form-check-label">Meal</label>
                    </div>
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="transportService" type="checkbox" class="form-check-input services-check" data-target="#transport-card" id="transport-cardform-check-switch" {{ isset($services->Transport) && $services->Transport == true ? 'checked' : '' }}>
                        <label for="transport-cardform-check-switch" class="form-check-label">Transport</label>
                    </div>
                    <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                        <input name="activitiesService" type="checkbox" class="form-check-input services-check" data-target="#activities-card" id="activities-cardform-check-switch" {{ isset($services->Activities) && $services->Activities == true ? 'checked' : '' }}>
                        <label for="activities-cardform-check-switch" class="form-check-label">Activities</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="display: {{ isset($services->Hotel) && $services->Hotel == true ? 'block;' : 'none;' }}" id="hotel-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Hotel</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-hotel-modal', ['quotationId' => $quotation->id]) }}" data-target="#quotationHotelModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Hotel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Hotel</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Date</th>
                            <th class="text-center" scope="col">Unit Cost</th>
                            <th class="text-center" scope="col">Total Person</th>
                            <th class="text-center" scope="col">Nights</th>
                            <th class="text-center" scope="col">Total Cost</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="hotel-tbl">
                        @foreach ($quotation->hotelQuotations as $hotel)
                        @include('quotations.hotel_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="display: {{ isset($services->Meal) && $services->Meal == true ? 'block;' : 'none;' }}" id="meal-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Meal</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-meal-modal', ['quotationId' => $quotation->id]) }}" data-target="#quotationMealModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Meal
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Meal Type</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Date</th>
                            <th scope="col">Unit Cost</th>
                            <th scope="col">Total Person</th>
                            <th scope="col">Days</th>
                            <th scope="col">Total Cost</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="meal-tbl">
                        @foreach ($quotation->mealQuotations as $meal)
                        @include('quotations.meal_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="display: {{ isset($services->Transport) && $services->Transport == true ? 'block;' : 'none;' }}" id="transport-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Transport</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-transport-modal', ['quotationId' => $quotation->id]) }}" data-target="#quotationTransportModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Transport
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Meal Type</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Date</th>
                            <th scope="col">Unit Cost</th>
                            <th scope="col">Total Person</th>
                            <th scope="col">Days</th>
                            <th scope="col">Total Cost</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="transport-tbl">
                        @foreach ($quotation->transportQuotations as $transport)
                        @include('quotations.transport_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="display: {{ isset($services->Activities) && $services->Activities == true ? 'block;' : 'none;' }}" id="activities-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Activity</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-activity-modal', ['quotationId' => $quotation->id]) }}" data-target="#quotationActivityModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Activity
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Activity Title</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Date</th>
                            <th scope="col">Unit Cost</th>
                            <th scope="col">Total Person</th>
                            <th scope="col">Days</th>
                            <th scope="col">Total Cost</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="activity-tbl">
                        @foreach ($quotation->activityQuotations as $activity)
                        @include('quotations.activity_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" id="other-services-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Other Services</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-activity-modal', ['quotationId' => $quotation->id]) }}" data-target="#quotationActivityModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Other Service
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Service Title</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Date</th>
                            <th scope="col">Unit Cost</th>
                            <th scope="col">Total Person</th>
                            <th scope="col">Days</th>
                            <th scope="col">Total Cost</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotation->otherServicesQuotations as $activity)
                        @include('quotations.activity_quotation_table_row')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" id="optional-services-card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Optional Services</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-success btn-label btn-sm show-modal-itinerary" data-url="{{ route('add-quotation-activity-modal', ['quotationId' => $quotation->id]) }}" data-target="#quotationActivityModal">
                    <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add Optional Service
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Service Title</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Date</th>
                            <th scope="col">Unit Cost</th>
                            <th scope="col">Total Person</th>
                            <th scope="col">Days</th>
                            <th scope="col">Total Cost</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotation->optionalServicesQuotations as $activity)
                        @include('quotations.activity_quotation_table_row')
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

        $(document).on('click', '.services-check', function(e) {
            var target = $(this).data('target');
            if ($(this).is(":checked")) {
                $(target).show(1000);
            } else {
                $(target).hide(1000);
            }

            $.ajax({

                url: "{{ route('save-quotation-service-types') }}",
                type: "POST",
                data: $('#serviceTypesForm').serializeArray(),
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

        $(document).on('change', '.meal-date-type', function(e) {
            $('.meal-date-section').hide();
            var type = $(this).val();
            var target = $(this).data('target');
            // console.log($(this).val(), target);

            if (type == 'tour' || type == 'range') {
                $('#calcDaysDiv').show();
            } else {
                $('#calcDaysDiv').hide();
            }

            $(target).show();

        });

        $(document).on('submit', '#hotelForm', function(e) {

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

    $(document).on('click', '.delete-quotation-service', function(e) {
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
</script>
@endpush