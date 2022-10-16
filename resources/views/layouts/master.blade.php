<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>Travel Pakistani</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('layouts.header_scripts')
    @stack('header_scripts')

</head>

<body>

    <div id="layout-wrapper">

        @include('layouts.header')


        {{-- @role('customer')
                @include('layouts.customer_side_nav')
            @endrole

            @role('staff') --}}
        @include('layouts.side_nav')
        {{-- @endrole --}}

        <div class="vertical-overlay"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    @include('layouts.breadcrum')
                    @yield('content')

                </div>
            </div>

            @include('layouts.footer')

        </div>
    </div>

    <div id="modal-div"></div>

    <div id="detailsModal" class="modal fade" tabindex="-1" aria-labelledby="detailsModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Modal Heading</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <div style="z-index: 11">
        <div id="toastBody" class="toast toast-border-primary overflow-hidden mt-3 fade hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        <i class="ri-user-smile-line align-middle"></i>
                    </div>
                    <div class="flex-grow-1" id="toastBodyText">
                        <h6 class="mb-0">Your application was successfully sent.</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.theme_setting')
    @include('layouts.footer_scripts')

    @stack('footer_scripts')


</body>

</html>