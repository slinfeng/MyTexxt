$(document).ready(function() {
    $("#example").on("click", "tr td:nth-child(1)", function(e) {
        setTimeout(getTableContent, 10);
    });
    var table = $('#example').DataTable({
        scrollY: "394px",
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        searching: false,
        fixedColumns: {
            leftColumns: 2
        },
        columnDefs: [{
            orderable: false,
            className: 'select-checkbox',
            targets: 0,
        }],
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        order: [
            [1, 'asc']
        ]
    });

    function getTableContent() {
        let is = false;
        $("tbody tr").each(function() {
            if ($(this).hasClass("selected")) {
                is = true;
            }
        });
        if (is) {
            $("#options").removeClass("invisible");
        } else {
            $("#options").addClass("invisible");
        }
    }
});