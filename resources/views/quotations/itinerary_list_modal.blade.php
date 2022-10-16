<div class="modal fade" id="itineraryListModal" tabindex="-1" aria-labelledby="itineraryListModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Itinerary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                    @foreach ($itinerary_templates as $i)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="itinerary-{{ $i->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor-itinerary-{{ $i->id }}" aria-expanded="false" aria-controls="accor-itinerary-{{ $i->id }}">
                                {{ $i->templateTitle }}
                            </button>
                        </h2>
                        <div id="accor-itinerary-{{ $i->id }}" class="accordion-collapse collapse" aria-labelledby="itinerary-{{ $i->id }}" data-bs-parent="#accordionWithicon">
                            <div class="accordion-body">
                            @foreach ($i->templateDetails as $d)
                                <div class="card" id="itinerary-detail-{{ $d->id }}">
                                    <div class="card-body">
                                        <div class="d-flex position-relative">
                                            <img style="background-size: cover; background-image: url(https://admin.travelpakistani.com/assets/media/svg/icons/Files/Pictures1.svg)" src="{{ \Cloudder::show($d->photo); }}" class="flex-shrink-0 me-3 avatar-xl rounded" alt="...">
                                            <div>
                                                <h5 class="mt-0">Day-{{ $d->dayNo }} | Pickup-{{ $d->pickupTime }}</h5>
                                                <p>{{ $d->description }}</p>
                                                <a href="" data-itinerary_detail_id="{{ $d->id }}" data-target="#itinerary-detail-{{ $d->id }}" class="btn btn-success btn-label btn-sm  add-existing-itinerary">
                                                    <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Add Itinerary Template
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Back</button>
                <button type="button" class="btn btn-primary">Send message</button>
            </div> -->
        </div>
    </div>
</div>
