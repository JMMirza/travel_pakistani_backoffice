<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add New Template</h4>

            <div class="flex-shrink-0">
                <a href="{{ route('templates.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="bx bx-arrow-back label-icon align-middle fs-16 me-2"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">

            <form class="row  needs-validation" action="{{ route('templates.store') }}" method="POST"
                enctype='multipart/form-data' novalidate>
                @csrf

                <div class="col-md-6 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @if ($errors->has('title')) is-invalid @endif"
                            id="title" name="title" placeholder="Please Enter" value="{{ old('title') }}"
                            required>
                        <div class="invalid-tooltip">
                            @if ($errors->has('title'))
                                {{ $errors->first('title') }}
                            @else
                                Title is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-label-group in-border">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type"
                            class="form-select form-control mb-3 @if ($errors->has('type')) is-invalid @endif">
                            <option value="cancellationPolicy" @if (old('type' == 'cancellationPolicy')) selected @endif>
                                Cancellation Policy</option>
                            <option value="bookingNotes" @if (old('type' == 'bookingNotes')) selected @endif>Booking Notes
                            </option>
                            <option value="paymentTerms" @if (old('type' == 'paymentTerms')) selected @endif>Payment Terms
                            </option>
                            <option value="freeText" @if (old('type' == 'freeText')) selected @endif>Free Text
                            </option>
                        </select>
                        <div class="invalid-tooltip">
                            @if ($errors->has('type'))
                                {{ $errors->first('type') }}
                            @else
                                Select the Type!
                            @endif
                        </div>
                    </div>
                    <div class="input-step">
                        <button type="button" class="minus">–</button>
                        <input type="number" class="product-quantity" value="2" min="0" max="100"
                            readonly>
                        <button type="button" class="plus">+</button>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @if ($errors->has('description')) is-invalid @endif" name="description" id="description"
                            cols="30" rows="10"></textarea>

                        <div class="invalid-tooltip">
                            @if ($errors->has('description'))
                                {{ $errors->first('description') }}
                            @else
                                Description is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button id="submit_btn" class="btn btn-primary" type="submit">Save Changes</button>
                    <a href="{{ route('landmarks.index') }}" type="button"
                        class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
