@extends('layouts.master')
@section('content')
@include('layouts.flash_message')
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create Quotation</h4>

                @if(isset($quotation))
                <div class="flex-shrink-0">
                    <a href="https://travelpakistani.com/quotation/{{ $quotation->liveQuotation }}" class="btn btn-success btn-label btn-sm">
                        <i class="ri-file-list-3-line label-icon align-middle fs-16 me-2"></i> View Quotation
                    </a>
                    <a href="{{ route('staffs.index') }}" class="btn btn-success btn-label btn-sm">
                        <i class=" ri-chat-1-line label-icon align-middle fs-16 me-2"></i> Contact Custumer
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">


                <ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ isset($tab) && $tab == 1 ? 'active' : '' }}" href="{{ route('quotation-edit', $quotation_id); }}?tab=1">
                            Basic Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($tab) && $tab == 2 ? 'active' : '' }} {{ isset($quotation) ? '' : 'disabled' }}" href="{{ route('quotation-edit', $quotation_id); }}?tab=2">
                            Itinerary
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($tab) && $tab == 3 ? 'active' : '' }} {{ isset($quotation) ? '' : 'disabled' }}" href="{{ route('quotation-edit', $quotation_id); }}?tab=3">
                            Quote
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($tab) && $tab == 4 ? 'active' : '' }} {{ isset($quotation) ? '' : 'disabled' }}" href="{{ route('quotation-edit', $quotation_id); }}?tab=4">
                            Terms & Conditions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($tab) && $tab == 5 ? 'active' : '' }} {{ isset($quotation) ? '' : 'disabled' }}" href="{{ route('quotation-edit', $quotation_id); }}?tab=5">
                            Photos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($tab) && $tab == 6 ? 'active' : '' }} {{ isset($quotation) ? '' : 'disabled' }}" href="{{ route('quotation-edit', $quotation_id); }}?tab=6">
                            Your Quotation
                        </a>
                    </li>
                </ul>
                <div class="tab-content text-muted">

                    @include('quotations.basic_details_tab')

                    @if(isset($quotation))
                    @include('quotations.itinerary_tab')
                    @include('quotations.quote_tab')
                    @include('quotations.terms_conditions_tab')
                    @include('quotations.photos_tab')
                    @include('quotations.your_quotation_tab')
                    @endif

                </div>
            </div>
        </div>


    </div>
</div>
@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')

@endpush