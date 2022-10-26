<div class="tab-pane {{ $tab == 1 ? 'active' : '' }}" id="nav-border-top-01" role="tabpanel">
    <form class="row  needs-validation row g-3" action="{{ route('quotation-store') }}" method="POST" enctype='multipart/form-data' novalidate>
        @csrf
        <input type="hidden" id="quoteStatus" name="quoteStatus" value="8">
        <input type="hidden" id="quotationId" name="quotationId" value="{{ isset($quotation) ? $quotation->id : 0 }}">

        @if(isset($quotation) && $quotation->id > 0)
        <div class="col-md-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isNew" id="editOnly" value="0" checked>
                <label class="form-check-label" for="editOnly">Edit Only</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isNew" id="createNewVersion" value="1">
                <label class="form-check-label" for="createNewVersion">Create New Version</label>
            </div>
        </div>
        @endif

        <div class="col-md-12">
            <label for="quotationsTitle" class="form-label">Quotation Title <span class="text-danger">*</span></label>
            <input type="text" value="{{ isset($quotation->quotationsTitle) ? $quotation->quotationsTitle : '' }}" class="form-control" id="quotationsTitle" placeholder="Enter quotation title" name="quotationsTitle" required>
        </div>
        <div class="col-md-6">
            <label for="clientName" class="form-label">Client Name <span class="text-danger">*</span></label>
            <input type="text" value="{{ isset($quotation->clientName) ? $quotation->clientName : '' }}" class="form-control" id="clientName" placeholder="Enter client name" name="clientName" required>
        </div>
        <div class="col-md-6">
            <label for="clientEmail" class="form-label">Client Email Quotation to Client? <span class="text-danger">*</span></label>
            <input type="email" value="{{ isset($quotation->clientEmail) ? $quotation->clientEmail : '' }}" class="form-control" id="clientEmail" placeholder="Enter email" name="clientEmail" required>
        </div>
        <div class="col-md-6">
            <label for="clientContact" class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="text" value="{{ isset($quotation->clientContact) ? $quotation->clientContact : '' }}" class="form-control" id="clientContact" placeholder="Enter phone" name="clientContact" required>
        </div>
        <div class="col-md-6">
            <label for="cityId" class="form-label">City <span class="text-danger">*</span></label>
            <select class="form-control" id="cityId" placeholder="Select city" name="cityId" required>
                <option value="">Please select</option>
                @foreach ($cities as $city)
                <option value="{{ $city->city_id }}" {{ $city->city_id == $quotation->cityId ? 'selected' : '' }}>{{ $city->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-label-group in-border">
                <label for="tourDates" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                <input value="{{ isset($quotation) ? $quotation->tourFrom.' to '.$quotation->tourEnd : '' }}" class="form-control" name="tourDates" id="tourDates" placeholder="Please Enter" required>
            </div>
        </div>
        <div class="col-md-6">
            <label for="validity" class="form-label">Validity <span class="text-danger">*</span></label>
            <input value="{{ isset($quotation->validity) ? $quotation->validity : '' }}" type="text" class="form-control" id="validity" placeholder="Enter validity" name="validity" required>
        </div>
        <div class="col-md-6">
            <label for="adults" class="form-label">Adults <span class="text-danger">*</span></label>
            <input type="number" value="{{ isset($quotation->adults) ? $quotation->adults : '' }}" class="form-control" id="adults" placeholder="Enter adults" name="adults" required>
        </div>
        <div class="col-md-6">
            <label for="children" class="form-label">Children <span class="text-danger">*</span></label>
            <input type="number" value="{{ isset($quotation->children) ? $quotation->children : '' }}" class="form-control" id="children" placeholder="Enter children" name="children" required>
        </div>
        <div class="col-md-12">
            <label for="citiesToVisit" class="form-label">Cities to Visit <span class="text-danger">*</span></label>
            <select class="form-control" id="citiesToVisit" placeholder="Select cities" name="citiesToVisit[]" multiple required>
                @foreach ($cities as $city)
                <option {{ isset($quotation) && in_array($city->city_id, json_decode($quotation->citiesToVisit)) ? 'selected' : '' }} value="{{ $city->city_id }}">{{ $city->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <label for="otherAreas" class="form-label">Other Areas </label>
            <input type="text" value="{{ isset($quotation->otherAreas) ? $quotation->otherAreas : '' }}" class="form-control" id="otherAreas" placeholder="Enter otherAreas" name="otherAreas">
        </div>
        <div class="col-md-12">
            <label for="staffRemarks" class="form-label">Staff Remarks </label>
            <textarea class="form-control" id="staffRemarks" placeholder="Enter staffRemarks" name="staffRemarks">{{ isset($quotation->staffRemarks) ? $quotation->staffRemarks : '' }}</textarea>
        </div>
        <div class="col-md-12">
            <label for="processedBy" class="form-label">Managed By <span class="text-danger">*</span></label>
            <select class="form-control" id="processedBy" placeholder="Select cities" name="processedBy" required>
                <option value="">Please select</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ isset($quotation) && $quotation->userId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <label for="processedBy" class="form-label">Apply Markup <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="markupType" id="Individual" value="Individual" {{ isset($quotation) && $quotation->markupType == 'Individual' ? 'checked' : '' }}>
                <label class="form-check-label" for="Individual">Individual Item</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="markupType" id="Total" value="Total" {{ isset($quotation) && $quotation->markupType == 'Total' ? 'checked' : '' }}>
                <label class="form-check-label" for="Total">Total</label>
            </div>
        </div>

        <div class="col-12">
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>

@push('footer_scripts')
<script>
    $(document).ready(function() {

        $('#tourDates').flatpickr({
            // minDate: "today",
            mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#validity').flatpickr({
            minDate: "today",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#citiesToVisit').select2({
            dropdownAutoWidth: true,
            width: '100%',
            allowClear: true
        });

        $('#processedBy').select2({
            dropdownAutoWidth: true,
            width: '100%',
            // allowClear: true
        });


    });
</script>
@endpush