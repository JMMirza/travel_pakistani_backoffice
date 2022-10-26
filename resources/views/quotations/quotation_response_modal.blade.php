<div id="shareholderModel" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Quotation Response</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="passport_no" class="form-label">Response</label>
                            <textarea name="response" id="response" cols="30" rows="5" class="form-control">{{ $response->feedback }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="passport_no" class="form-label">Response</label>
                            <input type="text" name="rating" class="form-control" value="{{ $response->rating }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border mb-3">
                            <label for="cityId" class="form-label">Change Status</label>
                            <select class="form-select form-control mb-3" name="status">
                                <option value="" selected disabled>
                                    Select One
                                </option>
                                <option value="viewed">
                                    Viewed
                                </option>
                                <option value="complete">
                                    Complete
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
