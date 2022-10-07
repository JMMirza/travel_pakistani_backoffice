$(document).ready(function () {
    var route = $("#ajaxRoute").val();
    console.log(route);
    $("#templates-data-table").DataTable({
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
                data: "title",
                name: "title",
            },
            {
                data: "user.name",
                name: "user.name",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "templateType",
                name: "templateType",
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
});
