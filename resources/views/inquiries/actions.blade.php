<a data-toggle="tooltip" title="Create Quotation" href="{{ route('quotation-save', ['inquire_id'=>$row->id]) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>

<button data-toggle="tooltip" title="View Quotation Templates" class="btn btn-sm btn-success btn-icon waves-effect waves-light show-modal-inquiry-quotation" data-url="{{ route('create-quotation-template-modal', ['inquire_id'=>$row->id]) }}" data-target="#quotationTemplateModal">
    <i class="mdi mdi-pencil-plus"></i>
</button>

<a data-toggle="tooltip" title="Edit Inquiry" href="{{ route('inquiries.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>

<a data-toggle="tooltip" title="Delete Inquiry" href="{{ route('inquiries.destroy', $row->id) }}" data-table="inquiries-data-table" class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>
