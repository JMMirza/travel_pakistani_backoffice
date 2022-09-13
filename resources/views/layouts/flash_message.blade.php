<!-- @if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{ session()->get('success') }}
    </div>
@endif


@if (true)
    @foreach ($errors->all() as $error)
        {{ $error }}<br/>
    @endforeach
@endif -->


@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-notification-off-line label-icon"></i><strong>Success</strong>
    - {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($message = Session::get('image'))
<div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-notification-off-line label-icon"></i><strong>Success</strong>
    - S3 bucket Path: {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-error-warning-line label-icon"></i><strong>Error</strong>
    - {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-alert-line label-icon"></i><strong>Warning</strong> -
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-airplay-line label-icon"></i><strong>Info</strong> -
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    Check the following errors :(
</div>
@endif
