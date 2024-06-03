<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Profile</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile Saya</li>
            </ol>
        </nav>
    </div>

</div>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Profile
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr><td width="150px">Nama Lengkap</td><td class="text-end fw-bold"><?php echo $pegawai['nama'];?></td></tr>
                        <tr><td>Username</td><td class="text-end"><?php echo $pegawai['username'];?></td></tr>
                        <tr><td>Email</td><td class="text-end"><?php echo $pegawai['email'];?></td></tr>
                        <tr><td>Nomor Handphone</td><td class="text-end"><?php echo $pegawai['no_handphone'];?></td></tr>
                        <tr><td>Jabatan</td><td class="text-end"><?php echo $pegawai['jabatan'];?></td></tr>
                        <tr><td>Last Login</td><td class="text-end"><?php echo WaktuIndo($pegawai['last_login_at']);?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Ubah Password
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="aksi-edit-profile" enctype="multipart/form-data">
                    <div class="row form-group mb-4">
                        <label class="form-label col-md-4 text-end pt-2" for="example-text-input">Old Password</label>
                        <div class="col-md-7">
                            <input type="password" class="form-control" value="" name="pass_lama" required autofocus id="pass_lama">
                        </div>
                    </div>
                    <div class="row form-group mb-4">
                        <label class="form-label col-md-4 text-end pt-2" for="example-text-input">New Password</label>
                        <div class="col-md-7">
                            <input type="password" class="form-control" value="" name="pass_baru" required id="pass_baru" disabled>
                        </div>
                    </div>
                    <div class="row form-group mb-4">
                        <label class="form-label col-md-4 text-end pt-2" for="example-text-input">New Password Confirmation</label>
                        <div class="col-md-7">
                            <input type="password" class="form-control" value="" name="pass_baru2" required id="pass_baru_konfim" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-md-4 col-md-7">
                            <div id="alertprofile" class="mb-2"></div>
                            <button type="submit" class="btn btn-success mb-3" id="btnSimpan">Simpan</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    var passLama = $("#pass_lama");
    var passBaru = $("#pass_baru");
    var passBaruKonfirm = $("#pass_baru_konfim");
    var alertProfile = $("#alertprofile");
    var btnSimpan = $("#btnSimpan");

    passLama.on('change', function (e) {
        var pass_lama = passLama.val();
        $.ajax({
            type: 'POST',
            data: {
                'pass_lama': pass_lama
            },
            url: 'profile-cekpass',
            success: function (msg) {
                if (msg === '') {
                    alertProfile.html("<div class='alert alert-danger'>Password tidak sesuai</div>");
                    btnSimpan.prop("disabled", true);
                } else {
                    btnSimpan.prop("disabled", false);
                    alertProfile.html("");
                    passBaru.prop("disabled", false);
                    passBaruKonfirm.prop("disabled", false);
                }
                console.log('Data ' + msg);
            }
        });
    });

    passBaruKonfirm.on('change', function (e) {
        var pass_baru = passBaru.val();
        var pass_baru_konfim = passBaruKonfirm.val();
        if (pass_baru !== pass_baru_konfim) {
            btnSimpan.prop("disabled", true);
            alertProfile.html("<div class='alert alert-danger'>Konfirmasi Password baru tidak sesuai</div>");
        } else {
            alertProfile.html("");
            btnSimpan.prop("disabled", false);
        }
    });
});

</script>