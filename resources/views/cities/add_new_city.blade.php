{{-- <div class="row"> --}}
<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add City</h4>
        </div>

        <div class="card-body">
            <form class="row  needs-validation" action="{{ route('cities.store') }}" method="POST" novalidate>
                @csrf
                <div class="col-md-6 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                            id="name" name="name" placeholder="Please Enter" value="{{ old('name') }}"
                            required>
                        <div class="invalid-tooltip">
                            @if ($errors->has('name'))
                                {{ $errors->first('name') }}
                            @else
                                Name is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 mb-3">
                    <div class="form-label-group in-border">
                        <label for="abbreviation" class="form-label">Abbreviation</label>
                        <input type="text" class="form-control @if ($errors->has('abbreviation')) is-invalid @endif"
                            id="abbreviation" name="abbreviation" placeholder="Please Enter"
                            value="{{ old('abbreviation') }}">
                        <div class="invalid-tooltip">
                            @if ($errors->has('abbreviation'))
                                {{ $errors->first('abbreviation') }}
                            @else
                                Abbreviation is required!
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                    <a href="{{ route('cities.index') }}" type="button"
                        class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- </div> --}}
