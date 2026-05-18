</div>

<div class="container-fluid">
    <div class="row mt-2">
        <div class="col-md-12 az-content-label text-center">
            <?php 
                if($this->session->flashdata('pesan')){ ?>
            <div class="alert alert-danger" role="alert">
                <?= $this->session->flashdata('pesan'); ?>
            </div>
            <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
            <div class="alert alert-success" role="alert">
                <?= $this->session->flashdata('pesan_success'); ?>
            </div>
            <?php
                }
            ?>
        </div>
    </div>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-generate-tab" data-toggle="tab" data-target="#nav-generate"
                type="button" role="tab" aria-controls="nav-generate" aria-selected="true">Generate Berita
                Acara</button>
            <button class="nav-link" id="nav-lapor-tab" data-toggle="tab" data-target="#nav-lapor" type="button"
                role="tab" aria-controls="nav-lapor" aria-selected="false">Lapor Pemusnahan</button>
        </div>
    </nav>

    <div class="tab-content mb-4" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-generate" role="tabpanel" aria-labelledby="nav-generate-tab">
            <form action="<?= base_url($url_berita_acara); ?>" method="post">
                <?php if ($tanggal_pemusnahan) { ?>
                    <a href="<?= base_url().'management_inventory/generate_pdf_berita_acara/'.$signature.'/'.str_replace('/', '-', $get_pengajuan->row()->no_terima_barang) ?>"
                        class="btn btn-submit-black mt-5 mb-3" target="_blank">Download pdf (Berita Acara)</a>
                <?php } ?>
                <div class="row">
                    <div class="col-md-12">
                        <table id="test">
                            <thead>
                                <tr>
                                    <th>Kodeprod</th>
                                    <th>Namaprod</th>
                                    <th>Batch</th>
                                    <th>ED</th>
                                    <th>Nama Outlet</th>
                                    <th>Qty Approval</th>
                                    <th>Qty Pemusnahan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($get_pengajuan_detail->result() as $a) : ?>
                                <tr>
                                    <td><?= $a->kodeprod ?></td>
                                    <td><?= $a->namaprod ?></td>
                                    <td><?= $a->batch_number ?></td>
                                    <td><?= date('d M Y', strtotime($a->expired_date)); ?></td>
                                    <td><?= $a->nama_outlet ?></td>
                                    <td>
                                        <?= $a->qty_approval_ho ?>
                                        <input type="number" class="form-control" name="qty_approval_ho[]"
                                            value="<?= $a->qty_approval_ho ?>" hidden>
                                    </td>
                                    <td>
                                        <input type="number" id="<?= $a->id; ?>" name="id_detail[]"
                                            class="<?= $a->id; ?>" value="<?= $a->id; ?>" hidden>
                                        <?php
                                        if ($a->qty_pemusnahan == null) { ?>
                                        <input type="number" class="form-control" name="qty_pemusnahan[]"
                                            value="<?= $a->qty_approval_ho ?>" min="0" max="<?= $a->qty_approval_ho ?>" required>
                                        <?php }else{ ?>
                                        <input type="number" class="form-control" name="qty_pemusnahan[]"
                                            value="<?= $a->qty_pemusnahan ?>" min="0" max="<?= $a->qty_approval_ho ?>" required>
                                        <?php } ?>
                                    </td>
                                    <td><textarea class="form-control" cols="50" name="keterangan_pemusnahan[]"
                                            placeholder="Masukan keterangan (Opsional)"><?= $a->keterangan; ?></textarea>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <p>Saya akan melakukan pemusnahan pada :</p>

                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label for="status_approval">Tanggal Pemusnahan</label>
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tanggal_pemusnahan" value="<?= $get_pengajuan->row()->tanggal_pemusnahan; ?>" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label for="nama_pemusnahan">Nama PIC Pemusnahan</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nama_pemusnahan" value="<?= $get_pengajuan->row()->nama_pemusnahan; ?>" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12" style="margin-left: 20px!important;">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" required>
                            <label class="form-check-label" style="font-weight: normal!important;" for="defaultCheck1">
                                Saya akan melakukan pemusnahan sesuai dengan data pengajuan retur diatas
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div style="text-align: center;">
                        <?php  if ($pemusnahan_at) { ?>
                        <button type="button" class="btn btn-dark" disabled>data anda sudah masuk</button>
                        <?php
                        }else{ ?>
                        <?php 
                            if (strtolower(substr($site_code,0,3)) == strtolower($this->session->userdata('username')) || $this->session->userdata('id') == 588 || ($site_code == $this->session->userdata('username'))) { ?>
                        <input type="submit" class="btn btn-submit-black" value="Simpan">
                        <?php
                            }
                        } ?>
                        
                        <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back
                            to
                            dashboard</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="nav-lapor" role="tabpanel" aria-labelledby="nav-lapor-tab">
            <form action="<?= base_url($url); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <input type="hidden" name="supp" value="<?= $supp ?>">

                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="file_pemusnahan">File Berita Acara Pemusnahan</label>
                    </div>
                    <div class="col-md-4">
                        <input type="file" class="form-control" id="file_pemusnahan" name="file_pemusnahan" required>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="foto_pemusnahan_1">File Foto Pemusnahan 1</label>
                    </div>
                    <div class="col-md-4">
                        <input type="file" class="form-control" id="foto_pemusnahan_1" name="foto_pemusnahan_1"
                            required>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="foto_pemusnahan_2">File Foto Pemusnahan 2</label>
                    </div>
                    <div class="col-md-4">
                        <input type="file" class="form-control" id="foto_pemusnahan_2" name="foto_pemusnahan_2"
                            required>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="foto_pemusnahan_2">File Video</label>
                    </div>
                    <div class="col-md-4">
                        <input type="file" class="form-control" id="video" name="video" required>
                    </div>
                </div>


                <div class="row mt-4">
                    <div class="col-md-3">
                        <label for="customerid"></label>
                    </div>
                    <div class="col-md-4">
                        <?php 
                        if ($pemusnahan_at) { ?>
                        <button type="button" class="btn btn-dark" disabled>data anda sudah masuk</button>
                        <?php
                        }else{ ?>
                        <?php 
                            if (strtolower(substr($site_code,0,3)) == strtolower($this->session->userdata('username')) || $this->session->userdata('id') == 588 || ($site_code == $this->session->userdata('username'))) { ?>
                        <input type="submit" class="btn btn-submit-black" value="Submit Data">
                        <?php
                            }
                        } ?>
                        <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back
                            to
                            dashboard</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#test").DataTable({
            "paging": false,
            "scrollCollapse": true,
            "scrollY": '500px',
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
        $("#example").DataTable({
            "pageLength": 5,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
    });
</script>