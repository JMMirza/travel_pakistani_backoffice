$(document).ready(function () {
    var route = $("#ajaxRoute").val();
    console.log(route);
    $("#inquiries-data-table").DataTable({
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
                data: "name",
                name: "name",
            },
            {
                data: "preferredDate",
                name: "preferredDate",
            },
            {
                data: "status",
                name: "status",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "createdByUser.name",
                name: "createdByUser.name",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "user.name",
                name: "user.name",
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
