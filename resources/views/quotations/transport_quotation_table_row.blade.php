<tr id="transport-{{ $transport->id }}">
    <td>{{ $transport->description }}</td>
    <td>
        @if($transport->instructions)
        <a tabindex="0" class="link-success" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" title="" data-bs-content="{{ $transport->instructions }}" data-bs-original-title="Remarks">View Remarks</a>
        @else
        <p class="text-muted mb-0">N/A</p>
        @endif
    </td>
    <td>{{ $transport->serviceDate }} to {{ $transport->serviceDate }}</td>
    <td class="text-center">{{ $transport->unitCost }}</td>
    <td class="text-center">{{ $transport->totalUnits }}</td>
    <td class="text-center">{{ $transport->totalDays }}</td>
    <td class="text-center">{{ $transport->unitCost * $transport->totalUnits * $transport->totalDays }}</td>
    <td>{{ $transport->serviceSales }}</td>
    <td style="width: 50px;" class="text-center">
        <button type="button" data-url="{{ route('add-quotation-transport-modal', ['quotationId' => $transport->quotationId, 'serviceId' => $transport->id]) }}" data-target="#quotationTransportModal" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light show-modal-itinerary"><i class="ri-pencil-line"></i></button>
        <button type="button" data-url="{{ route('remove-quotation-service' , $transport->id) }}" class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-light delete-quotation-service" data-target="#transport-{{ $transport->id }}"><i class="ri-delete-bin-line"></i></button>
    </td>
</tr>