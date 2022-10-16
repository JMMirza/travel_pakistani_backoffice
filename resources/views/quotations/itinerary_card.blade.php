<div class="card" id="quotation-itinerary-{{ $itinerary->id }}">
    <div class="card-body">
        <div class="d-flex position-relative">
            <img style="background-size: cover; background-image: url(https://admin.travelpakistani.com/assets/media/svg/icons/Files/Pictures1.svg)" src="{{ \Cloudder::show($itinerary->photo); }}" class="flex-shrink-0 me-3 avatar-xl rounded" alt="...">
            <div>
                <h5 class="mt-0">{{ $itinerary->title }}</h5>
                <p>{{ $itinerary->details }}</p>
                <a href="{{ route('add-quotation-itinerary-modal', ['id' => $itinerary->id, 'quotationId' => $itinerary->quotationable_id]) }}" data-url="{{ route('add-quotation-itinerary-modal', ['id' => $itinerary->id, 'quotationId' => $itinerary->quotationable_id]) }}" class="btn btn-primary btn-label btn-sm show-modal-itinerary" data-target="#quotationItineraryModal">
                    <i class="ri-pencil-line label-icon align-middle fs-16 me-2"></i> Edit
                </a>
                <a href="{{ route('remove-quotation-itinerary', $itinerary->id) }}" data-quotation_itinerary_id="{{ $itinerary->id }}" data-target="#quotation-itinerary-{{ $itinerary->id }}" class="btn btn-danger btn-label btn-sm  remove-quotation-itinerary">
                    <i class="ri-delete-bin-line label-icon align-middle fs-16 me-2"></i> Remove
                </a>
            </div>
        </div>
    </div>
</div>