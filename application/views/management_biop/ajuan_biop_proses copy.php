<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mb-2">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12 az-content-label">
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
        
        <div class="row mt-1">
            <div class="col-md">
                <div class="card" id="form">
                    <div class="card-body">
                        <?= form_open_multipart($url_input_data,  ['method' => 'post'])?> 
                            <div class="row mt-3">
                                <div class="col-md-7" id="divform1">
                                    <h5>Input Data BIOP</h5>
                                    <div class="row mt-3" id="divform_kategori">
                                        <div class="col-md-4">
                                            <label for="kategori">Kategori</label>
                                        </div>
                                        <div class="col-md-8">
                                            <Select class="form-select" style="text-transform: capitalize;" name="kategori" id="kategori" required>
                                                <option value=""> -- Pilih Kategori -- </option>
                                                <?php foreach ($get_kategori as $key) { ?>
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
                                    <button type="submit" class="btn btn-submit-black">Submit Data</button>
                                </div>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md">
                <div class="card" id="tabel">
                    <div class="card-body">
                        <div class="row">
                                <h5>Data BIOP</h5>
                        </div>

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
                                        <?php foreach ($get_data_biop as $biop) { ?>
                                            <tr>
                                                <td><?= $biop->tanggal; ?></td>
                                                <td style="text-transform: capitalize;"><?= $biop->nama_kategori; ?></td>
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
                                                <td>
                                                    <a href="<?= base_url($url_delete_data . $signature . '/' . md5($biop->id) ) ?>" type="button" class="btn btn-danger btn-sm">Delete</a>
                                                    <?php if ($biop->nama_kategori == 'bbm' || $biop->nama_kategori == 'jamuan') {?>
                                                            <!-- Tombol trigger -->
                                                            <button type="button" class="btn btn-submit-black btn-sm view-data" data-id="<?= $biop->id;?>">
                                                                Lihat Detail
                                                            </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">
                                                Total
                                            </th>
                                            <th>
                                                Rp. <?= number_format($total_biaya); ?>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-3" style="text-align: center;">
                            <div class="col-md">
                                <p>Cek kembali data anda. Jika sudah ok, klik button proses di bawah ini :</p>
                                <a href="<?= base_url($url_dashboard);?>" type="button" class="btn btn-submit-black"> Back To Dashboard</a>
                                <a href="<?= base_url($url_proses_user);?>" type="button" class="btn btn-warning"> Proses Ke Admin</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
        
        <div class="modal-header">
            <h5 class="modal-title">Detail Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered" id="detailTable">
                <thead>
                    <!-- Baris data akan dimasukkan lewat AJAX -->
                </thead>
                <tbody>
                    <!-- Baris data akan dimasukkan lewat AJAX -->
                </tbody>
            </table>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
    $(document).ready(function() {
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
        // ketika tombol detail di klik
        $(document).on('click', '.view-data', function() {
            var id = $(this).data('id'); // ambil data-id
            
            // panggil AJAX
            $.ajax({
            url: "<?= base_url('management_biop/get_biop_detail_by_id'); ?>", // endpoint ambil data dari server
            type: "GET",
            data: { id: id },
            dataType: "json",
            success: function(res) {
                // kosongkan tabel dulu
                $('#detailTable thead').empty();
                $('#detailTable tbody').empty();

                if (res.nama_kategori === 'bbm') {
                    $('#detailTable thead').append(`
                        <tr>
                            <th rowspan="2" style="text-align: center;">Tanggal</th>
                            <th colspan="2" style="text-align: center;">BBM</th>
                            </tr>
                        <tr>
                            <th style="text-align: center;">Kilo Meter (KM)</th>
                            <th style="text-align: center;">Liter</th>
                        </tr>
                    `); 

                    $('#detailTable tbody').append(`
                        <tr>
                            <td style="text-align: center;">${res.tanggal}</td>
                            <td style="text-align: center;">${formatNumber(res.bbm_km)}</td>
                            <td style="text-align: center;">${formatNumber(res.bbm_liter)}</td>
                        </tr>
                    `);
                } else if (res.nama_kategori === 'jamuan') {
                    $('#detailTable thead').append(`
                        <tr>
                            <th style="text-align: center;" rowspan="2">Tanggal</th>
                            <th style="text-align: center;" colspan="3">Perjamuan</th>
                            <th style="text-align: center;" colspan="4">Relasi Yang Dijamu</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">Tempat</th>
                            <th style="text-align: center;">Alamat</th>
                            <th style="text-align: center;">Jenis Jamuan</th>
                            <th style="text-align: center;">Nama Perusahaan</th>
                            <th style="text-align: center;">Nama Yang Dijamu</th>
                            <th style="text-align: center;">Jabatan</th>
                            <th style="text-align: center;">Jenis Usaha</th>
                        </tr>
                    `); 

                    $('#detailTable tbody').append(`
                        <tr>
                            <td style="text-align: center;">${res.tanggal}</td>
                            <td style="text-align: center;">${res.jamuan_tempat}</td>
                            <td style="text-align: center;">${res.jamuan_alamat}</td>
                            <td style="text-align: center;">${res.jamuan_jenis}</td>
                            <td style="text-align: center;">${res.jamuan_nama_perusahaan}</td>
                            <td style="text-align: center;">${res.jamuan_pic}</td>
                            <td style="text-align: center;">${res.jamuan_pic_jabatan}</td>
                            <td style="text-align: center;">${res.jamuan_jenis_perusahaan}</td>
                        </tr>
                    `);
                }

                // tampilkan modal
                $('#dataModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert("Terjadi kesalahan: " + error);
            }
            });
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

    function createInputTanggal(id, labelText) {
        return (
                `<div class="row mt-1" id="divform_${id}">` +
                    '<div class="col-md-4">' +
                        `<label for="${id}">${labelText}</label>` +
                    "</div>" +
                    '<div class="col-md-8">' +
                        `<input type="date" class="form-control" id="${id}" name="${id}" required>` +
                    "</div>" +
                "</div>"
            );
    }

    function createInputPeriode(id, labelText) {
        return (
                `<div class="row mt-1" id="divform_${id}">
                    <div class="col-md-4">
                        <label for="from">${labelText}</label>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md mt-1">
                                <label for="from" class="form-label">From</label>
                                <input type="date" name="from" id="from" min="2023-12-01" class="form-control" required>
                            </div>

                            <div class="col-md mt-1">
                                <label for="to" class="form-label">To</label>
                                <input type="date" name="to" id="to" min="2023-12-01" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>`
            );
    }

    $("select[name = kategori]").on("change", function () {
  // Hapus dulu jika sudah ada form upload sebelumnya
    $("#divform2").remove();
    $("#divform_attachment").remove();
    $("#divform_tanggal").remove();
    $("#divform_periode").remove();
    
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

        var DivTanggal = createInputTanggal("tanggal", "Tanggal");
        $("div#divform_kategori").after(DivTanggal);
    }  else if (kategori_terpilih == "2") {
        var DivPeriode = createInputPeriode("periode", "Periode");
        var Div = createFileInput("attachment", "Attachment", true);
        $("div#divform_kategori").after(DivPeriode);
        $("div#divform_keterangan").after(Div);
    }  else if (kategori_terpilih == "3") {
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
        
        var DivTanggal = createInputTanggal("tanggal", "Tanggal");
        $("div#divform_kategori").after(DivTanggal);
    } else {
        var DivTanggal = createInputTanggal("tanggal", "Tanggal");
        var Div = createFileInput("attachment", "Attachment", true);
        $("div#divform_kategori").after(DivTanggal);
        $("div#divform_keterangan").after(Div);
    }
});

</script>