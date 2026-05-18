</div>

<div class="container-fluid">

    <?php if(!$signature){ ?>
    <div class="title-square">Silahkan pilih Pemerataan Product Non OB DP yang ingin anda verifikasi pada tabel di bawah ini</div>
    <?php
}else{ ?>

    <?php echo form_open_multipart($url); ?>

    <div class="row mt-1">
        <div class="col-md-12 az-content-label">
            Verifikasi Pemerataan Product Non OB DP
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
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

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">No Pelaporan</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" value="<?= $get_data->row()->no_pelaporan ?>" readonly>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Status Pelaporan</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" value="<?= $get_data->row()->nama_status ?>" readonly>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">User Pelaksana</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" value="<?= $get_data->row()->name. ' - '.$get_data->row()->email ?>" readonly>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            Informasi Pelaksanaan
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Periode</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" value="<?= $get_data->row()->tanggal ?>" readonly>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Nama Toko</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" value="<?= $get_data->row()->nama_toko ?>" readonly>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Alamat</label>
        </div>
        <div class="col-md-5">
            <textarea type="text" class="form-control" rows="4" readonly><?= $get_data->row()->alamat ?></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Product Kompetitor</label>
        </div>
        <div class="col-md-5">
            <textarea type="text" class="form-control" rows="4" readonly><?= $get_data->row()->product_kompetitor ?></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Product Existing</label>
        </div>
        <div class="col-md-5">
            <textarea type="text" class="form-control" rows="4" readonly><?= $get_data->row()->product_existing ?></textarea>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            Attachment
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-2">
            <label for="nama_program">Foto</label>
        </div>
        <div class="col-md-5">
            <?php 
            if ($get_data->row()->attach_1) { ?>
            <a href="<?= base_url() ?>assets/uploads/kpi/<?= $get_data->row()->attach_1 ?>" class="btn btn-submit-black" target="_blank">
                <?= $get_data->row()->attach_1 ?>
            </a>
            <?php
            }
        ?>
        </div>
    </div>

    <hr>

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            Proses Verifikasi Pemerataan Product Non OB DP
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="approval">Approve / Reject ?</label>
        </div>
        <div class="col-md-5">
            <input type="hidden" name="signature" value="<?= $get_data->row()->signature ?>">
            <select name="approval" class="form-control" required>
                <option value="">-- Pilih -- </option>
                <option value="2">Approve</option>
                <option value="0">Reject</option>
            </select>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-2">
            <label for="nama_program"></label>
        </div>
        <div class="col-md-5">
            <input type="submit" value="Submit Verifikasi" class="btn btn-submit-black">
            <a href="<?= base_url() ?>kpi/manage_activity" class="btn btn-submit-black">Back</a>
        </div>
    </div>

    <?php }?>

    <div class="row mt-5">
        <div class="col-md-12">
            <table id="table-pemerataan-product" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>No Pemerataan</th>
                        <th>Tanggal</th>
                        <th>Pelaksana</th>
                        <th>Nama Toko</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data_table->result() as $a) : ?>
                    <tr>
                        <td align="center"><?= $no++ ?></td>
                        <td><a href="<?= base_url().'kpi/verifikasi_pemerataan_product/'.$a->signature ?>"
                                class="btn btn-submit-black"><?= $a->nama_status ?></a></td>
                        <td><?= $a->no_pelaporan; ?></td>
                        <td><?= $a->tanggal ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->nama_toko ?></td>
                        <td><?= $a->alamat ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#table-pemerataan-product').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>