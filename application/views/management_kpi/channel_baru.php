
</div>

<div class="container">

<div class="row mt-1 ms-5">
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
    
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_toko">Nama Toko</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_toko" id="nama_toko" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="alamat">Alamat</label>
        </div>
        <div class="col-md-5">
            <textarea name="alamat" id="alamat" class="form-control" cols="10" rows="5" required></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="sektor">Sektor</label>
        </div>
        <div class="col-md-5">
            <select name="sektor" id="sektor" class="form-control">
                <option value=""> -- Pilih Sektor -- </option>
                <option value="koperasi">Koperasi</option>
                <option value="hotel">Hotel</option>
                <option value="kantin">Kantin</option>
                <option value="pabrik">Pabrik</option>
                <option value="other">Other</option>
            </select>    
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">Tanggal Transaksi</label>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input type="date" name="tanggal" id="tanggal" min = "<?= date('Y-01-01') ?>" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="value_transaksi">Value Transaksi</label>
        </div>
        <div class="col-md-5">
            <input type="number" class="form-control" name="value_transaksi" id="value_transaksi" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="value_transaksi">DP</label>
        </div>
        <div class="col-md-5">
            <select name="site_code" id="site_code" class="form-control" required>
            </select> 
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            Attachment
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">foto</label>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control mb-2" id = "attach1" name="attach1" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">kpi pengembangan channel baru</label>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control mb-2" id = "attach2" name="attach2" required>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <input type="hidden" name='id_workspace' value = <?= $id_workspace ?>>
            <input type="hidden" name='signature_workspace' value = <?= $signature_workspace ?>>
        </div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-generate" id="btnKirim" onclick="return button()">Create Pengembangan Channel Baru</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
            <a href="<?= base_url('kpi/workspace') ?>" class="btn btn-back">back to workspace</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

</div>

<div class="container">

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">No Pelaporan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Nama Toko</th>
                        <th class="text-center">Alamat</th>
                        <th class="text-center">Sektor</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Value</th>
                        <th class="text-center">SiteCode</th>
                        <th class="text-center" style="width: 150px">Attachment</th>
                        <th class="text-center" style="width: 70px">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->no_pelaporan ?></td>
                        <td>                            
                            <a href="<?= base_url('kpi/review_channel_baru/'.$a->signature.'/'.$signature_workspace) ?>"><?= $a->nama_status ?></a>
                        </td>
                        <td><?= $a->nama_toko ?></td>
                        <td><?= $a->alamat ?></td>
                        <td><?= $a->sektor ?></td>
                        <td><?= $a->tanggal ?></td>
                        <td><?= number_format($a->value_transaksi) ?></td>
                        <td><?= $a->site_code ?></td>
                        <td align="center">                                
                            <?php 
                                if ($a->attach_1) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_1 ?>" target="_blank" class="btn btn-pending">attach_1</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>                        
                            <?php 
                                if ($a->attach_2) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_2 ?>" target="_blank" class="btn btn-pending">attach_2</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>     
                        </td>
                        <td align="center">   
                            <a href="<?= base_url('kpi/edit_channel_baru/'.$a->signature.'/'.$signature_workspace) ?>" class="btn btn-pending"><i class="fa-regular fa-pen-to-square"></i></a>                      
                            <a href="<?= base_url('kpi/delete_channel_baru/'.$a->signature.'/'.$signature_workspace) ?>" class="btn btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>                      
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
    

<script>
    function button()
    {        
        var nama_toko = document.getElementById('nama_toko').value;
        var alamat = document.getElementById('alamat').value;
        var tanggal = document.getElementById('tanggal').value;
        var value_transaksi = document.getElementById('value_transaksi').value;
        var site_code = document.getElementById('site_code').value;
        var sektor = document.getElementById('sektor').value;
        var attach_1 = document.getElementById('attach1').value;
        var attach_2 = document.getElementById('attach2').value;
        
        if (nama_toko && alamat && tanggal && value_transaksi && site_code && sektor && attach_1 && attach_2) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $("#example").DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
            scrollX: true
        });
    });
</script>

<script>


    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('kpi/site_code') ?>',
        data: 'userid=29',
        success: function(hasil_branch) {
            $("select[name = site_code]").html(hasil_branch);
        }
    });

</script>


<!-- <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script> -->
