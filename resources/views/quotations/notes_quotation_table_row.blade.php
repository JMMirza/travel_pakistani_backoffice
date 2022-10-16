<tr id="note-{{ $noteRow->id }}">
    <td>{{ $noteRow->title }}</td>
    <td style="width: 200px;">
        <a href="#" data-title="{{ $noteRow->title }}" data-description="{{ $noteRow->description }}" class="link-success view-note-description">View Description</a>
    </td>
    <td style="width: 100px;"> {{ $noteRow->created_at->format('M j, Y') }}</td>
    <td style="width: 50px;" class="text-center">
        <button type="button" data-url="{{ route('add-quotation-policies-modal', ['quotationId' => $noteRow->quotationId, 'noteId' => $noteRow->id]) }}" data-target="#quotationPoliciesModal" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light show-modal-itinerary"><i class="ri-pencil-line"></i></button>
        <button type="button" data-url="{{ route('remove-quotation-note' , $noteRow->id) }}" class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-light delete-quotation-note" data-target="#note-{{ $noteRow->id }}"><i class="ri-delete-bin-line"></i></button>
    </td>
</tr>

<!-- <tr id="note-description-{{ $noteRow->id }}" style="display: none;">
    <td colspan="4">
        <p>{!! $noteRow->description !!}</p>
    </td>
</tr> -->