// ON CHANGE PROPINSI
$("#id_propinsi").change(function() {
    var id_propinsi=$("#id_propinsi").val();
    $.ajax({
        type: 'POST',
        url: "lok-kabupaten",
        cache: false,
        data:{
            'id_propinsi': id_propinsi
        },
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(data) {
            $("#id_kabupaten").html(data);
            $("#id_kecamatan").html("");
            $("#id_kelurahan").html("");
        }
    });
});

//ON CHANGE KABUPATEN
$("#id_kabupaten").change(function() {
    var id_kabupaten=$("#id_kabupaten").val();
    $.ajax({
        type: 'POST',
        url: "lok-kecamatan",
        cache: false,
        data:{
            'id_kabupaten': id_kabupaten
        },
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(data) {
            $("#id_kecamatan").html(data);
            $("#id_kelurahan").html("");
        }
    });
});

//ON CHANGE KECAMATAN
$("#id_kecamatan").change(function() {
    var id_kecamatan=$("#id_kecamatan").val();
    $.ajax({
        type: 'POST',
        url: "lok-kelurahan",
        cache: false,
        data:{
            'id_kecamatan': id_kecamatan
        },
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(data) {
            $("#id_kelurahan").html(data);
        }
    });
});