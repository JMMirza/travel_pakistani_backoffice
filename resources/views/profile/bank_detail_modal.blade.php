 <div id="shareholderModel" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="myModalLabel">Bank Details</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
             </div>
             <div class="modal-body">
                 @if (isset($bank_detail))
                     <form class="row" id="shareholderForm"
                         action="{{ route('update-bank-detail', $bank_detail->id) }}" method="POST">
                         @csrf
                         @method('PUT')
                     @else
                         <form class="row" id="shareholderForm" action="{{ route('add-new-detail') }}"
                             method="POST">
                             @csrf
                 @endif
                 {{-- {{ dd('hello') }} --}}

                 <div class="col-12 col-md-6 mb-2">
                     <div class="form-group">
                         <label for="accountNo" class="form-label">Account Number</label>
                         <input type="text" name="accountNo"
                             value="{{ isset($bank_detail) ? $bank_detail->accountNo : old('accountNo') }}"
                             class="form-control  @if ($errors->has('accountNo')) is-invalid @endif"
                             placeholder="Please enter" required>
                     </div>
                 </div>
                 <div class="col-12 col-md-6 mb-2">
                     <div class="form-group">
                         <label for="passport_no" class="form-label">Account Title</label>
                         <input type="text" name="accountTitle"
                             value="{{ isset($bank_detail) ? $bank_detail->accountTitle : old('accountTitle') }}"
                             class="form-control @if ($errors->has('accountTitle')) is-invalid @endif"
                             placeholder="Please enter" required>
                     </div>
                 </div>


                 <div class="col-12 col-md-4 mb-2">
                     <div class="form-group">
                         <label for="passport_no" class="form-label">Bank Title</label>
                         <input type="text"
                             value="{{ isset($bank_detail) ? $bank_detail->bankName : old('bankName') }}"
                             name="bankName" class="form-control @if ($errors->has('bankName')) is-invalid @endif"
                             placeholder="Please enter" required>
                     </div>
                 </div>

                 <div class="col-12 col-md-4 mb-2">
                     <div class="form-group">
                         <label for="bankAddress" class="form-label">Bank Address</label>
                         <input type="text" name="bankAddress"
                             value="{{ isset($bank_detail) ? $bank_detail->bankAddress : old('bankAddress') }}"
                             class="form-control @if ($errors->has('bankAddress')) is-invalid @endif"
                             placeholder="Please enter" required>
                     </div>
                 </div>


                 <div class="col-12 col-md-4 mb-2">
                     <div class="form-group">
                         <label for="bankPhone" class="form-label">Bank Phone</label>
                         <input type="text" name="bankPhone"
                             value="{{ isset($bank_detail) ? $bank_detail->bankPhone : old('bankPhone') }}"
                             class="form-control @if ($errors->has('bankPhone')) is-invalid @endif"
                             placeholder="Please enter" required>
                     </div>
                 </div>
                 <div class="col-12 col-md-6 mb-2">
                     <div class="form-group">
                         <label for="passport_no" class="form-label">Swift Code</label>
                         <input type="text" name="swiftCode"
                             value="{{ isset($bank_detail) ? $bank_detail->swiftCode : old('swiftCode') }}"
                             class="form-control @if ($errors->has('swiftCode')) is-invalid @endif"
                             placeholder="Please enter" required>
                     </div>
                 </div>

                 <div class="col-12 col-md-6 mb-2">
                     <div class="form-group">
                         <label for="iban" class="form-label">IBAN</label>
                         <input type="text" name="IBAN"
                             value="{{ isset($bank_detail) ? $bank_detail->IBAN : old('IBAN') }}"
                             class="form-control @if ($errors->has('IBAN')) is-invalid @endif"
                             placeholder="Please enter" required>
                     </div>
                 </div>
             </div>

             <div class="col-md-12 text-end mb-2">
                 <button class="btn btn-primary" type="submit">Save
                     Changes</button>
                 <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
             </div>

             </form>
         </div>
     </div>
 </div>
 </div>
