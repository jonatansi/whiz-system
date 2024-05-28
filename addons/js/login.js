$(".portalLogin").click(function() {
    var id = this.id;
    $("#btnLogin").prop("disabled",false);
    $("#id_portal").val(id);

    if(id=="master"){
        $("#title_login").html("Master Data Management");
    }
    else if(id=='stock'){
        $("#title_login").html("Stock Management");
    }
}); 

$('#loginForm').submit(function(e) {
    e.preventDefault();

    $("#btnLogin").html('Loading...'); 
    $("#btnLogin").prop("disabled",true);
    var username = $("#loginUsername").val();
    var password = $("#loginPassword").val();
    var id_portal = $("#id_portal").val();
    // var captcha = $("#g-recaptcha-response").val();
    // var dataString = 'username=' + username + '&password=' + password + '&captcha=' + captcha;

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
                        $("#btnLogin").html("Sign in");
                        $("#btnLogin").prop("disabled",false);
                    }
                });
            });
        });

    }
    return false;
});