<div class="modal fade" id="quotationMealModal" tabindex="1" aria-labelledby="quotationMealModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="needs-validation" data-target-modal="#quotationMealModal" data-render-tbl="meal-tbl" id="hotelForm" method="POST" action="{{ route('save-quotation-meal') }}" novalidate>
                <input type="hidden" value="{{ isset($itineraryQuotation->id) ? $itineraryQuotation->id : 0 }}" name="quotationId" />
                <input type="hidden" value="{{ isset($itineraryQuotation->versionNo) ? $itineraryQuotation->versionNo : 0 }}" name="versionNo" />
                <input type="hidden" value="{{ isset($service->id) ? $service->id : 0 }}" name="serviceId" />
                <input type="hidden" value="Meal" name="serviceType" />
                <div class="modal-header">
                    <h5 class="modal-title">Meal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0" role="alert" style="display: none;">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input meal-date-type" type="radio" name="serviceDateType" id="mealDateSingle" value="day" data-target="#daySection" {{ isset($service) && $service->serviceDateType == 'day' ? 'checked' : '' }} {{ isset($service) ? '' : 'checked' }}>
                                <label class="form-check-label" for="mealDateSingle">Single Day</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input meal-date-type" type="radio" name="serviceDateType" id="mealDateRange" value="range" data-target="#rangeSection" {{ isset($service) && $service->serviceDateType == 'range' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mealDateRange">Date Range</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input meal-date-type" type="radio" name="serviceDateType" id="mealDateType" value="tour" data-target="#tourSection" {{ isset($service) && $service->serviceDateType == 'tour' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mealDateType">Apply to Tpur</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input meal-date-type" type="radio" name="serviceDateType" id="mealNoDate" value="nodate" data-target="#noDateSection" {{ isset($service) && $service->serviceDateType == 'nodate' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mealNoDate">No Date</label>
                            </div>
                        </div>

                        <div class="meal-date-section col-md-6" id="daySection" style="display:{{ isset($service) && $service->serviceDateType == 'day' ? 'block' : 'none' }} {{ isset($service) ? 'block' : 'none' }}">
                            <div class="form-label-group in-border">
                                <label for="serviceDate" class="form-label">Date <span class="text-danger">*</span></label>
                                <input value="{{ isset($service) ? $service->serviceDate : '2022-07-22' }}" class="form-control single-date" name="serviceDate" id="serviceDate" placeholder="Please select date">
                            </div>
                        </div>
                        <div class="meal-date-section col-md-6" id="rangeSection" style="display:{{ isset($service) && $service->serviceDateType == 'range' ? 'block' : 'none' }}">
                            <div class="form-label-group in-border">
                                <label for="serviceDuration" class="form-label">Date Range <span class="text-danger">*</span></label>
                                <input value="{{ isset($service) ? $service->serviceDate.' to '.$service->serviceEndDate : '2022-07-22 to 2022-07-28' }}" class="form-control duration" name="serviceDuration" id="serviceDuration" placeholder="Please select duration">
                            </div>
                        </div>
                        <div class="meal-date-section col-md-6" id="tourSection" style="display:{{ isset($service) && $service->serviceDateType == 'tour' ? 'block' : 'none' }}">

                        </div>
                        <div class="meal-date-section col-md-6" id="noDateSection" style="display:{{ isset($service) && $service->serviceDateType == 'nodate' ? 'block' : 'none' }}">

                        </div>

                        <div class="col-md-6">
                            <label for="mealType" class="form-label">Meal Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="description" id="mealType">
                                <option value="Breakfast">Breakfast</option>
                                <option value="Brunch">Brunch</option>
                                <option value="Dinner">Dinner</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Breakfast &amp; Dinner">Breakfast &amp; Dinner</option>
                                <option value="Breakfast, Lunch &amp; Dinner">Breakfast, Lunch &amp; Dinner</option>
                                <option value="Lunch &amp; Dinner">Lunch &amp; Dinner</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="unitCost" class="form-label">Meal Cost <span class="text-danger">*</span></label>
                            <input type="number" value="{{ isset($service->unitCost) ? $service->unitCost : '' }}" class="form-control" id="unitCost" placeholder="Enter meal cost" name="unitCost" required>
                        </div>

                        <div class="col-md-6">
                            <label for="totalUnits" class="form-label">Persons <span class="text-danger">*</span></label>
                            <input type="number" value="{{ isset($service->totalUnits) ? $service->totalUnits : '' }}" class="form-control" id="totalUnits" placeholder="Enter total no. of persons" name="totalUnits" required>
                        </div>

                        @if($itineraryQuotation->markupType == 'Individual')
                        <div class="col-md-6">
                            <label for="markupType" class="form-label">Markup Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="markupType" name="markupType">
                                <option value="Flat">Flat</option>
                                <option value="Percentage">Percentage</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="markupValue" class="form-label">Markup <span class="text-danger">*</span></label>
                            <input type="number" value="{{ isset($service->markupValue) ? $service->markupValue : '' }}" class="form-control" id="markupValue" placeholder="Enter markup" name="markupValue" required>
                        </div>
                        @endif

                        <div class="col-md-12">
                            <label for="instructions" class="form-label">Remarks (If any) </label>
                            <textarea class="form-control" id="instructions" placeholder="Enter remarks" name="instructions" required>{{ isset($service->instructions) ? $service->instructions : '' }}</textarea>
                        </div>

                        <div class="col-sm-12" id="calcDaysDiv" style="display: {{ isset($service) && ($service->serviceDateType == 'tour' || $service->serviceDateType == 'range') ? 'block' : 'none' }}">
                            <div class="form-check form-switch form-check-inline form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input" id="calcDaysMeal" name="calcDaysMeal" {{ isset($service->calculateByDays) && $service->calculateByDays == true ? 'checked' : '' }}>
                                <label for="calcDaysMeal" class="form-check-label">Calculate Everyday</label>
                            </div>
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