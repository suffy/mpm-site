
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

    <?php echo form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-6">

            <div class="col-md-12">
                <h4><?= $title ?></h4>
            </div>


            <div class="col-md-12 mt-4">
                <label for="biaya">Approve atau Reject ?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_verifikasi" id="status_verifikasi1"
                        value="1" checked>
                    <label class="form-check-label" for="status_verifikasi1">
                        Approve
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_verifikasi" id="status_verifikasi2"
                        value="0">
                    <label class="form-check-label" for="status_verifikasi2">
                        Reject
                    </label>
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <label for="keterangan">Keterangan (opsional)</label>
                <div class="d-flex flex-row">
                    <textarea name="keterangan_verifikasi" id="keterangan" cols="30" rows="2"
                        class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <label for="signature">Manage Signature Digital</label>
                <div class="d-flex flex-row">
                    <?php 
                    $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
                    if (file_exists($file)) { ?>
                    <a href="<?= base_url($url2) ?>" class="btn btn-submit-black" target="_blank">
                        <img src="<?= base_url().'assets/uploads/signature/'.$this->session->userdata('username').'-signature.png' ?>"
                            alt="<?= $this->session->userdata('username').'-signature' ?>" width="150px">
                    </a>
                    <?php
                    } else { ?>
                    <a href="<?= base_url($url2) ?>" class="btn btn-submit-black" target="_blank">
                        click here
                    </a>
                    <?php 
                    }
                ?>
                </div>
            </div>

            <div class="col-md-12 mt-2 mb-5">
                <label for="keterangan" ></label>
                <div class="d-flex flex-row">
                    <input class="form-control form-control-md" id="aktivitas" type="hidden" name="id_pengajuan"
                        value="<?= $id_pengajuan ?>" required>
                    <input class="form-control form-control-md" id="signature_pengajuan" type="hidden"
                        name="signature_pengajuan" value="<?= $signature_pengajuan ?>" required>
                <?php 
                $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
                if (file_exists($file)) { 
                    if ($this->session->userdata('id') == $userid_verifikasi1 || $this->session->userdata('id') == $userid_verifikasi2) {
                        if ($status != 9 && $status != 0 && $status != 10) { 
                            echo '<input type="submit" value="Proses Verifikasi RPD" class="btn btn-submit-black">';
                        }else{ 
                            echo '<button type="submit" class="btn btn-submit" disabled>verifikasi anda sudah masuk</button>';
                        }
                    } else {
                        echo '<button type="submit" class="btn btn-submit" disabled>verifikasi</button>';
                    }
                    
                }else{ 
                    echo '<button type="submit" class="btn btn-submit" disabled>verifikasi tidak bisa dilakukan. Mungkin anda belum mengisi signature.</button>';
                    }
                ?>

                </div>
            </div>
        </div>

        <div class="col-md-6 border rounded-10 shadow p-3">

            <div class="col-md-12 mt-4 text-center">
                <h4>Rencana Perjalanan Dinas</h4>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >No RPD</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $no_rpd ?>">
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Pelaksana</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $pelaksana ?>">
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Maksud Perjalanan Dinas</label>
                </div>
                <div class="col-md-8">
                    <textarea name="" id="" cols="30" rows="5"
                        class="form-control"><?= $maksud_perjalanan_dinas ?></textarea>
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Berangkat</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $berangkat ?>">
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Tiba</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="<?= $tiba ?>">
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Status</label>
                </div>
                <div class="col-md-8">
                    <?= $nama_status ?>
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Verifikasi 1</label>
                </div>
                <div class="col-md-8">
                    <?php 
                    if ($verifikasi1_at) {
                        echo $verifikasi1_name.' at '.$verifikasi1_at. ' by '.$username_verifikasi1;
                    }
                ?>
                </div>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Verifikasi 2</label>
                </div>
                <div class="col-md-8">
                    <?php 
                    if ($verifikasi2_at) {
                        echo $verifikasi2_name.' at '.$verifikasi2_at. ' by '.$username_verifikasi2;
                    }
                ?>

                </div>
            </div>

            <div class="col-md-12 mt-3 mb-4 d-flex justify-content-center">
                <div class="col-md-3">
                    <label for="kategori" >Total Biaya</label>
                </div>
                <div class="col-md-8">
                    <font style="color:var(--bs-dark-text-emphasis)" size="5px">Rp. <?= number_format($total_biaya) ?></font>
                </div>
            </div>
        </div>

    </div>

    </form>

    <hr>

    <div class="container">

        <div class="row mt-5">
            <div class="col-md-12 az-content-label text-center">
                Tabel Aktivitas
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th>
                            Tanggal
                        </th>
                        <th>
                            Aktivitas
                        </th>
                        <th>
                            Detail
                        </th>
                        <th>
                            Biaya
                        </th>
                        <th>
                            Claim
                        </th>
                        <th>
                            Keterangan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                foreach ($get_aktivitas->result() as $a) : ?>
                    <tr>
                        <td><?= $a->tanggal_aktivitas; ?></td>
                        <td><?= $a->aktivitas; ?></td>
                        <td><?= $a->detail_aktivitas; ?></td>
                        <td><?= number_format($a->biaya); ?></td>
                        <td>
                            <?= ($a->status_claim == 1) ? 'Ya' : 'No' ?>
                        </td>
                        <td><?= $a->keterangan; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


</div>
<br>

<script>
    $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
    });
</script>