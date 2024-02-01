$(document).ready(function () {    
    $(".btnAdd").click(function() {
        var id = this.id;
        $.ajax({
            type: 'POST',
            url: 'user-tambah',
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").hide();
            },

            success: function(msg) {
                $("#form_modul").html(msg);
                $("#form_modul").modal('show');
            }
        });
    });

    
    $("#my_datatable tbody").on('click', '.btnEdit',function() {
        var id = this.id;
        $.ajax({
            type: 'POST',
            url: 'user-edit',
            data: {
                'id': id
            },
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").hide();
            },

            success: function(msg) {
                $("#form_modul").html(msg);
                $("#form_modul").modal('show');
            }
        });
    });

    $("#my_datatable tbody").on('click', '.btnDelete',function() {
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
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = 'user-delete-' + id;
            }
        })
    });
});