$(document).ready( function () {
    var table = $('#my_datatable').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        "lengthMenu": [[25, 50, 100, 500], [25, 50, 100, 500]],
        "bDestroy": true,
        dom :"<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            'copy', 'excel', 'colvis'
        ]
    });    
});