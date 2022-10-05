$(document).ready(function () {
    var route = $("#ajaxRoute").val();
    console.log(route);
    $("#hotels-data-table").DataTable({
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
                data: "hotelName",
                name: "hotelName",
            },
            {
                data: "hotelAddress",
                name: "hotelAddress",
                width: "15%",
            },
            {
                data: "contactNo",
                name: "contactNo",
                width: "15%",
            },
            {
                data: "created_at",
                name: "created_at",
                width: "15%",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                width: "5%",
                sClass: "text-center",
            },
        ],
    });
});
