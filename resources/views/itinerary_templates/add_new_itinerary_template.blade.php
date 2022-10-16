<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add New Itinerary Template</h4>

            <div class="flex-shrink-0">
                <a href="{{ route('itinerary-templates.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="bx bx-arrow-back label-icon align-middle fs-16 me-2"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">

            <form class="row  needs-validation" action="{{ route('itinerary-templates.store') }}" method="POST"
                enctype='multipart/form-data' novalidate>
                @csrf

                <div class="col-md-6 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="templateTitle" class="form-label">Template Title</label>
                        <input type="text" class="form-control @if ($errors->has('templateTitle')) is-invalid @endif"
                            id="templateTitle" name="templateTitle" placeholder="Please Enter"
                            value="{{ old('templateTitle') }}" required>
                        <div class="invalid-tooltip">
                            @if ($errors->has('templateTitle'))
                                {{ $errors->first('templateTitle') }}
                            @else
                                Template Title is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-label-group in-border">
                        <label for="templateType" class="form-label">Template Type</label>
                        <select name="templateType" id="templateType"
                            class="form-select form-control mb-3 @if ($errors->has('templateType')) is-invalid @endif">
                            <option value="basic" @if (old('templateType' == 'basic')) selected @endif>
                                Basic</option>
                            <option value="detailed" @if (old('templateType' == 'detailed')) selected @endif>Detailed
                            </option>
                        </select>
                        <div class="invalid-tooltip">
                            @if ($errors->has('templateType'))
                                {{ $errors->first('templateType') }}
                            @else
                                Select the Type!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="form-label-group in-border">
                        <label for="categoryId" class="form-label">Categories</label>
                        <select
                            class="form-select form-control mb-3 @if ($errors->has('categoryId')) is-invalid @endif"
                            name="categoryId" required>
                            <option value="" @if (old('categoryId') == '') {{ 'selected' }} @endif selected
                                disabled>
                                Select One
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if (old('categoryId') == $category->id) {{ 'selected' }} @endif>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-tooltip">
                            @if ($errors->has('categoryId'))
                                {{ $errors->first('categoryId') }}
                            @else
                                Select the Category!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="form-label-group in-border">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id=""
                            class="form-select form-control mb-3 @if ($errors->has('status')) is-invalid @endif">
                            <option value="0" @if (old('status' == '0')) selected @endif>
                                In Active</option>
                            <option value="1" @if (old('status' == '1')) selected @endif>Active
                            </option>
                        </select>
                        <div class="invalid-tooltip">
                            @if ($errors->has('status'))
                                {{ $errors->first('status') }}
                            @else
                                Select the Type!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="form-label-group in-border d-flex flex-column">
                        <label for="totalDays" class="form-label">Template Type</label>
                        <div class="input-step full-width">
                            <button type="button" class="minus">–</button>
                            <input type="number" name="totalDays"
                                class="form-control @if ($errors->has('totalDays')) is-invalid @endif"
                                value="{{ old('totalDays') || 2 }}" min="0" max="100" readonly>
                            <button type="button" class="plus">+</button>
                        </div>
                        <div class="invalid-tooltip">
                            @if ($errors->has('totalDays'))
                                {{ $errors->first('totalDays') }}
                            @else
                                Select the Type!
                            @endif
                        </div>
                    </div>
                </div>



                <div class="col-12 text-end">
                    <button id="submit_btn" class="btn btn-primary" type="submit">Save Changes</button>
                    <a href="{{ route('itinerary-templates.index') }}" type="button"
                        class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
