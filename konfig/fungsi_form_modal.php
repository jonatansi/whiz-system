<?php
function form_modal_js($url_return){
    $return_data = <<<EOD
    <script type="text/javascript">
    $("#form_crud").submit(function(e) {
        $(this).find("button[type='submit']").prop('disabled',true);
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var actionUrl = form.attr('action');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(), // serializes the form's elements.
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").hide();
            },

            success: function(msg) {
                window.location.href="$url_return";
                $("#form_modul").modal('hide');
            }
        });

    });
    </script>
    EOD;

    return $return_data;
}

function form_select2(){
    $return_data = <<<EOD
    <script type="text/javascript">
    $('.select2').select2({
        dropdownParent: $('#form_modul')
    });
    </script>
    EOD;

    return $return_data;
}
?>