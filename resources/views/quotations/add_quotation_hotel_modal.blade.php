<div class="modal fade" id="quotationHotelModal" tabindex="1" aria-labelledby="quotationHotelModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="needs-validation" data-target-modal="#quotationHotelModal" data-render-tbl="hotel-tbl" id="hotelForm" method="POST" action="{{ route('save-quotation-hotel') }}" novalidate>
                <input type="hidden" value="{{ isset($itineraryQuotation->id) ? $itineraryQuotation->id : 0 }}" name="quotationId" />
                <input type="hidden" value="{{ isset($itineraryQuotation->versionNo) ? $itineraryQuotation->versionNo : 0 }}" name="versionNo" />
                <input type="hidden" value="{{ isset($hotel->id) ? $hotel->id : 0 }}" name="hotelId" />
                <input type="hidden" value="Hotel" name="serviceType" />
                <div class="modal-header">
                    <h5 class="modal-title">Hotel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0" role="alert" style="display: none;">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="hotelName" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                            <input type="text" value="{{ isset($hotel->hotelName) ? $hotel->hotelName : '' }}" class="form-control" id="hotelName" placeholder="Enter hotel name" name="hotelName" required>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-group in-border">
                                <label for="hotelDuration" class="form-label">Duration <span class="text-danger">*</span></label>
                                <input value="{{ isset($hotel) ? $hotel->checkIn.' to '.$hotel->checkout : '' }}" class="form-control duration" name="hotelDuration" id="hotelDuration" placeholder="Please select duration" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="nights" class="form-label">Total Nights <span class="text-danger">*</span></label>
                            <input type="number" value="{{ isset($hotel->nights) ? $hotel->nights : '5' }}" class="form-control" id="nights" placeholder="Enter total nights" name="nights" required>
                        </div>

                        <div class="col-md-6">
                            <label for="unitCost" class="form-label">Room Price <span class="text-danger">*</span></label>
                            <input type="number" value="{{ isset($hotel->unitCost) ? $hotel->unitCost : '' }}" class="form-control" id="unitCost" placeholder="Enter room price" name="unitCost" required>
                        </div>

                        <div class="col-md-6">
                            <label for="totalUnits" class="form-label">Total Rooms <span class="text-danger">*</span></label>
                            <input type="number" value="{{ isset($hotel->totalUnits) ? $hotel->totalUnits : '' }}" class="form-control" id="totalUnits" placeholder="Enter total no. of rooms" name="totalUnits" required>
                        </div>

                        @if($itineraryQuotation->markupType == 'Individual')
                        <div class="col-md-6">
                            <label for="markupType" class="form-label">Markup Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="markupType" name="markupType">
                                <option value="Flat" {{ isset($hotel) && $hotel->markupType == 'Flat' ? 'selected' : '' }}>Flat</option>
                                <option value="Percentage" {{ isset($hotel) && $hotel->markupType == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="markupValue" class="form-label">Markup <span class="text-danger">*</span></label>
                            <input type="number" value="{{ isset($hotel->markupValue) ? $hotel->markupValue : '' }}" class="form-control" id="markupValue" placeholder="Enter markup" name="markupValue" required>
                        </div>
                        @endif

                        <div class="col-md-12">
                            <label for="instructions" class="form-label">Instructions </label>
                            <textarea class="form-control" id="instructions" placeholder="Enter instructions" name="instructions" required>{{ isset($hotel->instructions) ? $hotel->instructions : '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>