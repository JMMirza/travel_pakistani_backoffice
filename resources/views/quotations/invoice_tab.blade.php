<div class="tab-pane {{ $tab == 7 ? 'active' : '' }}" id="nav-border-top-06" role="tabpanel">

    <form class="needs-validation" novalidate method="POST" action="{{ route('save-quotation-invoice') }}">

        @csrf
        <input type="hidden" value="{{ isset($quotation) ? $quotation->id : 0 }}" name="quotationId" />
        <input type="hidden" value="{{ isset($quotation) ? $quotation->versionNo : 1 }}" name="quotationVersion" />
        <div class="table-responsive ">
            <table class="table align-middle table-nowrap  mb-3">
                <thead class="table-light">
                    <tr>
                        <td scope="col"><strong>Total Amount:</strong> </td>
                        <td scope="col"><strong>Total Paid:</strong> </td>
                        <td scope="col"><strong>Total Remaining:</strong> </td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="row g-3">
            <div class="col-md-12">
                <label for="staffRemarks" class="form-label">Description </label>
                <textarea class="form-control" id="description" placeholder="Enter description" name="description"></textarea>
            </div>
            <div class="col-md-3">
                <label for="dueAmount" class="form-label">Amount <span class="text-danger">*</span></label>
                <input type="number" value="" class="form-control" id="dueAmount" placeholder="Enter due amount" name="dueAmount" required>
            </div>
            <div class="col-md-3">
                <label for="remainingAmount" class="form-label">Remaining Amount </label>
                <input type="number" value="" class="form-control" id="remainingAmount" placeholder="Enter remaining amount" name="remainingAmount" readonly required>
            </div>
            <div class="col-md-3">
                <label for="invoiceDate" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                <input value="" type="text" class="form-control" id="invoiceDate" placeholder="Enter due date" name="invoiceDate" required>
            </div>
            <div class="col-md-3">
                <label for="dueDate" class="form-label">Due Date <span class="text-danger">*</span></label>
                <input value="" type="text" class="form-control" id="dueDate" placeholder="Enter due date" name="dueDate" required>
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-light">Back</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>

@push('footer_scripts')
<script>
    $(document).ready(function() {

        $('#dueDate').flatpickr({
            minDate: "today",
            defaultDate: "today",
            // mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#invoiceDate').flatpickr({
            // minDate: "today",
            // mode: "range",
            defaultDate: "today",

            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

    });
</script>
@endpush