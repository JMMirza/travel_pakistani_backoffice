<button type="button" class="btn btn-sm btn-success btn-icon waves-effect waves-light show-modal"
    data-url="{{ route('quotations-response-details', $row->id) }}" data-bs-toggle="modal" id="shareholders"
    data-target="#shareholderModel"> <i class="mdi mdi-eye"></i></button>
{{-- @endpermission --}}

{{-- @permission('delete-course') --}}
<a href="{{ route('quotations-chat', $row->id) }}" class="btn btn-sm btn-primary btn-icon waves-effect waves-light">
    <i class="mdi mdi-chat"></i>
</a>
