
$(document).ready(
    function () {
        $('.datatable').DataTable(
            {
                "language" : {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
                },
                "order" : [[0, "desc"]]
            }
        );
    }
);

