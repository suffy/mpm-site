<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $title; ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../assets_new/login/images/favicon.png">
    <link href="<?php echo base_url(); ?>assets_new/login/css/style.css" rel="stylesheet">

</head>

<body class="h-100">

    <!--*******************
        Preloader start
    ********************-->
    <!-- <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div> -->
    <!--*******************
        Preloader end
    ********************-->

    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <a class="text-center" href="index.html">
                                    <h4>Input Alamat Gmail Anda</h4>
                                </a>
                                <br>
                                <pre>
Note :
Silahkan isi alamat gmail anda yang aktif di form ini,
sebelum anda menggunakan web, Terima kasih.
                                </pre>
                                <?php 
                                $attributes = array('class' => 'mt-5 mb-5 login-input');
                                echo form_open($url, $attributes);
                                ?>

                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email (gmail)" name="email"
                                        required>
                                </div>
                                <button class="btn login-form__btn submit w-100">Simpan Email</button>
                                </form>
                                <!-- <p class="mt-5 login-form__footer">Belum punya akun ? <a href="<?php echo base_url(); ?>login/formRegistrasi" class="text-primary">Daftar disini</a><br>
                            Lupa password login ? <a href="<?php echo base_url(); ?>login/formLupaPassword" class="text-primary">Klik Reset Password</a></p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="whatsapp d-flex align-items-center justify-content-center active" data-toggle="modal"
        data-target="#whatsapp">
        <img src="<?php echo base_url() ?>assets/png/whatsapp-logo-1.png"></a>

    <!-- Modal -->
    <div class="modal fade" id="whatsapp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kontak Whatsapp</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Support PO</h5>
                    <hr>
                    <a href="https://api.whatsapp.com/send?phone=+628983031781&text=Halo,%20Admin%20Mulia%20Putra%20Mandiri"
                        target="_blank"><img src="<?php echo base_url() ?>assets/png/whatsapp-logo-1.png"
                            style="width: 10%;height: 10%;"> (Admin PO 1)</a><br>
                    <a href="https://api.whatsapp.com/send?phone=+6281310967642&text=Halo,%20Admin%20Mulia%20Putra%20Mandiri"
                        target="_blank"><img src="<?php echo base_url() ?>assets/png/whatsapp-logo-1.png"
                            style="width: 10%;height: 10%;"> (Admin PO 2)</a><br>
                    <br>
                    <h5>Support Development</h5>
                    <hr>
                    <a href="https://api.whatsapp.com/send?phone=+6285779834495&text=Halo,%20Admin%20Mulia%20Putra%20Mandiri"
                        target="_blank"><img src="<?php echo base_url() ?>assets/png/whatsapp-logo-1.png"
                            style="width: 10%;height: 10%;"> (Admin Develop 1)</a><br>
                    <a href="https://api.whatsapp.com/send?phone=+6281283453274&text=Halo,%20Admin%20Mulia%20Putra%20Mandiri"
                        target="_blank"><img src="<?php echo base_url() ?>assets/png/whatsapp-logo-1.png"
                            style="width: 10%;height: 10%;"> (Admin Develop 2)</a><br>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="<?php echo base_url(); ?>assets_new/login/plugins/common/common.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/settings.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/gleek.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/styleSwitcher.js"></script>
</body>

</html>