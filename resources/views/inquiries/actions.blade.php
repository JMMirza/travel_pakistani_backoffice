{{-- @permission('edit-course') --}}
<a href="{{ route('inquiries.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>
{{-- @endpermission --}}

{{-- @permission('delete-course') --}}
<a href="{{ route('inquiries.destroy', $row->id) }}" data-table="inquiries-data-table"
    class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>
