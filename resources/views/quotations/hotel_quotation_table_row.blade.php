<tr id="hotel-{{ $hotel->id }}">
    <td>{{ $hotel->hotelName }}</td>
    <td>
        @if($hotel->instructions)
        <a tabindex="0" class="link-success" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" title="" data-bs-content="{{ $hotel->instructions }}" data-bs-original-title="Remarks">View Remarks</a>
        @else
        <p class="text-muted mb-0">N/A</p>
        @endif
    </td>
    <td>{{ $hotel->checkIn->format('M j, Y') }} to {{ $hotel->checkout->format('M j, Y') }}</td>
    <td class="text-center">{{ $hotel->unitCost }}</td>
    <td class="text-center">{{ $hotel->totalUnits }}</td>
    <td class="text-center">{{ $hotel->nights }}</td>
    <td class="text-center">{{ $hotel->hotelCost }}</td>
    <td>{{ $hotel->hotelSales }}</td>
    <td style="width: 50px;" class="text-center">
        <button type="button" data-url="{{ route('add-quotation-hotel-modal', ['quotationId' => $hotel->quotationId, 'hotelId' => $hotel->id]) }}" data-target="#quotationHotelModal" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light show-modal-itinerary"><i class="ri-pencil-line"></i></button>
        <button type="button" data-url="{{ route('remove-quotation-hotel', $hotel->id) }}" class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-light delete-quotation-service" data-target="#hotel-{{ $hotel->id }}"><i class="ri-delete-bin-line"></i></button>
    </td>
</tr>