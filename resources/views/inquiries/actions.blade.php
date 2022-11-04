<a href="{{ route('quotation-save', ['inquire_id'=>$row->id]) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>

<button class="btn btn-sm btn-success btn-icon waves-effect waves-light show-modal-inquiry-quotation" data-url="{{ route('create-quotation-template-modal', ['inquire_id'=>$row->id]) }}" data-target="#quotationTemplateModal">
    <i class="mdi mdi-lead-pencil"></i>
</button>

</a><a href="{{ route('inquiries.edit', $row->id) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
    <i class="mdi mdi-lead-pencil"></i>
</a>

<a href="{{ route('inquiries.destroy', $row->id) }}" data-table="inquiries-data-table" class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
    <i class="ri-delete-bin-5-line"></i>
</a>