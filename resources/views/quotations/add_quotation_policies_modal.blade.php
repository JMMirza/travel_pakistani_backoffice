<div class="modal fade" id="quotationPoliciesModal" tabindex="1" aria-labelledby="quotationPoliciesModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="needs-validation" data-target-modal="#quotationPoliciesModal" data-render-tbl="{{ strtolower($noteType) }}-tbl" id="notesForm" method="POST" action="{{ route('save-quotation-policy') }}" novalidate>
                <input type="hidden" value="{{ isset($itineraryQuotation->id) ? $itineraryQuotation->id : 0 }}" name="quotationId" />
                <input type="hidden" value="{{ isset($itineraryQuotation->versionNo) ? $itineraryQuotation->versionNo : 0 }}" name="versionNo" />
                <input type="hidden" value="{{ isset($note->id) ? $note->id : 0 }}" name="noteId" />
                <input type="hidden" value="{{ $noteType }}" name="noteType" />
                <div class="modal-header">
                    <h5 class="modal-title">Cancellation Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0" role="alert" style="display: none;">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="noteTemplates" class="form-label">Templates</label>
                            <select name="noteTemplates" id="noteTemplates" class="form-control">

                                <option value="">Please select</option>
                                @foreach ($noteTypes as $temp)
                                <option value="{{ $temp->id }}" data-description="{{ $temp->description }}">{{ $temp->title }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" value="{{ isset($note->title) ? $note->title : '' }}" class="form-control" id="title" placeholder="Enter title" name="title" required>
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea rows="4" class="form-control" id="description" placeholder="Enter Description" name="description" required>{{ isset($note->description) ? $note->description : '' }}</textarea>
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