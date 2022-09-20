<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>Sign Up | Travel Pakistani</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="{{ asset('theme/dist/default/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('theme/dist/default/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('theme/dist/default/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/dist/default/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('theme/dist/default/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />


</head>

<body>
    <style type="text/css">
        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg {
            max-width: 1000px !important;
        }
    </style>
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index" class="d-inline-block auth-logo">
                                    <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt=""
                                        height="20">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium ">Travel Pakistani</p>
                            <p class="mt-3 fs-15 fw-medium">Contents Management System</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create New Account</h5>
                                    <p class="text-muted">Get your free account now</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" novalidate method="POST"
                                        action="{{ route('operators.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" id="full_name" placeholder="Enter name"
                                                required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter Full Name
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username" value="{{ old('username') }}" id="username"
                                                placeholder="Enter username" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter username
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" id="useremail"
                                                placeholder="Enter email address" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter email
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="cityId" class="form-label">City <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-control mb-3" name="cityId" required>
                                                <option value=""
                                                    @if (old('cityId') == '') {{ 'selected' }} @endif
                                                    selected disabled>
                                                    Select One
                                                </option>
                                                <option value=""
                                                    @if (old('cityId') == '1') {{ 'selected' }} @endif>
                                                    Lahore
                                                </option>
                                                <option value=""
                                                    @if (old('cityId') == '2') {{ 'selected' }} @endif>
                                                    Karachi
                                                </option>
                                                {{-- @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        @if (old('cityId') == $category->id) {{ 'selected' }} @endif>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach --}}
                                            </select>
                                            @error('cityId')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please select city
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="regType" class="form-label">Registration Type <span
                                                    class="text-danger">*</span></label>
                                            <select id="regType" class="form-select form-control mb-3"
                                                name="regType" required>
                                                <option value=""
                                                    @if (old('regType') == '') {{ 'selected' }} @endif
                                                    selected disabled>
                                                    Select One
                                                </option>
                                                <option value="operator"
                                                    @if (old('regType') == 'operator') {{ 'selected' }} @endif>
                                                    Operator
                                                </option>
                                                <option value="staff"
                                                    @if (old('regType') == 'staff') {{ 'selected' }} @endif>
                                                    Staff
                                                </option>
                                            </select>
                                            @error('regType')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please select registration type
                                            </div>
                                        </div>

                                        <div class="mb-3" id="companyTitleDiv" style="display: none">
                                            <label for="companyTitle" class="form-label">Company Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('companyTitle') is-invalid @enderror"
                                                name="companyTitle" value="{{ old('companyTitle') }}"
                                                id="companyTitle" placeholder="Enter company title" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter Company Title
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="userpassword" placeholder="Enter password"
                                                required>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please enter password
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="input-password">Confirm Password</label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" id="input-password"
                                                placeholder="Enter Confirm Password" required>

                                            <div class="form-floating-icon">
                                                <i data-feather="lock"></i>
                                            </div>
                                        </div>

                                        <div class="mb-4 form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="auth-remember-check" name="remember"
                                                {{ old('remember') ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="auth-remember-check">By registering
                                                you agree to the
                                                Travel Pakistani <a href="#"
                                                    class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                    of Use</a></label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Sign Up</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Already have an account ? <a href="{{ route('login') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by
                            Themesbrand</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <div id="investorModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Crop Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript">
        var $modal = $('#investorModal');
        var image = document.getElementById('image');
        var platformCropper;

        $("body").on("change", ".image", function(e) {
            var files = e.target.files;
            var done = function(url) {
                image.src = url;
                $modal.modal('show');
            };
            var reader;
            var file;
            var url;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function() {
            platformCropper = new Cropper(image, {
                aspectRatio: 60 / 50,
                viewMode: 1,
                dragMode: crop,
                preview: '.preview',
                cropBoxResizable: true,
                cropBoxMovable: true,
                cropstart: function(e) {
                    console.log(e.type, e.detail);
                }
            });
        }).on('hidden.bs.modal', function() {
            platformCropper.destroy();
            platformCropper = null;
        });


        $("#crop").click(function() {
            $modal.modal('hide');
            canvas = platformCropper.getCroppedCanvas({});
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $('#base64data').val(base64data);
                }
            });
        });

        $(document).ready(function() {
            $("#regType").change(function() {
                var selected_option = $('#regType').val();
                console.log(selected_option)
                if (selected_option == 'operator') {
                    $('#companyTitleDiv').css("display", "block");
                } else {
                    $('#companyTitleDiv').css("display", "none");
                }
            });
        });
    </script>
    <script src="{{ asset('theme/dist/default/assets/libs/particles.js/particles.js.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/js/pages/particles.app.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/js/pages/form-validation.init.js') }}"></script>


    <!-- password-addon init -->
    <script src="{{ asset('theme/dist/default/assets/js/pages/password-addon.init.js') }}"></script>
</body>

</html>
