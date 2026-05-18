<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-1">
            <div class="col-md">
                <div class="card" id="form">
                    <div class="card-body">
                        <?= form_open_multipart($url,  ['method' => 'post'])?> 
                            <div class="row mt-3">
                                <div class="col-md-7" id="divform1">
                                    <h5>Input Data BIOP</h5>
                                    <div class="row mt-3" id="divform_tanggal">
                                        <div class="col-md-4">
                                            <label for="tanggal">Tanggal</label> 
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="date" name="tanggal" id="tanggal" min="2023-12-01" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="row mt-1" id="divform_kategori">
                                        <div class="col-md-4">
                                            <label for="kategori">Kategori</label>
                                        </div>
                                        <div class="col-md-8">
                                            <Select class="form-select" style="text-transform: capitalize;" name="kategori" id="kategori" required>
                                                <option value=""> -- Pilih Kategori -- </option>
                                                <?php foreach ($get_kategori->result() as $key) { ?>
                                                    <option value="<?= $key->id; ?>"> <?= $key->nama_kategori ?> </option>
                                                <?php }?>
                                            </Select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-1" id="divform_biaya">
                                        <div class="col-md-4">
                                            <label for="biaya">Biaya</label> 
                                        </div>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control" name="biaya" id="biaya" placeholder="Masukan Jumlah Biaya" required>
                                        </div>
                                    </div>
        
                                    <div class="row mt-1" id="divform_keterangan">
                                        <div class="col-md-4">
                                            <label for="keterangan">Keterangan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukan Keterangan" required></textarea>
                                        </div>
                                    </div>
                                </div>
        
                            </div>
        
                            <hr>
            
                            <div class="row mt-3" style="text-align: center;">
                                <div class="col-md-12">
                                    <input type="text" id="signature" name="signature" value="<?= $signature; ?>" hidden>
                                    <?= form_submit('submit', 'Submit', 'class="btn btn-submit-black"'); ?>
                                </div>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md">
                <div class="card" id="form">
                    <div class="card-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-biop-tab" data-toggle="tab" data-target="#nav-biop"
                                    type="button" role="tab" aria-controls="nav-biop" aria-selected="true">Data BIOP</button>
                                <button class="nav-link" id="nav-detail-tab" data-toggle="tab" data-target="#nav-detail" type="button"
                                    role="tab" aria-controls="nav-detail" aria-selected="false">Detail</button>
                            </div>
                        </nav>

                        <div class="tab-content mb-4" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-biop" role="tabpanel" aria-labelledby="nav-biop-tab">
                                <div class="row mt-3">
                                    <div class="table-responsive">
                                        <table id="tabel-data-biop">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Kategori</th>
                                                    <th>Biaya</th>
                                                    <th>Keterangan</th>
                                                    <th>Attachment</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($get_data_biop->result() as $biop) { ?>
                                                    <tr>
                                                        <td><?= $biop->tanggal; ?></td>
                                                        <td><?= $biop->nama_kategori; ?></td>
                                                        <td>Rp. <?= number_format($biop->biaya); ?></td>
                                                        <td><?= $biop->keterangan; ?></td>
                                                        <td>
                                                            <?php
                                                                $attachment = json_decode($biop->attachment);
                                                                foreach ($attachment as $key_attachment) {?>
                                                                    <a href="<?= base_url() . 'assets/uploads/management_biop/' .$biop->nama_kategori .'/'. $key_attachment ?>">
                                                                        <?= $key_attachment ?></a>
                                                                    <br>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a href="<?= base_url('management_biop/ajuan_biop_delete_data/' . $signature . '/' . md5($biop->id) ) ?>" type="button" class="btn btn-danger btn-sm">Delete</a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab">
                                <div class="row mt-3">
                                    <h5>Detail BBM</h5>
                                    <div class="table-responsive">
                                        <table id="tabel-detail-bbm">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Tanggal</th>
                                                    <th rowspan="2">Kategori</th>
                                                    <th style="text-align: center;" colspan="2">BBM</th>
                                                </tr>
                                                <tr>
                                                    <th>Kilo Meter (KM)</th>
                                                    <th>Liter</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($get_data_biop->result() as $bbm) { ?>
                                                    <?php if ($bbm->nama_kategori == 'bbm') { ?>
                                                        <tr>
                                                            <td><?= $bbm->tanggal; ?></td>
                                                            <td><?= $bbm->nama_kategori; ?></td>
                                                            <td><?= number_format($bbm->bbm_km); ?></td>
                                                            <td><?= $bbm->bbm_liter; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <h5>Detail Perjamuan</h5>
                                    <div class="table-responsive">
                                        <table id="tabel-detail-jamuan">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Tanggal</th>
                                                    <th rowspan="2">Kategori</th>
                                                    <th style="text-align: center;" colspan="3">Perjamuan</th>
                                                    <th style="text-align: center;" colspan="4">Relasi Yang Dijamu</th>
                                                </tr>
                                                <tr>
                                                    <th>Tempat</th>
                                                    <th>Alamat</th>
                                                    <th>Jenis Perjamuan</th>
                                                    <th>Nama Perusahaan</th>
                                                    <th>Nama Yang Dijamu</th>
                                                    <th>Jabatan</th>
                                                    <th>Jenis Usaha</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($get_data_biop->result() as $jamuan) { ?>
                                                    <?php if ($jamuan->nama_kategori == 'jamuan') { ?>
                                                        <tr>
                                                            <td><?= $bbm->tanggal; ?></td>
                                                            <td><?= $bbm->nama_kategori; ?></td>
                                                            <td><?= $jamuan->jamuan_tempat; ?></td>
                                                            <td><?= $jamuan->jamuan_alamat; ?></td>
                                                            <td><?= $jamuan->jamuan_jenis; ?></td>
                                                            <td><?= $jamuan->jamuan_nama_perusahaan; ?></td>
                                                            <td><?= $jamuan->jamuan_pic; ?></td>
                                                            <td><?= $jamuan->jamuan_pic_jabatan; ?></td>
                                                            <td><?= $jamuan->jamuan_jenis_perusahaan; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2" style="text-align: center;">
                                <div class="col-md">
                                    <p>Cek kembali data anda. Jika sudah ok, klik Button di bawah ini :</p>
                                    <a href="<?= base_url($url_proses_user);?>" type="button" class="btn btn-warning"> Proses Ke Admin</a>
                                </div>
                            </div>
                        </div>
                    </div>
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
        $('#tabel-detail-bbm').DataTable({
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "info" : false,
            "searching" : false,
            "lengthChange" : false,
            "width": 100% !important
            // scrollX: true,
        });
        $('#tabel-detail-jamuan').DataTable({
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "info" : false,
            "searching" : false,
            "lengthChange" : false,
            "width": 100% !important
            // scrollX: true,
        });
    });

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