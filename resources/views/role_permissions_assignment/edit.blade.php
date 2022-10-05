@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Role Assignment</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('roles-permission-assignment-list') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row g-3 needs-validation"
                        action="{{ route('assign-role-permissions', isset($user->id) ? $user->id : '') }}" method="POST"
                        novalidate>
                        @csrf
                        <div class="col-md-12 col-sm-12">
                            <div class="form-label-group in-border">
                                <label for="name" class="form-label">User name</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                            </div>
                        </div>
                        <div class="border mt-3 border-dashed"></div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-header">Roles</div>
                                    <div class="card-body">
                                        <div class="row justify-content-left">
                                            @foreach ($roles as $role)
                                                <div class="col-xl-3 col-md-6">
                                                    <!-- card -->
                                                    <div class="card card-animate">
                                                        <div class="card-body" data-aos="flip-up">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1">
                                                                    <p class="fw-medium text-muted mb-0">{{ $role->name }}
                                                                    </p>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <h5 class="text-success fs-14 mb-0">
                                                                        <input type="checkbox"
                                                                            @if ($role->assigned && !$role->isRemovable) class="form-checkbox focus:shadow-none focus:border-transparent text-gray-500 h-4 w-4"
                                                                @else
                                                                    class="form-checkbox h-4 w-4" @endif
                                                                            name="roles[]" value="{{ $role->id }}"
                                                                            {!! $role->assigned ? 'checked' : '' !!} {!! $role->assigned && !$role->isRemovable ? 'onclick="return false;"' : '' !!}>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-end justify-content-between mt-4">
                                                                <div>
                                                                    {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $role->display_name }}</h4> --}}
                                                                    <small
                                                                        class="text-fade">{{ $role->description }}</small>
                                                                </div>
                                                                <div class="avatar-sm flex-shrink-0">
                                                                    <span class="avatar-title bg-soft-warning rounded fs-3">
                                                                        <i class="bx bx-user-circle text-warning"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div><!-- end card body -->
                                                    </div>
                                                    <!-- end card -->
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>



                                {{-- <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Box</th>
                                                        <th>name</th>
                                                        <th>Display name</th>
                                                        <th>Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($roles as $role)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox"
                                                            @if ($role->assigned && !$role->isRemovable)
                                                                class="form-checkbox focus:shadow-none focus:border-transparent text-gray-500 h-4 w-4"
                                                            @else
                                                                class="form-checkbox h-4 w-4"
                                                            @endif
                                                            name="roles[]" value="{{$role->id}}"
                                                            {!! $role->assigned ? 'checked' : '' !!}
                                                            {!! $role->assigned && !$role->isRemovable ? 'onclick="return false;"' : '' !!}
                                                            >
                                                        </td>
                                                        <td>{{ $role->name }}</td>
                                                        <td>{{ $role->display_name }}</td>
                                                        <td>{{ $role->description }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>

                            <div class="border mt-3 border-dashed"></div>

                            <div class="row">
                                <div class="col-12">
                                    @if ($permissions)
                                        <div class="card mb-3">
                                            <div class="card-header">Permissions</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($permissions as $permission)
                                                        <div class="col-xl-3 col-md-6">
                                                            <!-- card -->
                                                            <div class="card card-animate">
                                                                <div class="card-body" data-aos="flip-left">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-grow-1">
                                                                            <p class="fw-medium text-muted mb-0">
                                                                                {{ $permission->name }}</p>
                                                                        </div>
                                                                        <div class="flex-shrink-0">
                                                                            <h5 class="text-success fs-14 mb-0">
                                                                                <input type="checkbox"
                                                                                    class="form-checkbox h-4 w-4"
                                                                                    name="permissions[]"
                                                                                    value="{{ $permission->id }}"
                                                                                    {!! $permission->assigned ? 'checked' : '' !!}>
                                                                            </h5>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $permission->display_name }}</h4> --}}
                                                                            <small
                                                                                class="text-muted">{{ $permission->description }}</small>
                                                                        </div>
                                                                        <div class="avatar-sm flex-shrink-0">
                                                                            <span
                                                                                class="avatar-title bg-soft-success rounded fs-3">
                                                                                <i class="bx bx-key text-success"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div><!-- end card body -->
                                                            </div>
                                                            <!-- end card -->
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="row">
                            <div class="col-12">
                                @if ($permissions)
                                    <div class="card mb-3">
                                        <div class="card-header">Permissions</div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>Box</th>
                                                            <th>name</th>
                                                            <th>Display name</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($permissions as $permission)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="form-checkbox h-4 w-4" name="permissions[]" value="{{$permission->id}}"
                                                                {!! $permission->assigned ? 'checked' : '' !!}>
                                                            </td>
                                                            <td>{{ $permission->name }}</td>
                                                            <td>{{ $permission->display_name }}</td>
                                                            <td>{{ $permission->description }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}

                            <div class="col-12 text-end">
                                @if (isset($user->id))
                                    <button class="btn btn-primary" type="submit">Update</button>
                                @endif
                                <a href="{{ route('roles-permission-assignment-list') }}"
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
                    $("#name").val(display_name.val().split(" ").join("_").toLowerCase());
                });
            });
        </script>
    @endpush
