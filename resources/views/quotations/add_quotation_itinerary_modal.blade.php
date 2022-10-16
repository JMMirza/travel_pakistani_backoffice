<div class="modal fade" id="quotationItineraryModal" tabindex="1" aria-labelledby="itineraryListModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="itineraryQuotationForm">
                <input type="hidden" value="{{ isset($itineraryQuotation->id) ? $itineraryQuotation->id : 0 }}" name="itineraryQuotationId" />
                <div class="modal-header">
                    <h5 class="modal-title">Add Itinerary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="itineraryDay" class="form-label">Day <span class="text-danger">*</span></label>
                            <select class="form-control" name="itineraryDay" id="itineraryDay">
                                @foreach ($days as $day)
                                    <option value="{{ $day['id'] }}" {{ isset($itineraryQuotation) && $day["id"] == $itineraryQuotation->day ? 'selected' : '' }}>{{ $day['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" value="{{ isset($itineraryQuotation->title) ? $itineraryQuotation->title : '' }}" class="form-control" id="title" placeholder="Enter quotation title" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="landmarkTypes" class="form-label">Landmark Category</label>
                            <select class="form-control landmark-filters" name="landmarkTypes" id="landmarkTypes">
                                @foreach ($landmarkCategories as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="itineraryCities" class="form-label">City </label>
                            <select class="form-control landmark-filters" name="itineraryCities" id="itineraryCities">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->city_id }}">{{ $city->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="landmarkList" class="form-label">Landmark</label>
                            <select class="form-control" name="landmarkList[]" id="landmarkList" multiple>
                                
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="itineraryDescription" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="itineraryDescription" placeholder="Enter quotation description" name="itineraryDescription" required>{{ isset($itineraryQuotation->details) ? $itineraryQuotation->details : '' }}</textarea>
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