function togglePassword() {
    var passwordInput = document.getElementById("password");
    var toggleIcon = document.querySelector(".toggle-password i");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}

function toggleActive(clickedElement) {
    var circleItems = document.querySelectorAll('.circle-submenu');

    // Iterasi melalui semua elemen dan hapus kelas 'active' dari setiap elemen
    circleItems.forEach(function(item) {
        item.classList.remove('active');
    });

    // Tambahkan kelas 'active' ke elemen yang diklik
    clickedElement.classList.add('active');
}

$(document).ready(function () {    
    $(".menu-circle").click(function() {
        var id = this.id;
        if(id=='mail'){
            window.open('https://send-me.id', '_blank');
        }
        else if($id=='cloud'){
            window.open('https://cloud.whizdigital.id', '_blank');
        }
        else if($id=='matrix'){

        }
        else if($id=='spy'){
            
        }
        else if(id=='temp'){
            
        }
    });
});

$(".circle-submenu").click(function() {
    var id = this.id;
    $("#btnLogin").prop("disabled",false);
    if(id=="whiz_master"){
        var value = "master";
    }
    else if(id=='whiz_stock'){
        var value = "stock";
    }
    $("#id_portal").val(value);

}); 

$('#loginForm').submit(function(e) {
    e.preventDefault();

    $("#btnLogin").html('Loading...'); 
    $("#btnLogin").prop("disabled",true);
    var username = $("#loginUsername").val();
    var password = $("#loginPassword").val();
    var id_portal = $("#id_portal").val();
    // var captcha = $("#g-recaptcha-response").val();
    var dataString = 'username=' + username + '&password=' + password;
    alert(dataString);
    if ($.trim(username).length > 0 && $.trim(password).length > 0) {

        grecaptcha.ready(function() {
            // do request for recaptcha token
            // response is promise with passed token
            grecaptcha.execute('6LdQhl0pAAAAAORLRytMwFzYm6tlEFc5-oYfA6Zc', { action: 'ceklogin' }).then(function(token) {
                // add token to form
                $('#loginForm').prepend('<input type="hidden" name="token" value="' + token + '" id="token">');
                var token = $("#token").val();
                $.post("ceklogin", { 
                    username: username, 
                    password: password,
                    id_portal: id_portal,
                    token: token
                },
                function(data) {
                    console.log(data);
                    if (data == "ok") {
                        window.location.href = id_portal+"/home";
                    }
                    else{
                        if(data=='-1'){
                            $("#error").html("<div class='alert alert-danger'>Not valid from Google Recaptcha</div>");
                        }
                        else if(data=='notauth'){
                            $("#error").html("<div class='alert alert-danger'>You are not authorized for access this portal</div>");
                        }
                        else{
                            $("#error").html("<div class='alert alert-danger'>Invalid Login</div>");
                        }
                        // $("#error").html(data);
                        $("#btnLogin").html("Login");
                        $("#btnLogin").prop("disabled",false);
                    }
                });
            });
        });

    }
    return false;
});