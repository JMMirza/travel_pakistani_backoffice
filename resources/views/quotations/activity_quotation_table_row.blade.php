<tr id="activity-{{ $activity->id }}">
    <td>{{ $activity->description }}</td>
    <td>
        @if($activity->instructions)
        <a tabindex="0" class="link-success" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" title="" data-bs-content="{{ $activity->instructions }}" data-bs-original-title="Remarks">View Remarks</a>
        @else
        <p class="text-muted mb-0">N/A</p>
        @endif
    </td>
    <td>{{ $activity->serviceDate->format('M j, Y') }} to {{ $activity->serviceEndDate->format('M j, Y') }}</td>
    <td class="text-center">{{ $activity->unitCost }}</td>
    <td class="text-center">{{ $activity->totalUnits }}</td>
    <td class="text-center">{{ $activity->totalDays }}</td>
    <td class="text-center">{{ $activity->unitCost * $activity->totalUnits * $activity->totalDays }}</td>
    <td>{{ $activity->serviceSales }}</td>
    <td style="width: 50px;" class="text-center">
        <button type="button" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light show-modal-itinerary"><i class="ri-pencil-line"></i></button>
        <button type="button" data-url="{{ route('remove-quotation-service' , $activity->id) }}" class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-light delete-quotation-service" data-target="#activity-{{ $activity->id }}"><i class="ri-delete-bin-line"></i></button>
    </td>
</tr>