<div class="container-fluid mt-5">
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
        
        <!-- Form -->
        <div class="row mt-2" id="form">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <?= form_open_multipart($url_input_data,  ['method' => 'post', 'class' => 'mt-3']) ?>
                        <div class="row">
                            <div class="biop-form col-md-7" id="divform1">
                                <div class="row mb-5">
                                    <h3 class="form-title">Input Data Biop</h3>
                                </div>
                                
                                <div class="row mt-3" id="divform_kategori">
                                    <div class="col-md-4">
                                        <label for="kategori">Kategori</label>
                                    </div>
                                    <div class="col-md-8">
                                        <Select class="form-select" name="kategori" id="kategori" required>
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

                                <div class="row mt-1" id="divform_keterangan_tempat">
                                    <div class="col-md-4">
                                        <label for="keterangan_tempat">Keterangan Tempat</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea name="keterangan_tempat" id="keterangan_tempat" class="form-control" rows="3" placeholder="Masukan Keterangan Tempat" required></textarea>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4"></div>                                        
                                    <div class="col-md-8">
                                        <input type="text" id="signature" name="signature" value="<?= $signature; ?>" hidden>
                                        <?php 
                                        if($is_authorized) { ?>
                                        
                                            <?php 
                                                if($get_biop->status != 2) { ?>                                        
                                                    <button type="submit" class="btn btn-submit-orange" style="padding : 10px 20px 10px 20px;">Submit Data</button>
                                                <?php
                                                }else{ ?>
                                                    <label for="" style="border: 1px solid black; padding: 10px 20px 10px 20px; border-radius: 5px">menunggu admin biop</label>
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
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md">
                <div class="card" id="tabel">
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
<table id="tabel-data-biop">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Keterangan Tempat</th>
            <th>Biaya</th>
            <th>Attachment</th>
            <th></th>
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
                <td><?= $biop->keterangan_tempat; ?></td>
                <td><?= number_format($biop->biaya); ?></td>
                <td>
                    <?php
                        $attachment = json_decode($biop->attachment);
                        foreach ($attachment as $key_attachment) {?>
                            <a href="<?= base_url() . 'assets/uploads/management_biop/' .$biop->nama_kategori .'/'. $key_attachment ?>" target="_blank">
                                <?= $key_attachment ?></a>
                            <br>
                    <?php } ?>
                </td>
                <td>
                    <?php 
                        if($is_authorized)
                        { ?>
                            <a href="<?= base_url($url_delete_data . $signature . '/' . md5($biop->id) ) ?>" type="button" onclick="return confirm('Are you sure?')" class="delete-button" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                            
                        <?php
                        }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <th><?= number_format($total_biaya); ?></th>
        </tr>
    </tfoot>
</table>
                            </div>
                        </div>

                        <div class="row mt-3" style="text-align: center;">
                            <div class="col-md">
                                <p>Cek kembali data anda. Jika sudah ok, klik button proses di bawah ini :</p>

                                <a href="<?= base_url($url_dashboard);?>" type="button" class="btn btn-submit-back" style="height: 44px; padding: 10px 20px 10px 20px;"><i class="bi bi-arrow-left-square"></i> Back</a>

                                <?php 
                                    if($is_authorized) { ?>
                                        <?php 
                                            if($get_biop->status != 2) { ?>                                        
                                                <a href="<?= base_url($url_proses_user);?>" type="button" class="btn btn-submit-approve" style="height: 44px; padding: 10px 20px 10px 20px;">
                                                <i class="bi bi-check2-square"></i> Approve & Proses Ke Admin Biop</a>
                                            <?php
                                            }else{ ?>
                                                <label for="" style="border: 1px solid black; padding: 10px 20px 10px 20px; border-radius: 5px">menunggu admin biop</label>
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
    function createFileInput(id, labelText, required) {
        return (
            `<div class="row mt-1" id="divform_${id}">
                <div class="col-md-4">
                    <label for="${id}">${labelText} ${
                    required ? "(Wajib)" : ""
                    }</label>
                </div>
                <div class="col-md-8">
                    <input type="file" class="form-control" id="${id}" name="${id}" ${
                    required ? "required" : ""
                    }>
                </div>
            </div>`
        );
    }

    function createInput(id, labelText, placeholder) {
        return (
            `<div class="row mt-1" id="divform_${id}">
                <div class="col-md-4">
                    <label for="${id}">${labelText}</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="${id}" name="${id}" placeholder="${placeholder}" required>
                </div>
            </div>`
        );
    }

    function createInputTanggal(id, labelText) {
        return (
            `<div class="row mt-1" id="divform_${id}">
                <div class="col-md-4">
                    <label for="${id}">${labelText}</label>
                </div>
                <div class="col-md-8">
                    <input type="date" class="form-control" id="${id}" name="${id}" min="<?= $get_biop->from;?>" max="<?= $get_biop->to;?>" required>
                </div>
            </div>`
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
                            <label for="from">From</label>
                            <input type="date" name="from" id="from" min="<?= $get_biop->from;?>" max="<?= $get_biop->to;?>" class="form-control" required>
                        </div>

                        <div class="col-md mt-1">
                            <label for="to">To</label>
                            <input type="date" name="to" id="to" min="<?= $get_biop->from;?>" max="<?= $get_biop->to;?>" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>`
        );
    }

    function createTextarea(id, labelText, placeholder) {
        return (
            `<div class="row mt-1" id="divform_${id}">
                <div class="col-md-4">
                    <label for="${id}">${labelText}</label>
                </div>
                <div class="col-md-8">
                    <textarea class="form-control" id="${id}" name="${id}" rows="3" placeholder="${placeholder}" required></textarea>
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

    if (kategori_terpilih == "3") { // bbm
        var Div =
            '<div class="biop-form col-md-5" id="divform2">' +
                '<div class="row mb-5">' +
                    '<h3 class="form-title">Detail BBM</h3>' +
                '</div>' +
                    createInput("km", "Kilo Meter", "Masukan Jumlah Kilo Meter") +
                    createInput("liter", "Liter", "Masukan Jumlah Liter") +
                    createFileInput("attachment_km", "Attachment Kilo Meter", true) +
                    createFileInput("attachment_struk", "Attachment Struk", true) +
                '</div>' +
            '</div>';
        $("div#divform1").after(Div);

        var DivTanggal = createInputTanggal("tanggal", "Tanggal");
        $("div#divform_kategori").after(DivTanggal);
    } else if (kategori_terpilih == "5") { // jamuan
        var Div =
        '<div class="biop-form col-md-5" id="divform2">' +
            '<div class="row mb-5">' +
                '<h3 class="form-title">Detail Jamuan</h3>' +
            '</div>' +
                createInput("jamuan_tempat", "Tempat Jamuan", "Masukan Tempat Jamuan") +
                createInput("jamuan_alamat", "Alamat", "Masukan Alamat") +
                createInput("jamuan_nama_perusahaan", "Nama Perusahaan", "Masukan Nama Perusahaan") +
                createInput("jamuan_jenis", "Jenis Jamuan", "Masukan Jenis Jamuan") +
                createTextarea("jamuan_pic", "Nama", "Masukan Nama Tamu Yang Dijamu") +
                createTextarea("jamuan_jabatan", "Jabatan", "Masukan Jabatan Tamu Yang Dijamu") +
                createInput("jamuan_jenis_perusahaan", "Jenis Perusahaan", "Masukan Jenis Perusahaan") +
                createFileInput("attachment", "Attachment", true) +
            '</div>' +
        '</div>';
        $("div#divform1").after(Div);
        
        var DivTanggal = createInputTanggal("tanggal", "Tanggal");
        $("div#divform_kategori").after(DivTanggal);
    } else if (kategori_terpilih == "7") { // hotel
        var DivPeriode = createInputPeriode("periode", "Tanggal");
        var Div = createFileInput("attachment", "Attachment", true);
        $("div#divform_kategori").after(DivPeriode);
        $("div#divform_keterangan_tempat").after(Div);
    } else {
        var DivTanggal = createInputTanggal("tanggal", "Tanggal");
        var Div = createFileInput("attachment", "Attachment", true);
        $("div#divform_kategori").after(DivTanggal);
        $("div#divform_keterangan_tempat").after(Div);
    }
});

</script>