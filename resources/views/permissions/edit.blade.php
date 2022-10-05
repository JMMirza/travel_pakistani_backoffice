@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit a permission</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('permissions.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row g-3 needs-validation"
                        action="{{ route('permissions.update', isset($permission->id) ? $permission->id : '') }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PATCH')
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="name" class="form-label">Permission name</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                                    name="name" placeholder="Permission Name" value="{{ $permission->name }}" readonly
                                    required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('name'))
                                        {{ $errors->first('name') }}
                                    @else
                                        Permission name is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="display_name" class="form-label">Permission display name</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('display_name')) is-invalid @endif"
                                    id="display_name" name="display_name" placeholder="Permission Display Name"
                                    value="{{ $permission->display_name }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('display_name'))
                                        {{ $errors->first('display_name') }}
                                    @else
                                        Permission display name is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="description" class="form-label">Permission display name</label>
                                <input type="text"
                                    class="form-control @if ($errors->has('description')) is-invalid @endif"
                                    id="description" name="description" placeholder="Description"
                                    value="{{ $permission->description }}" required>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('description'))
                                        {{ $errors->first('description') }}
                                    @else
                                        Permission display name is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="border mt-3 border-dashed"></div>

                        <div class="col-12 text-end">
                            @if (isset($permission->id))
                                <button class="btn btn-primary" type="submit">Update permission</button>
                            @endif
                            <a href="{{ route('permissions.index') }}"
                                class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection


    @push('header_scripts')
    @endpush

    @push('footer_scripts')
        <script type="text/javascript">
            $(function() {
                $("#display_name").on("focusout", function() {
                    var display_name = $(this);
                    $("#name").val(display_name.val().split(" ").join("-").toLowerCase());
                });
            });
        </script>
    @endpush
