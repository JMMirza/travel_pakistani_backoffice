$(document).ready(function () {
    var route = $("#ajaxRoute").val();
    console.log(route);
    $("#operators-data-table").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        scrollX: true,
        language: {
            search: "",
            searchPlaceholder: "Search...",
        },
        ajax: route,
        columns: [
            {
                data: "id",
                name: "id",
            },
            {
                data: "user.name",
                name: "user.name",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "contactPerson",
                name: "contactPerson",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "contactNumber",
                name: "contactNumber",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "businessEmail",
                name: "businessEmail",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "created_at",
                name: "created_at",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                width: "5%",
                sClass: "text-center",
            }
        ],
    });

    var quill_snow;
    quill_snow = new Quill("#snow-editor", {
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline", "strike"],
                ["code-block"],
                ["link"],
                [{ script: "sub" }, { script: "super" }],
                [{ list: "ordered" }, { list: "bullet" }],
                ["clean"],
            ],
        },
        theme: "snow",
    });
    quill_snow.on("text-change", function (delta, oldDelta, source) {
        $("#description").val(quill_snow.root.innerHTML);
    });
});
