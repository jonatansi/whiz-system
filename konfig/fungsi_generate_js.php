<?php
function generate_javascript_action($button_class, $url){
    if($button_class=='btnDelete'){
$return_data="
$('#my_datatable tbody').on('click', '.btnDelete',function() {
    var id = this.id;
    
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success ms-2',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: 'You will not be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.location.href = '$url-' + id;
        }
    })
});";
    }
    else{
        if($button_class=='btnAdd'){
$first_return = "
$('.$button_class').click(function() {";
        }
        else if($button_class=='btnPrint'){
            $first_return = "
            $('.$button_class').click(function() {";
        }
        else{
$first_return = "
$('#my_datatable tbody').on('click', '.$button_class',function() {";
        }
$return_data = "$first_return
    var id = this.id;
    $.ajax({
        type: 'POST',
        url: '$url',
        data: {
            'id': id
        },
        beforeSend: function() {
            $('.preloader').show();
        },
        complete: function() {
            $('.preloader').hide();
        },

        success: function(msg) {
            $('#form_modul').html(msg);
            $('#form_modul').modal('show');
        }
    });
});";
    }

    return $return_data;
}

function datatable_column($targets, $className, $orderable){
    $return_data="{
        'targets': $targets, 
        'className': '$className',
        'orderable': $orderable
    },";

    return $return_data;
}

function datatable_column_search_disabled($column){
    $return_data=<<<EOD
    if(i=='$column'){
        $(this).html( '<input type="text" class="form-control2" placeholder="" disabled/>' );
    }
    EOD;

    return $return_data;
}

function generate_js_print($url){
    $return_data=<<<EOD
    $("#btnPrint").click(function() {
        $('#form_modul').modal('hide');
        var first_date = $("#tanggal_awal").val();
        var end_date = $("#tanggal_akhir").val();
        window.open("$url?first_date="+first_date+"&end_date="+end_date, "popupWindow", "width=600,height=600,scrollbars=yes");
    });
    EOD;

    return $return_data;
}

function general_default_datatable(){
    $return_data=<<<EOD
    $(document).ready( function () {
        $('#my_datatable thead tr').clone(true).appendTo( '#my_datatable thead' );
        $('#my_datatable thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            if(title=='No' || title=='Aksi'){
                $(this).html( '<input type="text" class="form-control2" placeholder="" disabled/>' );
            }
            else{
                $(this).html( '<input type="text" class="form-control2" placeholder="'+title+'" />' );
            }
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            });
        });

        var table = $('#my_datatable').DataTable( {
            orderCellsTop: true,
            fixedHeader: true,
            "lengthMenu": [[25, 50, 100, 500], [25, 50, 100, 500]],
            "bDestroy": true,
            dom :"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-end'Br>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                { extend: 'colvis', className: 'btn btn-warning'},
                { extend: 'copy', className: 'btn-info' },
                { extend: 'print', className: 'btn-danger' },
                { extend: 'excel', className: 'btn-success' },
            ]
        });    
    });
    EOD;
    return $return_data;
}
function generate_datatable($url, $order_number, $order_type, $oder_column, $column_search_disabled){
    $return_data = <<<EOD
    <script type="text/javascript">
    $(document).ready(function () {  
        $('#datatable_ajax thead tr').clone(true).appendTo( '#datatable_ajax thead' );
        $('#datatable_ajax thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            if(title=='#' || title=='Action'){
                $(this).html( '<input type="text" class="form-control2" placeholder="" disabled/>' );
            }
            else{
                $(this).html( '<input type="text" class="form-control2" placeholder="'+title+'" />' );
            }
            $column_search_disabled
        
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            });
        });

        var table = $('#datatable_ajax').DataTable( {
            "processing": true,
            "serverSide": true,
            orderCellsTop: true,
            fixedHeader: false,
            "ajax": {
                "url": "$url",
                "data": function(d){
                    d.tanggal_awal= $("#tanggal_awal").val();
                    d.tanggal_akhir= $("#tanggal_akhir").val();
                },
                "type": "POST"
            },
            "order": [[ $order_number, "$order_type" ]],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'columnDefs': [
                {
                    "targets": 0, // your case first column
                    "className": "text-center",
                    "width": "50px",
                    "orderable": false
                },
                $oder_column
                {
                    "targets": -1, 
                    "className": "text-center",
                    "orderable": false
                }
            ],
            "drawCallback": function (settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide');
            },
            "bDestroy": true,
            dom :"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-end'Br>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                { extend: 'colvis', className: 'btn btn-warning'},
                { extend: 'copy', className: 'btn-info' },
                { extend: 'print', className: 'btn-danger' },
                { extend: 'excel', className: 'btn-success' },
            ]
        });
        
    
        $("#btnFilter").click(function() {
            table.ajax.reload();
        });
    });
    </script>
    EOD;   
    
    return $return_data;
}
?>