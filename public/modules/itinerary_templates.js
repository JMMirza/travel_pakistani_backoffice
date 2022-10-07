$(document).ready(function () {
    var route = $("#ajaxRoute").val();
    console.log(route);
    $("#itinerary-templates-data-table").DataTable({
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
                data: "templateTitle",
                name: "templateTitle",
            },
            {
                data: "totalDays",
                name: "totalDays",
            },
            {
                data: "category.title",
                name: "category.title",
                defaultContent: '<span>N/A</span>'
            },
            {
                data: "status",
                name: "status",
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
