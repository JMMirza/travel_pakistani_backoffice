<tr id="meal-{{ $meal->id }}">
    <td>{{ $meal->description }}</td>
    <td>
        @if($meal->instructions)
        <a tabindex="0" class="link-success" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" title="" data-bs-content="{{ $meal->instructions }}" data-bs-original-title="Remarks">View Remarks</a>
        @else
        <p class="text-muted mb-0">N/A</p>
        @endif
    </td>

    <td>{{ $meal->serviceDate->format('M j, Y') }} to {{ $meal->serviceEndDate->format('M j, Y') }}</td>

    <td class="text-center">{{ $meal->unitCost }}</td>
    <td class="text-center">{{ $meal->totalUnits }}</td>
    <td class="text-center">{{ $meal->totalDays }}</td>
    <td class="text-center">{{ $meal->unitCost * $meal->totalUnits * $meal->totalDays }}</td>
    <td>{{ $meal->serviceSales }}</td>
    <td style="width: 50px;" class="text-center">
        <button type="button" data-url="{{ route('add-quotation-meal-modal', ['quotationId' => $meal->quotationId, 'serviceId' => $meal->id]) }}" data-target="#quotationMealModal" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light show-modal-itinerary"><i class="ri-pencil-line"></i></button>
        <button type="button" data-url="{{ route('remove-quotation-service' , $meal->id) }}" class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-light delete-quotation-service" data-target="#meal-{{ $meal->id }}"><i class="ri-delete-bin-line"></i></button>
    </td>
</tr>