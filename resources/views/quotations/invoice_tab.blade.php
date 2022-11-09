<div class="tab-pane {{ $tab == 7 ? 'active' : '' }}" id="nav-border-top-06" role="tabpanel">

    <form class="needs-validation" novalidate method="POST" action="{{ route('save-quotation-invoice') }}">

        @csrf
        <input type="hidden" value="{{ isset($quotation) ? $quotation->id : 0 }}" name="quotationId" />
        <input type="hidden" value="{{ isset($quotation) ? $quotation->versionNo : 1 }}" name="quotationVersion" />
        <div class="table-responsive ">
            <table class="table align-middle table-nowrap  mb-3">
                <thead class="table-light">
                    <tr>
                        <td scope="col"><strong>Total Amount:</strong> {{ number_format($discountedAmount) }}</td>
                        <td scope="col"><strong>Total Paid:</strong> {{ number_format($totalPaid) }} </td>
                        <td scope="col"><strong>Total Remaining:</strong> {{ number_format($totalRemaining) }} </td>
                        <td scope="col" style="width: 100px;">
                            <a href="{{ route('create-quotation-invoice-pdf',$quotation->id.'?download=1') }}" class="btn btn-info btn-label btn-sm">
                                <i class="ri-file-download-line label-icon align-middle fs-16 me-2"></i> Download
                            </a>
                            <a href="{{ route('create-quotation-invoice-pdf',$quotation->id.'?print=1') }}" class="btn btn-danger btn-label btn-sm">
                                <i class="ri-printer-line label-icon align-middle fs-16 me-2"></i> Print
                            </a>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="table-responsive ">
            <table class="table align-middle table-nowrap  mb-3">
                <thead class="table-light">
                    <tr>
                        <th>Sr#</th>
                        <th>Paid Date</th>
                        <th>Total Amount</th>
                        <th>Due Amount</th>
                        <th>Remaining Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quotation->quotationInvoices as $k => $invoice)
                    <tr>
                        <td>{{ $k+1 }}</td>
                        <td>Due Date</td>
                        <td>Paid Date</td>
                        <td>{{ number_format($invoice->totalAmount) }}</td>
                        <td>{{ number_format($invoice->dueAmount) }}</td>
                        <td>{{ number_format($invoice->remainingAmount) }}</td>
                        <td style="width: 50px;">
                            @if($invoice->staus == 0)
                            <a href="{{ route('add-quotation-itinerary-modal') }}" class="btn btn-success btn-label btn-sm">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Mark Paid
                            </a>
                            @else
                            <a href="{{ route('add-quotation-itinerary-modal') }}" class="btn btn-success btn-label btn-sm">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Paid
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="alert alert-light alert-dismissible alert-solid alert-label-icon fade show" role="alert">
                                <i class="ri-question-line label-icon"></i><strong>Info</strong> -
                                No itinerary added to quotation.
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row g-3">
            <div class="col-md-12">
                <label for="staffRemarks" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" placeholder="Enter description" name="description" required></textarea>
            </div>
            <div class="col-md-3">
                <label for="dueAmount" class="form-label">Amount <span class="text-danger">*</span></label>
                <input type="number" value="" class="form-control" id="dueAmount" placeholder="Enter due amount" name="dueAmount" required>
            </div>
            <div class="col-md-3">
                <label for="remainingAmount" class="form-label">Remaining Amount </label>
                <input type="number" value="{{ $totalRemaining }}" class="form-control" id="remainingAmount" placeholder="Enter remaining amount" name="remainingAmount" readonly required>
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
