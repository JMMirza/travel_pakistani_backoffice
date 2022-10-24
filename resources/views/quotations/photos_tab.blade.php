<div class="tab-pane {{ $tab == 5 ? 'active' : '' }}" id="nav-border-top-05" role="tabpanel">
    <div class="card-body">
        <!-- <p class="text-muted">DropzoneJS is an open source library that provides drag’n’drop file uploads with image previews.</p> -->


        <form action="{{ route('quotation-image-upload') }}" method="post" enctype="multipart/form-data" id="quotation-image-upload" class="dropzone">
            @csrf
            <input type="hidden" value="{{ $quotation->id }}" name="quotationId" />
            <div class="fallback">
                <input name="file" type="file" multiple="multiple">
            </div>
            <div class="dz-message needsclick">
                <div class="mb-3">
                    <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                </div>

                <h4>Drop files here or click to upload.</h4>
            </div>
        </form>

        <ul class="list-unstyled mb-0" id="quotation-image-preview">
            <li class="mt-2" id="dropzone-preview-list">
                <!-- This is used as the file preview template -->
                <div class="border rounded">
                    <div class="d-flex p-2">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm bg-light rounded">
                                <img data-dz-thumbnail class="img-fluid rounded d-block" src="#" alt="Dropzone-Image" />
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="pt-1">
                                <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                <strong class="error text-danger" data-dz-errormessage></strong>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <table data-quotationid="{{ $quotation->id }}" id="quotations-images-table" class="table table-striped align-middle table-nowrap mb-0" style="width:100%">
            <thead class="text-muted table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@push('header_scripts')
<link rel="stylesheet" href="{{ asset('theme/dist/default/assets/libs/dropzone/dropzone.css') }}" type="text/css" />
@endpush

@push('footer_scripts')

<script src="{{ asset('theme/dist/default/assets/libs/dropzone/dropzone-min.js') }}"></script>

<script>
    $(document).ready(function() {

        var dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
        dropzonePreviewNode.id = "";
        var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
        dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);

        Dropzone.autoDiscover = false;

        var dropzone = new Dropzone('#quotation-image-upload', {
            // thumbnailWidth: 200,
            // maxFilesize: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            // previewTemplate: previewTemplate,
            // previewsContainer: "#quotation-image-preview",
            success: function(file, response) {
                console.log(response);
                $('#quotations-images-table').DataTable().ajax.reload(null, false);
            },
        });

        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#quotations-images-table').dataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                searchPlaceholder: "Search..."
            },
            ajax: {
                url: "{{ route('list-quotation-images') }}",
                data: function(d) {
                    // d.status = status;
                    d.quotationId = $('#quotations-images-table').data('quotationid');
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "5%",
                    sClass: 'text-center'
                }
            ]
        });

    });
</script>
@endpush