

</div>

<div class="container-fluid">
    <div class="row mt-1">
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
    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
</form>

    <?= form_open_multipart($url); ?>

    <div class="row mt-5">
        <div class="col-lg-2">
            <label for="nama_program">Kategori</label>
        </div>
        <div class="col-lg-5">
            <select name="kategori" class="form-control" id="kategori" required>
                <option value=""> -- Pilih Kategori -- </option>
                <option value="event">Event</option>
                <option value="channel_baru">Pengembangan channel baru</option>
                <option value="topping_up">Topping UP</option>
                <option value="branding">Branding</option>
                <option value="market_survey">Market Survey</option>
            </select>
        </div>
    </div>   
    
    <div class="row mt-2">
        <div class="col-lg-2">
            <label for="nama_program">Periode</label>
        </div>
        <div class="col-md-5">
            <input type="month" name="periode" class="form-control" max="<?= date('Y-m'); ?>" min="<?= date('Y-01'); ?>" id="periode" required>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Submit Pelaporan</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th class="text-center col-1"style="width: 1px;">No</th>
                        <th style="width: 100px;">Kategori</th>
                        <th style="width: 100px;">Karyawan</th>
                        <th style="width: 100px;">Jabatan</th>
                        <th style="width: 100px;">Periode</th>
                        <th class="text-center col-1">Jumlah</th>
                        <th class="text-center col-1">Review</th>
                        <th class="text-center" style="width: 12%;">Action</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php 
                    $no = 1;
                    foreach ($get_workspace->result() as $a) : ?>
                    <tr>
                        <td align="center"><?= $no++ ?></td>
                        <td><?= $a->kategori ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->jabatan ?></td>
                        <td><?= $a->tahun.'-'.$a->bulan.'' ?></td>
                        <td class="text-center"><a href="<?= base_url('kpi/manage_workspace/'.$a->signature) ?>" class="btn-submit-black"><?= $a->count_event ?></a></td>
                        <td class="text-center"><a href="#" class="btn-submit-black"><?= $a->count_review ?></a></td>
                        <td align="center">   
                            <a href="<?= base_url('kpi/manage_workspace/'.$a->signature) ?>" class="btn-submit-black"><i class="fa-regular fa-list" style="color: grey"></i></a>
                            <a href="<?= base_url('kpi/reload_workspace/'.$a->signature) ?>" class="btn-submit-black"><i class="fa-regular fa-repeat" style="color: grey"></i></a>
                            <a href="<?= base_url('kpi/delete_workspace/'.$a->signature) ?>" class="btn-submit-black" onclick="return confirm('Anda ingin menghapus data ini ?')" style = "background-color: red"><i class="fa-solid fa-trash-can" style="color: white"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>      
                </tbody>
            </table>

        </div>
    </div>

    <br>
    
<script>
    function button()
    {
        var kategori   = document.getElementById('kategori').value;
        var periode = document.getElementById('periode').value;

        if (kategori && periode) {
            $("#btnKirim").hide();
            $("#btnLoading").show();
        }
    }
</script>

<script>
    $(document).ready(function () {
        $("#btnLoading").hide();
        $("#example").DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [0, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ]
        });
    });
</script>
