<style>
    /* Styling dasar untuk tabel */
    #tabel-data-biop {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }
    
    #tabel-data-biop th, #tabel-data-biop td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    
    #tabel-data-biop th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    
    /* Styling untuk header dengan warna khusus */
    #tabel-data-biop thead tr:first-child th {
        text-align: center;
    }
    
    #tabel-data-biop thead tr:nth-child(2) th {
        text-align: center;
    }
    
    .approval-header {
        background-color: #e3e3e3 !important;
        color: black !important;
    }
    
    .input-header {
        background-color: #F9F8F6 !important;
        color: black !important;
    }
    
    /* Styling untuk status badge */
    .status-badge {
        padding: 5px;
        border-radius: 5px;
        color: #fff;
        display: inline-block;
        text-align: center;
    }
    
    .status-approve {
        background-color: #28a745;
    }
    
    .status-reject {
        background-color: #A72703;
    }
    
    /* Styling untuk toggle switch */
    .toggle-container {
        display: flex;
        align-items: center;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        margin-right: 10px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #A72703;
        transition: .4s;
        border-radius: 34px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #28a745;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
    
    .toggle-label {
        font-size: 14px;
    }
    
    /* Styling untuk kolom dengan lebar khusus */
    .col-biaya-adjustment {
        width: 150px;
    }
    
    .col-keterangan {
        width: 200px;
    }
    
    .col-status {
        width: 120px;
    }
    
    /* Styling untuk input dan textarea */
    .form-control {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    
    textarea.form-control {
        min-height: 60px;
        resize: vertical;
    }
</style>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-2">
            <div class="col-md">
                <div class="card" id="tabel">
                <form action="<?= base_url($url_admin_proses) ?>" method="post">
                    <div class="col-md">
                        <div class="row mt-4">
                            <h5>Nomor Ajuan: <?= $get_biop->no_ajuan; ?></h5>
                            <div class="table-responsive">
    <table id="tabel-data-biop">
        <thead>
            <tr>
                <th style="text-align: center;" colspan="4">Data</th>
                <th style="text-align: center;" class="approval-header" colspan="5">Approval</th>
                <th style="text-align: center;" class="input-header" colspan="3">Input</th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>NominalUser</th>
                <th class="approval-header">admin</th>
                <th class="approval-header">atasan1</th>
                <th class="approval-header">atasan2</th>
                <th class="approval-header">finance</th>
                <th class="approval-header">head finance</th>
                <th class="input-header col-biaya-adjustment">Biaya Adjustment</th>
                <th class="input-header col-keterangan">Keterangan</th>
                <th class="input-header col-status">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($get_data_biop as $biop) { ?>
                <tr>
                    <td>
                        <?php
                        $date = new DateTime($biop->tanggal);
                        echo $date->format('d M');
                        ?>                         
                    </td>
                    <td><?= $biop->nama_kategori; ?></td>
                    <td><?= $biop->keterangan; ?></td>
                    <td><?= number_format($biop->biaya); ?></td>
                    <td>
                        <?php if ($biop->flag_tolak_admin_biop == '0') { ?>
                            <span class="status-badge status-approve">ok</span>
                        <?php
                        } elseif ($biop->flag_tolak_admin_biop == '1') { ?>
                            <span class="status-badge status-reject">no</span>
                        <?php
                        } else {
                            echo '';
                        } ?>
                    </td>
                    <td>
                        <?php if ($biop->flag_tolak_atasan1 == '0') { ?>
                            <span class="status-badge status-approve">ok</span>
                        <?php
                        } elseif ($biop->flag_tolak_atasan1 == '1') { ?>
                            <span class="status-badge status-reject">no</span>
                        <?php
                        } else {
                            echo '';
                        } ?>
                    </td>
                    <td>
                        <?php if ($biop->flag_tolak_atasan2 == '0') { ?>
                            <span class="status-badge status-approve">ok</span>
                        <?php
                        } elseif ($biop->flag_tolak_atasan2 == '1') { ?>
                            <span class="status-badge status-reject">no</span>
                        <?php
                        } else {
                            echo '';
                        } ?>
                    </td>
                    <td>
                        <?php if ($biop->flag_tolak_admin_finance == '0') { ?>
                            <span class="status-badge status-approve">ok</span>
                        <?php
                        } elseif ($biop->flag_tolak_admin_finance == '1') { ?>
                            <span class="status-badge status-reject">no</span>
                        <?php
                        } else {
                            echo '';
                        } ?>
                    </td>
                    <td>
                        <?php if ($biop->flag_tolak_head_finance == '0') { ?>
                            <span class="status-badge status-approve">ok</span>
                        <?php
                        } elseif ($biop->flag_tolak_head_finance == '1') { ?>
                            <span class="status-badge status-reject">no</span>
                        <?php
                        } else {
                            echo '';
                        } ?>
                    </td>
                    <td>
                        <input type="hidden" name="id_detail[]" id="id_detail" value="<?= $biop->id; ?>">
                        <!-- <input type="number" class="form-control" name="biaya_admin_biop[]" max="<?= $biop->biaya;?>" id="biaya_admin_biop" value="<?= $biop->biaya_admin_biop != null ? $biop->biaya_admin_biop : $biop->biaya ; ?>" > -->
                        <input type="number" class="form-control" name="biaya_admin_biop[]"  id="biaya_admin_biop" value="<?= $biop->biaya_admin_biop != null ? $biop->biaya_admin_biop : $biop->biaya ; ?>" >
                    </td>
                    <td><textarea class="form-control" name="keterangan_admin_biop[]" id="keterangan_admin_biop"><?= $biop->keterangan_admin_biop ?></textarea></td>
                    <td class="col-status">
                        <Select class="form-control" name="status[]" id="status" required>
                            <option value=""> -- Pilih -- </option>
                            <option value="0" <?= ($biop->flag_tolak_admin_biop == "0") ? "selected" : "" ?>>ok</option>
                            <option value="1" <?= ($biop->flag_tolak_admin_biop == "1") ? "selected" : "" ?>>no</option>
                        </Select>
                    </td>
                    <!-- <td class="col-status">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" name="status[]" value="0" <?= ($biop->flag_tolak_admin_biop == "0") ? "checked" : "" ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label"><?= ($biop->flag_tolak_admin_biop == "0") ? "ok" : "no" ?></span>
                        </div>
                    </td> -->
                </tr>
            <?php } ?>
        </tbody>
    </table>
                            </div>
                        </div>

                        <div class="row mt-3" style="text-align: center;">
                            <div class="col-md">
                                <p>Cek kembali data anda. Jika sudah ok, klik button proses di bawah ini :</p>

                                <a href="<?= base_url($url_dashboard);?>" type="button" class="btn btn-submit-back" style="height: 44px; padding: 10px 20px 10px 20px;"><i class="bi bi-arrow-left-square"></i> Back</a>

                                <?php 
                                    if($is_authorized) { ?>
                                        <!-- <a href="<?= base_url($url_revisi);?>" type="button" class="btn btn-submit-revisi" style="height: 44px; padding: 10px 20px 10px 20px;"><i class="bi bi-x-square"></i> Revisi BIOP</a>   -->

                                        <button type="submit" name="proses" value="0" class="btn btn-submit-revisi" style="height: 44px; padding: 10px 20px 10px 20px;"><i class="bi bi-x-square"></i> Revisi BIOP</button>
                                        
                                        <?php 
                                            if($get_biop->status != 3 ) { ?>
                                                <button type="submit" class="btn btn-submit-approve" name="proses" value="1" style="height: 44px; padding: 10px 20px 10px 20px;">
                                                <i class="bi bi-check2-square"></i> Approve & Proses Ke Atasan 1</button>
                                            <?php
                                            }else{ ?>
                                                <label for="" style="border: 1px solid black; padding: 10px 20px 10px 20px; border-radius: 5px">menunggu verifikasi atasan 1</label>
                                            <?php
                                            }
                                        ?>  
                                    <?php 
                                    }else{ ?>
                                        <label for="" style="border: 1px solid black; padding: 10px 20px 10px 20px; border-radius: 5px"><?= $nama_status. ' ('.$pic_name.')'; ?> </label>
                                    <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- <form action="<?= base_url($url_revisi) ?>" method="post">
                    <button type="submit" class="btn btn-submit-revisi" style="height: 44px; padding: 10px 20px 10px 20px;"><i class="bi bi-x-square"></i> Revisi BIOP</button>
                </form> -->

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-data-biop-accordion').DataTable({
            "paging" : false,
            scrollCollapse: true,
            scrollY: '50vh'
        });

        $('#tabel-data-biop').DataTable({
            "info" : false,
            "searching" : true,
            "lengthChange" : false,
            "paging" : false,
            // scrollX: true,
        });
    });
</script>

<script>
    function toggleKonten() {
        const form = document.getElementById('form');
        const tombol_form = document.getElementById('button_form');

        form.classList.toggle('show');

        if (form.classList.contains('show')) {
            tombol_form.textContent = 'Close Detail';
        } else {
            tombol_form.textContent = 'Lihat Detail';
        }
    }
</script>

<script>
    // JavaScript untuk mengubah label toggle saat status berubah
    document.addEventListener('DOMContentLoaded', function() {
        const toggleSwitches = document.querySelectorAll('.toggle-switch input');
        
        toggleSwitches.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const label = this.closest('.toggle-container').querySelector('.toggle-label');
                if (this.checked) {
                    label.textContent = 'ok';
                    this.value = '0';
                } else {
                    label.textContent = 'no';
                    this.value = '1';
                }
            });
        });
    });
</script>