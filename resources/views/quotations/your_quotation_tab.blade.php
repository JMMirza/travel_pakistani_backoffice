<div class="tab-pane {{ $tab == 6 ? 'active' : '' }}" id="nav-border-top-06" role="tabpanel">
    <form class="needs-validation" id="quotationForm" novalidate action="{{ route('save-quotation') }}" method="POST">
        @csrf
        <input type="hidden" value="{{ isset($quotation->id) ? $quotation->id : 0 }}" name="quotationId" />
        <input type="hidden" value="{{ isset($totalPersons) ? $totalPersons : 0 }}" name="personsCount" id="personsCount" />
        <div class="d-flex mb-3">
            <div class="flex-shrink-0">
                <i class="ri-checkbox-circle-line text-success"></i>
            </div>
            <div class="flex-grow-1 ms-2">
                Please Review the quotation before sending it to the customer
            </div>
        </div>

        <div class="table-responsive ">
            <table class="table align-middle table-nowrap  mb-3">
                <thead class="table-light">
                    <tr>
                        <td scope="col"><strong>Client Name:</strong> {{ $quotation->clientName }}</td>
                        <td scope="col"><strong>Phone:</strong> {{ $quotation->clientContact }}</td>
                        <td scope="col"><strong>Email:</strong> {{ $quotation->clientEmail }}</td>
                    </tr>
                    <tr>
                        <td scope="col"><strong>City:</strong> {{ $quotation->clientContact }}</td>
                        <td scope="col"><strong>Preferred Dates:</strong> {{ $quotation->tourFrom->format('M j, Y') }} to {{ $quotation->tourEnd->format('M j, Y') }}</td>
                        <td scope="col"><strong>Validity:</strong> {{ $quotation->validity->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <td scope="col"><strong>Adults:</strong> {{ $quotation->adults }}</td>
                        <td scope="col"><strong>Children:</strong> {{ $quotation->children }}</td>
                        <td scope="col"><strong>Markup Type:</strong> <span class="text-success">{{ strtoupper($quotation->markupType) }}</span></td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-nowrap">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Total Cost</th>
                        <th scope="col">Markup Type</th>
                        <th scope="col">Markup Value</th>
                        <th scope="col">Final Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="col">
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input id="totalCost" name="totalCost" type="text" class="form-control quotation-input" placeholder="Total Cost" value="{{ number_format($totalCost) }}" min="0" readonly>
                            </div>
                        </td>
                        <td scope="col">
                            <select class="form-control quotation-input" id="markupTypeQuotation" name="markupTypeQuotation" {{ $quotation->markupType == 'Total' ? '' : 'readonly' }} }}>
                                <option value="">Please select</option>
                                <option value="Flat" {{ $quotation->markupTypeQuotation == 'Flat' ? 'selected' : '' }}>Flat</option>
                                <option value="Percentage" {{ $quotation->markupTypeQuotation == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                            </select>
                        </td>
                        <td scope="col">
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input id="extraMarkup" name="extraMarkup" class="form-control quotation-input" type="text" placeholder="Markup Value" value="0.0" {{ $quotation->markupType == 'Total' ? '' : 'readonly' }} min="0">
                            </div>
                        </td>
                        <td scope="col">
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input id="finalAmount" name="finalAmount" class="form-control quotation-input" type="text" placeholder="Final Amount" value="{{ number_format($finalAmount) }}" min="0" readonly>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <thead class="table-light">
                    <tr>
                        <th scope="col">Discount Type</th>
                        <th scope="col">Discount Value</th>
                        <th scope="col">Final Amount</th>
                        <th scope="col">Per Person Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="col">
                            <select class="form-control quotation-input" id="discountType" name="discountType">
                                <option value="">Please select</option>
                                <option value="Flat" {{ $quotation->discountType == 'Flat' ? 'selected' : '' }}>Flat</option>
                                <option value="Percentage" {{ $quotation->discountType == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                            </select>
                        </td>
                        <td scope="col">
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input id="discountValue" name="discountValue" class="form-control quotation-input" type="text" placeholder="Discount Value" value="{{ $quotation->discountValue }}" min="0">
                            </div>
                        </td>
                        <td scope="col">
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input id="discountedAmount" name="discountedAmount" class="form-control quotation-input" type="text" placeholder="Final Amount" value="{{ number_format($discountedAmount) }}" min="0">
                            </div>
                        </td>
                        <td scope="col">
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input id="perPersonCost" name="perPersonCost" class="form-control quotation-input" type="text" placeholder="Per Person Cost" value="{{ number_format(($discountedAmount/$totalPersons)) }}" min="0" readonly>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-nowrap">
                <tbody>
                    <tr>
                        <td style="width: 250px;">Email Quotation to Client?</td>
                        <td>
                            <div class="">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="email" id="email-yes" value="1" {{ $quotation->email == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email-yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="email" id="email-no" value="0" {{ $quotation->email == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email-no">No</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr id="emailOptionsSection" {{ $quotation->email == 1 ? 'style="display: block;"' : 'style="display: none;"' }}>
                        <td>Email Price Options</td>
                        <td>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="quotationEmailPriceItems" name="showCost" {{ $quotation->showCost == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="quotationEmailPriceItems">
                                    Show Cost Items on Quotation
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="quotationEmailPrice" name="showPrice" {{ $quotation->showPrice == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="quotationEmailPrice">
                                    Show Prices on Quotation
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showPerPersonCost">
                                <label class="form-check-label" for="showPerPersonCost" name="showPerPersonCost">
                                    Show Per Person Cost
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Approve Version</td>
                        <td>
                            <div class=" form-check form-switch form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input" id="approvedVersionId" name="approvedVersionId">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Save As Template</td>
                        <td>
                            <div class="form-check form-switch form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input" id="isTemplate" name="isTemplate" {{ $quotation->isTemplate == 1 ? 'checked' : '' }}>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Expired?</td>
                        <td>
                            <div class="form-check form-switch form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input" id="isExpired" name="isExpired" {{ $quotation->expiryReason ? 'checked' : '' }}>
                            </div>
                        </td>
                    </tr>
                    <tr id="expiryReasonSection" {{ strlen($quotation->expiryReason) > 0 ? 'style="display: block"' : 'style="display: none"' }}>
                        <td>Reason</td>
                        <td>
                            <select class=" form-control quotation-input" id="expiryReason" name="expiryReason">
                                <option value="">Please select</option>
                                <option value="Price was over budget" {{ $quotation->expiryReason == "Price was over budget" ? 'selected' : '' }}>Price was over budget</option>
                                <option value="Customer booked with other operator" {{ $quotation->expiryReason == "Customer booked with other operator" ? 'selected' : '' }}>Customer booked with other operator</option>
                                <option value="No availability on requested dates" {{ $quotation->expiryReason == "No availability on requested dates" ? 'selected' : '' }}>No availability on requested dates</option>
                                <option value="Customer travel plan changed" {{ $quotation->expiryReason == "Customer travel plan changed" ? 'selected' : '' }}>Customer travel plan changed</option>
                                <option value="Customer didn't Respond, Duplicate Inquiry" {{ $quotation->expiryReason == "Customer didn't Respond, Duplicate Inquiry" ? 'selected' : '' }}>Customer didn't Respond, Duplicate Inquiry</option>
                                <option value="Others" {{ $quotation->expiryReason == "Others" ? 'selected' : '' }}>Others</option>
                            </select>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="col-12 align-right">
            <button type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

@push('footer_scripts')
<script>
    $(document).ready(function() {

        $(':radio[name="email"]').change(function() {
            var emailQuotationClient = $(this).filter(':checked').val();

            if (emailQuotationClient == 1) {
                $('#emailOptionsSection').show();
            } else {
                $('#emailOptionsSection').hide();
            }
        });

        $('#isExpired').change(function() {
            var isExpired = $(this).is(':checked');
            console.log(isExpired);
            if (isExpired == true) {
                $('#expiryReasonSection').show();
            } else {
                $('#expiryReasonSection').hide();
            }
        });

        $(document).on('change keyup', '.quotation-input', function(e) {

            let output = parseFloat($('#totalCost').val().replace(/,/g, ''));

            var totalCost = parseFloat($('#totalCost').val().replace(/,/g, ''));
            var markupType = $('#markupTypeQuotation').find(":selected").val();
            var markupValue = parseFloat($('#extraMarkup').val().replace(/,/g, ''));
            var finalAmount = parseFloat($('#finalAmount').val().replace(/,/g, ''));
            var discountType = $('#discountType').find(":selected").val();
            var discountValue = parseFloat($('#discountValue').val().replace(/,/g, ''));
            var discountedAmount = parseFloat($('#discountedAmount').val().replace(/,/g, ''));
            var perPersonCost = parseFloat($('#perPersonCost').val().replace(/,/g, ''));
            var personsCount = parseFloat($('#personsCount').val().replace(/,/g, ''));

            var amount = 0;
            var discountAmount = 0;

            if (markupType == 'Flat' && markupValue > 0) {
                discountAmount = amount = parseInt(totalCost) + parseInt(markupValue);
            } else if (markupType == 'Percentage' && markupValue > 0) {
                discountAmount = amount = parseInt(totalCost) + parseInt((markupValue / 100) * totalCost);
            } else {
                discountAmount = amount = totalCost;
            }

            $('#finalAmount').val(amount);
            $('#discountedAmount').val(amount);

            totalCost = parseFloat($('#totalCost').val().replace(/,/g, ''));
            finalAmount = parseFloat($('#finalAmount').val().replace(/,/g, ''));

            if (discountType == 'Flat' && discountValue > 0) {
                discountAmount = parseInt(finalAmount) - parseInt(discountValue);
            } else if (discountType == 'Percentage' && discountValue > 0) {
                discountAmount = parseInt(finalAmount) - parseInt((discountValue / 100) * finalAmount);
            }

            $('#discountedAmount').val(discountAmount);
            $('#perPersonCost').val(discountAmount / personsCount);


        });
    });
</script>
@endpush