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
        background-color: #dc3545;
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
        background-color: #ccc;
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
                    <form action="<?= base_url($url_admin_finance_proses) ?>" method="post">
                        <div class="biop-form">
                            <div class="row mt-4">
                                <h5>Nomor Ajuan: <?= $get_biop->no_ajuan; ?></h5>
                                <div class="table-responsive">
                                    <table id="tabel-data-biop">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" colspan="8">Data</th>
                                                <th style="text-align: center;" class="approval-header" colspan="5">Approval</th>
                                                <th style="text-align: center;" class="input-header" colspan="3">Input</th>
                                            </tr>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Kategori</th>
                                                <th>Keterangan</th>
                                                <th>NominalUser</th>
                                                <th>NominalAdmin</th>
                                                <th>NominalAtasan1</th>
                                                <th>NominalAtasan2</th>
                                                <th>NominalFinance</th>

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
                                                    <td><?= number_format($biop->biaya_admin_biop); ?></td>
                                                    <td><?= number_format($biop->biaya_atasan1); ?></td>
                                                    <td><?= number_format($biop->biaya_atasan2); ?></td>
                                                    <td><?= number_format($biop->biaya_admin_finance); ?></td>
                                                    <td>
                                                        <?php if ($biop->flag_tolak_admin_biop == '0') { ?>
                                                            <span style="color: #fff; background-color: #28a745;padding: 5px;border-radius: 5px;">ok</span>
                                                        <?php
                                                        } elseif ($biop->flag_tolak_admin_biop == '1') { ?>
                                                            <span style="color: #fff; background-color: #dc3545;padding: 5px;border-radius: 5px;">no</span>
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
                                                        <input type="number" class="form-control" name="biaya_admin_finance[]" id="biaya_admin_finance" value="<?= $biop->biaya_admin_finance != null ? $biop->biaya_admin_finance : $biop->biaya_atasan2 ; ?>" >
                                                    </td>
                                                    <td><textarea class="form-control" name="keterangan_admin_finance[]" id="keterangan_admin_finance"><?= $biop->keterangan_atasan1 ?></textarea></td>
                                                    <td class="col-status">
                                                        <!-- <Select class="form-control" style="text-transform: capitalize;" name="status[]" id="status" required>
                                                            <option value=""> -- Pilih Status -- </option>
                                                            <option value="0" <?= ($biop->flag_tolak == "0") ? "selected" : "" ?>> Approve </option>
                                                            <option value="1" <?= ($biop->flag_tolak == "1") ? "selected" : "" ?>> Reject </option>
                                                        </Select> -->
                                                        <select class="form-control" name="status[]" id="status" required>
                                                            <option value=""> -- Pilih -- </option>
                                                            <option value="0" <?= ($biop->flag_tolak_admin_finance ? $biop->flag_tolak_admin_finance : $biop->flag_tolak_atasan2) == "0" ? "selected" : "" ?>>ok</option>
                                                            <option value="1" <?= ($biop->flag_tolak_admin_finance ? $biop->flag_tolak_admin_finance : $biop->flag_tolak_atasan2) == "1" ? "selected" : "" ?>>no</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <label for="tanggal_uang_keluar">Tanggal Uang Keluar</label>
                                    <input type="date" class="form-control" name="tanggal_uang_keluar" id="tanggal_uang_keluar" value="<?= $get_biop->tanggal_uang_keluar; ?>">
                                </div>
                                <div class="col-lg-4 mt-4">
                                    <button type="submit" formaction="<?= base_url($url_update_tanggal); ?>" class="btn pastel-orange-btn" style="height:44px;">
                                        Submit
                                    </button>
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

                                                // echo 'status : '.$get_biop->status. ' ('.$pic_name.')';
                                                if($get_biop->status != 6 ) { ?>
                                                    <button type="submit" name="proses" value="2" class="btn btn-submit-approve" style="height: 44px; padding: 10px 20px 10px 20px;"> Save </button>
                                                    <button type="submit" name="proses" value="1" class="btn btn-submit-approve" style="height: 44px; padding: 10px 20px 10px 20px;">
                                                    <i class="bi bi-check2-square"></i> Approve & Proses Ke Head Finance</button>
                                                <?php
                                                }else{ ?>
                                                    <label for="" style="border: 1px solid black; padding: 10px 20px 10px 20px; border-radius: 5px">menunggu verifikasi admin finance</label>
                                                <?php
                                                }
                                            ?>  
                                        <?php 
                                        }else{ ?>
                                            <label for="" style="border: 1px solid black; padding: 10px 20px 10px 20px; border-radius: 5px"><?= $nama_status; ?> </label>
                                        <?php
                                        }
                                    ?>
                                </div>
                            </div>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-data-biop').DataTable({
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
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
    function createFileInput(id, labelText, required) {
        return (
                `<div class="row mt-1" id="divform_${id}">` +
                    '<div class="col-md-4">' +
                        `<label for="${id}">${labelText} ${
                        required ? "(Wajib)" : ""
                        }</label>` +
                    "</div>" +
                    '<div class="col-md-8">' +
                        `<input type="file" class="form-control" id="${id}" name="${id}" ${
                        required ? "required" : ""
                        }>` +
                    "</div>" +
                "</div>"
            );
    }

    function createInput(id, labelText, placeholder) {
        return (
                `<div class="row mt-1" id="divform_${id}">` +
                    '<div class="col-md-4">' +
                        `<label for="${id}">${labelText}</label>` +
                    "</div>" +
                    '<div class="col-md-8">' +
                        `<input type="text" class="form-control" id="${id}" name="${id}" placeholder="${placeholder}" required>` +
                    "</div>" +
                "</div>"
            );
    }

    $("select[name = kategori]").on("change", function () {
  // Hapus dulu jika sudah ada form upload sebelumnya
    $("#divform2").remove();
    $("#divform_attachment").remove();
    
    var kategori_terpilih = document.getElementById("kategori").value;

    if (kategori_terpilih == "1") {
        var Div =
        '<div class="col-md-5" id="divform2">' +
            "<h5>Detail BBM</h5>" +
                createInput("km", "Kilo Meter", "Masukan Jumlah Kilo Meter") +
                createInput("liter", "Liter", "Masukan Jumlah Liter") +
                createFileInput("attachment_km", "Attachment Kilo Meter", true) +
                createFileInput("attachment_struk", "Attachment Struk", true) +
            "</div>" +
        "</div>";
        $("div#divform1").after(Div);
    } else if (kategori_terpilih == "3") {
        var Div =
        '<div class="col-md-5" id="divform2">' +
            "<h5>Detail Jamuan</h5>" +
                createInput("jamuan_tempat", "Tempat Jamuan", "Masukan Tempat Jamuan") +
                createInput("jamuan_alamat", "Alamat", "Masukan Alamat") +
                createInput("jamuan_nama_perusahaan", "Nama Perusahaan", "Masukan Nama Perusahaan") +
                createInput("jamuan_jenis", "Jenis Jamuan", "Masukan Jenis Jamuan") +
                createInput("jamuan_pic", "Nama", "Masukan Nama Tamu Yang Dijamu") +
                createInput("jamuan_jabatan", "Jabatan", "Masukan Jabatan Tamu Yang Dijamu") +
                createInput("jamuan_jenis_perusahaan", "Jenis Perusahaan", "Masukan Jenis Perusahaan") +
                createFileInput("attachment", "Attachment", true) +
            "</div>" +
        "</div>";
        $("div#divform1").after(Div);
    } else {
        var Div = createFileInput("attachment", "Attachment", true);
        $("div#divform_keterangan").after(Div);
    }
});

</script>