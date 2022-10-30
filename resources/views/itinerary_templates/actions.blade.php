<a href="{{ route('itinerary-templates.edit', $row->id) }}?tab=1" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
<a href="{{ route('itinerary-templates.destroy', $row->id) }}" data-table="itinerary-templates-data-table" class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>