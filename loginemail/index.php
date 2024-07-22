<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stay Connected Whiz Digital</title>
    <link rel="icon" href="images/stayconnected.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <link id="style" href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

    <link href="assets/css/icons.min.css" rel="stylesheet" >

    <link href="assets/css/style-login.css?v1" rel="stylesheet">
</head>
<body>
    <div class="container-fluid body">
        <div class="vh-80 d-flex justify-content-center align-items-center">
            <div class="row w-100 justify-content-center align-items-center">
                <div class="col-lg-4 col-md-9 d-none d-md-block">
                    <div class="login-form w-100 shadow-sm rounded-4 py-4 px-4 bg-white">
                        <div id="login_opsi">
                            <img src="images/logo_sendMe.svg" alt="Whiz Matrix" style="max-width:170px;" id="img_login">
                            <h4 class="my-3" id="title_login">Login</h4>
                        </div>
                        <form id="loginForm" class="w-100" action="ceklogin">
                            <input type="hidden" name="id_portal" id="id_portal">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                                    </div>
                                    <input type="email" class="form-control form-email" id="loginUsername" placeholder="John@corporate.id">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control form-password" id="loginPassword" placeholder="Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" id="togglePassword" onclick="togglePassword()"><i class="fas fa-eye"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div id="error" class="mt-3 mb-1"></div>
                            <button type="submit" class="btn btn-primary btn-block rounded-3" id="btnLogin">Login</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 text-center d-none d-lg-block">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/secure_mail.svg" class="img-fluid" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Secure Email</h5>
                                    <p>Secure email access, anytime, anywhere.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="images/fastest.svg" class="img-fluid" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Fastest</h5>
                                    <p>Experience the fastest email.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="images/unlimeted_access.svg" class="img-fluid" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Unlimited Access</h5>
                                    <p>Don't worry about storage space.</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="d-block d-md-none">
                    <div class="login-form w-100 rounded-4">
                        <div id="login_opsi">
                            <img src="images/logo_sendMe.svg" alt="Whiz Matrix" style="max-width:170px;" id="img_login">
                            <h4 class="my-3" id="title_login">Login</h4>
                        </div>
                        <form id="loginForm" class="w-100" action="ceklogin">
                            <input type="hidden" name="id_portal" id="id_portal">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                                    </div>
                                    <input type="email" class="form-control form-email" id="loginUsername" placeholder="John@corporate.id">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control form-password" id="loginPassword" placeholder="Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" id="togglePassword" onclick="togglePassword()"><i class="fas fa-eye"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div id="error" class="mt-3 mb-1"></div>
                            <button type="submit" class="btn btn-primary btn-block rounded-3" id="btnLogin">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                Powered by <span class="text-secondary">Lunata Technologies</span>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

</body>
</html>