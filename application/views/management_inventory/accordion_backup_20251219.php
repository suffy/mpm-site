<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

<style>
    body {
        font-family: 'Poppins';
        font-style: normal;
    }

    /* .collapse {
        visibility: hidden;
    } */

    .collapse.show {
        visibility: visible;
        display: block;
    }

    .collapsing {
        position: relative;
        height: 0;
        overflow: hidden;
        -webkit-transition-property: height, visibility;
        transition-property: height, visibility;
        -webkit-transition-duration: 0.8s;
        transition-duration: 0.8s;
        -webkit-transition-timing-function: ease;
        transition-timing-function: ease;
    }

</style>

</div>

<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <p>
                <button class="btn btn-submit-cream" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2" style="border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Lihat Detail Program</button>
            </p>

            <div class="row">

                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Pengajuan DP</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Status</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->nama_status ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">No Pengajuan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->no_pengajuan ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Tipe</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label  readonly><?= $get_pengajuan->row()->tipe ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Principal</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->namasupp ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Branch - SubBranch</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->branch_name . ' - ' . $get_pengajuan->row()->nama_comp . ' - ' . $get_pengajuan->row()->site_code ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Key Account</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label  style="text-transform: capitalize;"
                                            readonly><?= $get_pengajuan->row()->key_account ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">PIC DP</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label  readonly><?= $get_pengajuan->row()->nama ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Pengajuan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->tanggal_pengajuan) { ?>
                                            <label
                                                readonly><?= $get_pengajuan->row()->tanggal_pengajuan ?></label>
                                        <?php
                                        } else { ?>
                                            <label  readonly><i>retur belum tuntas diajukan</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Nota Retur Barang (NRB) </label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->tanggal_nrb ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Lampiran Pengajuan</label>
                                    </div>

                                    <?php
                                    // echo "tipe : " . $get_pengajuan->row()->tipe;
                                    if ($get_pengajuan->row()->tipe != "retur_administrasi" && $get_pengajuan->row()->tipe != "retur_khusus") { ?>

                                        <div class="col-md-9">
                                            <?php
                                            if ($get_pengajuan->row()->file) {
                                                if ($get_pengajuan->row('is_file_folder_retur') == 1) { ?>
                                                    <a href="<?= base_url() . 'assets/file/retur/email_capture/' . $get_pengajuan->row()->file ?>">
                                                        <label  readonly><?= $get_pengajuan->row()->file ?></label></a>
                                                <?php } else { ?>
                                                    <a href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->file ?>">
                                                        <label  readonly><?= $get_pengajuan->row()->file ?></label></a>
                                                    <?php } ?>
                                            <?php
                                            } else { ?>
                                                <label  readonly><i>user tidak melampirkan file</i></label>
                                            <?php } ?>
                                        </div>

                                    <?php
                                    } else { ?>

                                        <div class="col-md-9">
                                            <?php
                                            if ($get_pengajuan->row()->file) { ?>
                                                <a href="<?= base_url() . 'assets/file/retur/email_capture/' . $get_pengajuan->row()->file ?>"><label  readonly><?= $get_pengajuan->row()->file ?></label></a>
                                                | <a href="<?= base_url() . 'assets/file/retur/tanda_terima/' . $get_pengajuan->row()->file_2 ?>"><label  readonly><?= $get_pengajuan->row()->file_2 ?></label></a>
                                                | <a href="<?= base_url() . 'assets/file/retur/foto/' . $get_pengajuan->row()->file_3 ?>"><label  readonly><?= $get_pengajuan->row()->file_3 ?></label></a>
                                            <?php
                                            } else { ?>
                                                <label  readonly><i>user tidak melampirkan file</i></label>
                                            <?php
                                            }
                                            ?>
                                        </div>

                                    <?php
                                    }
                                    ?>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Verifikasi Principal & MPM</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Verifikasi Principal Area at</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->principal_area_at ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->principal_area_username ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Verifikasi MPM at</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->verifikasi_at ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->verifikasi_username ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Verifikasi Principal HO at</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->principal_ho_at ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->principal_ho_username ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Principal HO</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->file_principal_ho) { ?>
                                            <a
                                                href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->file_principal_ho ?>">
                                                <label
                                                    ><?= $get_pengajuan->row()->file_principal_ho ?></label></a>
                                        <?php
                                        } else { ?>
                                            <label ><i>user tidak melampirkan
                                                    file_principal_ho</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Note Principal HO</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->catatan_principal_ho ?></label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Pengiriman Barang Retur</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Kirim Barang</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->tanggal_kirim_barang ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama Ekspedisi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->nama_ekspedisi ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Estimasi Tiba</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->est_tanggal_tiba ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Resi Pengiriman</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->file_pengiriman) { ?>
                                            <a
                                                href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->file_pengiriman ?>">
                                                <!-- <input type="text" value="<?= $get_pengajuan->row()->file_pengiriman ?>" > -->
                                                <label
                                                    ><?= $get_pengajuan->row()->file_pengiriman ?></label></a>
                                        <?php
                                        } else { ?>
                                            <label ><i>user tidak melampirkan
                                                    file_pengiriman</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Proses Kirim Barang By</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->username_kirim_barang ?></label>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Last Update</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->proses_kirim_barang_at ? $get_pengajuan->row()->proses_kirim_barang_at : '' ?></label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Penerimaan Barang Retur</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Terima Barang</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->tanggal_terima_barang ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama Penerima</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->nama_penerima ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nomor Terima Barang (LPK)</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->no_terima_barang ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Terima Barang</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->file_terima_barang) { ?>
                                            <a
                                                href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->file_terima_barang ?>">
                                                <!-- <input type="text" value="<?= $get_pengajuan->row()->file_terima_barang ?>" > -->
                                                <label
                                                    ><?= $get_pengajuan->row()->file_terima_barang ?></label></a>
                                        <?php
                                        } else { ?>
                                            <label ><i>user tidak melampirkan
                                                    file_terima_barang</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Last Update</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->terima_barang_at ?></label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Pemusnahan Barang Retur</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Pemusnahan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->tanggal_pemusnahan ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">PIC Pemusnahan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->nama_pemusnahan ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Pemusnahan (Berita Acara)</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->file_pemusnahan) { ?>
                                            <a
                                                href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->file_pemusnahan ?>">
                                                <label
                                                    ><?= $get_pengajuan->row()->file_pemusnahan ?></label></a>
                                        <?php
                                        } else { ?>
                                            <label ><i>user tidak melampirkan
                                                    file_pemusnahan</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Foto Pemusnahan 1</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->foto_pemusnahan_1) { ?>
                                            <a
                                                href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->foto_pemusnahan_1 ?>">
                                                <label
                                                    ><?= $get_pengajuan->row()->foto_pemusnahan_1 ?></label></a>
                                        <?php
                                        } else { ?>
                                            <label ><i>user tidak melampirkan
                                                    foto_pemusnahan_1</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Foto Pemusnahan 2</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->foto_pemusnahan_2) { ?>
                                            <a
                                                href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->foto_pemusnahan_2 ?>">
                                                <label
                                                    ><?= $get_pengajuan->row()->foto_pemusnahan_2 ?></label></a>
                                        <?php
                                        } else { ?>
                                            <label ><i>user tidak melampirkan foto pemusnahan
                                                    2</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Video Pemusnahan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        if ($get_pengajuan->row()->video) { ?>
                                            <video width="320" height="240" controls>
                                                <source
                                                    src="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->video ?>"
                                                    type="video/mp4">
                                                <source src="movie.ogg" type="video/ogg">
                                                Your browser does not support the video tag.
                                            </video>
                                            <a href="<?= base_url() . 'assets/file/retur/' . $get_pengajuan->row()->video ?>"
                                                class="btn btn-secondary btn-sm rounded" target="_blank"
                                                download>download</a>
                                        <?php
                                        } else { ?>
                                            <label ><i>user tidak melampirkan video</i></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Last Updated</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label
                                            readonly><?= $get_pengajuan->row()->pemusnahan_at ?></label>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container-fluid">

                                <div class="row mt-1">
                                    <div class="col-md-12 text-center">
                                        <a href="<?= base_url() . 'management_inventory/export_by_signature/' . $signature ?>"
                                            class="btn btn-submit-black mt-2">export raw csv</a>
                                        <a href="<?= base_url() . 'management_inventory/generate_pdf/' . $signature . '/' . $supp ?>"
                                            class="btn btn-submit-black mt-2" target="_blank">Export Pdf</a>
                                        <a href="<?= base_url() . 'management_inventory/export_sortir_by_signature/' . $signature ?>"
                                            class="btn btn-submit-black mt-2">export csv (data final untuk pabrik)</a>
                                        <br>
                                        <?php
                                        if ($get_pengajuan->row()->tanggal_terima_barang) {
                                            if (substr($get_pengajuan->row()->supp, 1, 3) == 001) { ?>
                                                <a href="<?= base_url() . 'management_inventory/generate_pdf_spbr/' . $signature . '/' . str_replace('/', '-', $get_pengajuan->row()->no_terima_barang) ?>"
                                                    class="btn btn-submit-black mt-2" target="_blank">SPBR Persetujuan Original Dp</a>
                                                <a href="<?= base_url() . 'management_inventory/generate_pdf_spbr_group_kodeprod/' . $signature . '/' . str_replace('/', '-', $get_pengajuan->row()->no_terima_barang) ?>"
                                                    class="btn btn-submit-black mt-2" target="_blank">SPBR Persetujuan dengan Batch Number untuk Accounting</a>
                                                <a href="<?= base_url() . 'management_inventory/generate_pdf_spbr_penolakan/' . $signature . '/' . str_replace('/', '-', $get_pengajuan->row()->no_terima_barang) ?>"
                                                    class="btn btn-submit-black mt-2" target="_blank">STBR Penolakan Original Dp</a>
                                                <a href="<?= base_url() . 'management_inventory/generate_pdf_spbr_penolakan_group_kodeprod/' . $signature . '/' . str_replace('/', '-', $get_pengajuan->row()->no_terima_barang) ?>"
                                                    class="btn btn-submit-black mt-2" target="_blank">STBR Penolakan dengan Batch Number untuk Accounting</a>
                                            <?php }
                                        } elseif ($get_pengajuan->row()->validasi_pemusnahan_at) { ?>
                                            <a href="<?= base_url() . 'management_inventory/generate_pdf_spbr_pemusnahan/' . $signature . '/' . str_replace('/', '-', $get_pengajuan->row()->no_terima_barang) ?>"
                                                class="btn btn-submit-black mt-2" target="_blank">SPBR Pemusnahan Original Dp</a>
                                            <a href="<?= base_url() . 'management_inventory/generate_pdf_spbr_pemusnahan_group_kodeprod/' . $signature . '/' . str_replace('/', '-', $get_pengajuan->row()->no_terima_barang) ?>"
                                                class="btn btn-submit-black mt-2" target="_blank">SPBR Pemusnahan dengan Batch Number untuk Accounting</a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="example">
                                                <thead>
                                                    <tr>
                                                        <th>Kodeprod</th>
                                                        <th>Namaprod</th>
                                                        <th>Batch</th>
                                                        <th>ED</th>
                                                        <th>Nama Outlet</th>
                                                        <th>Alasan</th>
                                                        <th>Ket</th>
                                                        <th>Qty Pengajuan</th>
                                                        <th>RBP</th>
                                                        <th>Qty Approval Area</th>
                                                        <th>Ket Princ Area</th>
                                                        <th>Qty Approval HO</th>
                                                        <th>Ket Princ HO</th>
                                                        <th>Status MPM</th>
                                                        <th>Ket MPM</th>
                                                        <th>Qty Terima Pabrik</th>
                                                        <th>Qty Tolak Pabrik</th>
                                                        <th>Ket Pabrik</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($get_pengajuan_detail_accordion->result() as $a) : ?>
                                                        <tr>
                                                            <td><?= $a->kodeprod ?></td>
                                                            <td><?= $a->namaprod ?></td>
                                                            <td><?= $a->batch_number ?></td>
                                                            <td><?= $a->expired_date ?></td>
                                                            <td><?= $a->nama_outlet ?></td>
                                                            <td><?= $a->nama_alasan ?></td>
                                                            <td><?= $a->keterangan ?></td>
                                                            <td><?= $a->jumlah ?></td>
                                                            <td><?= number_format($a->rbp, 2) ?></td>
                                                            <td><?= ($a->qty_approval <= 0 && $a->qty_approval != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->qty_approval</span>" : "$a->qty_approval" ?>
                                                            </td>
                                                            <td><?= ($a->qty_approval <= 0 && $a->qty_approval != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->keterangan_principal_area</span>" : "$a->keterangan_principal_area" ?>
                                                            </td>
                                                            <td><?= ($a->qty_approval_ho <= 0 && $a->qty_approval_ho != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->qty_approval_ho</span>" : "$a->qty_approval_ho" ?>
                                                            </td>
                                                            <td><?= ($a->qty_approval_ho <= 0 && $a->qty_approval_ho != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->keterangan_principal_ho</span>" : "$a->keterangan_principal_ho" ?>
                                                            </td>
                                                            <td><?= ($a->status == 4) ? "<span class='btn btn-danger btn-sm rounded'>$a->nama_status</span>" : "$a->nama_status"  ?>
                                                            </td>
                                                            <td><?= ($a->status == 4) ? "<span class='btn btn-danger btn-sm rounded'>$a->deskripsi</span>" : "$a->deskripsi" ?>
                                                            </td>
                                                            <td><?= ($a->qty_final <= 0 && $a->qty_final != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->qty_final</span>" : "$a->qty_final" ?>
                                                            </td>
                                                            <td><?= ($a->qty_tolak <= 0 && $a->qty_tolak != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->qty_tolak</span>" : "$a->qty_tolak" ?>
                                                            </td>
                                                            <td><?= $a->keterangan_final ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>