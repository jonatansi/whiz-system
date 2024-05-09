<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Pencarian Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pencarian Material</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset>
                    <legend class="fw-bold">Pencarian Material Berdasarkan Serial  Number</legend>
                    <form method="POST" action="cari-material" id="form_cari">
                        <div class="row justify-content-center">
                            <label class="col-md-2 text-end pt-2">Serial Number <span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="serial_number" required autofocus>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                            </div>
                        </div>
                    </form>
                </fieldset>
                <div id="hasil_cari" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function performAjaxRequest(url, data, successCallback) {
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").hide();
            },
            success: successCallback
        });
    }

    $("#form_cari").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var actionUrl = form.attr('action');
        performAjaxRequest(actionUrl, form.serialize(), function(msg) {
            $("#hasil_cari").html(msg);
            form.find("button[type='submit']").prop('disabled', false);
        });
    });
</script>