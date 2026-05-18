<style>
    #form {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.5s ease;
}

    #form.show {
        max-height: 100%; /* cukup besar agar semua konten terlihat */
        opacity: 1;
        transition: all 0.15s ease-in-out;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
</style>

</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 text-center">
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

    <button onclick="toggleKonten()" class="btn btn-submit-black" id="button_form">Form Ajuan</button>

    <div class="card" id="form">
        <div class="card-body">
            <?= form_open_multipart($url,  ['method' => 'post'])?> 
                <div class="row mt-3">
                    <div class="col-md-7" id="divform1">
                        <h5>Form</h5>
                        <div class="row mt-1" id="divform_no_klaim">
                            <div class="col-lg-4">
                                <label for="no_klaim">Channel</label> 
                            </div>
                            <div class="col-lg-8">
                                <select name="channel" id="channel" class="form-select">
                                    <option value="">-- Pilih Channel --</option>
                                    <option value="nka">NKA</option>
                                    <option value="pharma">PHARMA</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-1" id="divform_kategori">
                            <div class="col-lg-4">
                                <label for="kategori">Kategori</label>
                            </div>
                            <div class="col-lg-8">
                                <Select class="form-select" name="kategori" id="kategori" required>
                                    <option value=""> -- Pilih Kategori -- </option>
                                </Select>
                            </div>
                        </div>
                        
                        <div class="row mt-1" id="divform_no_klaim">
                            <div class="col-lg-4">
                                <label for="no_klaim">Nomor Klaim</label> 
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="no_klaim" id="no_klaim" placeholder="Masukan Nomor Klaim" required>
                            </div>
                        </div>
            
                        <div class="row mt-1" id="divform_no_invoice">
                            <div class="col-lg-4">
                                <label for="no_invoice">Nomor Invoice/SKP/Trading Term</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="no_invoice" id="no_invoice" placeholder="Masukan No Invoice / SKP / Trading Term" required>
                            </div>
                        </div>

                        <div class="row mt-1" id="divform_periode">
                            <div class="col-lg-4">
                                <label for="from">Periode</label> 
                            </div>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="date" name="from" id="from" min="2023-12-01" class="form-control" required>
                                    <input type="date" name="to" id="to" min="2023-12-01" class="form-control" required>
                                </div>
                            </div>
                        </div>
            
                        <div class="row mt-1" id="divform_keterangan">
                            <div class="col-lg-4">
                                <label for="keterangan">Keterangan</label>
                            </div>
                            <div class="col-lg-8">
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukan Keterangan Klaim" required></textarea>
                            </div>
                        </div>
            
                        <div class="row mt-1" id="divform_nominal_dpp">
                            <div class="col-lg-4">
                                <label for="nominal_dpp">Nominal Claim</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="number" class="form-control" name="nominal_dpp" id="nominal_dpp" placeholder="Masukan Nominal Claim" required>
                            </div>
                        </div>
            
                        <div class="row mt-1" id="divform_nama">
                            <div class="col-lg-4">
                                <label for="nama">Nama</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="Text" class="form-control" name="nama" id="nama" placeholder="Masukan Nama" required>
                            </div>
                        </div>
            
                        <div class="row mt-1" id="divform_email">
                            <div class="col-lg-4">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Masukan Email" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3" style="text-align: center;">
                    <div class="col-md-12">
                        <?= form_submit('submit', 'Submit Pengajuan Claim', 'class="btn btn-submit-black"'); ?>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-submit-orange" onclick="convertTable()" style="border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Convert data below to Excel</button>
                </div>
            </div>

            <div class="card-block mt-3 mb-5">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabel-ajuan-claim">
                            <thead>
                                <tr>
                                    <th class="text-center">No Ajuan</th>
                                    <th class="text-center">No Klaim</th>
                                    <th class="text-center">No Invoice</th>
                                    <th class="text-center">Channel</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Key Account</th>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Keterangan</th> 
                                    <th class="text-center">On Duty</th> 
                                    <th class="text-center">Status</th>     
                                </tr>
                            </thead>
                            <tbody>     
                                <?php foreach ($get_data->result() as $key)  {?>
                                    <tr>
                                        <td><?= $key->nomor_ajuan;?></td>
                                        <td><?= $key->nomor_klaim;?></td>
                                        <td><?= $key->nomor_invoice;?></td>
                                        <td style="text-transform: uppercase;"><?= $key->channel;?></td>
                                        <td><?= $key->kategori;?></td>
                                        <td><?= $key->channel == 'nka' ? $key->key_account : '-';?></td>
                                        <td>
                                            <?php
                                                if($key->periode_end != null){
                                                    echo date( 'd M Y', strtotime($key->periode_start)) . ' - ' . date( 'd M Y', strtotime($key->periode_end));
                                                } else {
                                                    echo date( 'M Y', strtotime($key->periode_start));
                                                }
                                            ;?>
                                        </td>
                                        <td><?= $key->keterangan;?></td>
                                        <td style="text-transform: capitalize;"><?= $key->on_duty_name;?></td>
                                        <td style="text-transform: uppercase;">
                                            <?php 
                                                if ($key->status == 1) { // PROSES PENDING KAM
                                                    $color = "btn-warning btn-sm rounded";
                                                } elseif($key->status == 2){ // PROSES PENDING MPM
                                                    $color = "btn-warning btn-sm rounded";
                                                } elseif($key->status == 3){ // PROSES REJECT KAM
                                                    $color = "btn-danger btn-sm rounded"; 
                                                } elseif($key->status == 4){ // PROSES PENDING ADMIN MPM
                                                    $color = "btn-warning btn-sm rounded";
                                                } elseif($key->status == 5){ // PROSES REJECT MPM
                                                    $color = "btn-danger btn-sm rounded";
                                                } elseif($key->status == 6){ // PROSES APPROVE ADMIN MPM
                                                    $color = "btn-success btn-sm rounded";
                                                } elseif($key->status == 7){ // PROSES REJECT ADMIN MPM
                                                    $color = "btn-danger btn-sm rounded";
                                                }                           
                                            ?>
                                    <a href='<?= base_url("$url_detail/$key->signature"); ?>'
                                        class="btn <?= $color ?> btn-sm" target="_blank"><?= $key->nama_status ?></a>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("select[name = channel]").on("change", function() 
    {    
        $("#divform_key_account").remove();
        let channel = document.getElementById('channel').value;   
        // alert(channel);         
        
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_kategori_nka') ?>',
            data: {
                'channel': channel,     
            },
            success: function(result) {
                $("select[name = kategori]").html(result);
            }
        });
        
        if (channel === 'nka') {
            var divKeyAccount =
                `<div class="row mt-1" id="divform_key_account">
                    <div class="col-lg-4">
                        <label for="key_account">Key Account</label>
                    </div>
                    <div class="col-lg-8">
                            <Select class="form-select" name="key_account" id="key_account">
                            <option value=""> -- Pilih Account -- </option>
                            <?php foreach ($key_account->result() as $key) {
                                    echo '<option value="'.$key->key_account. '">'.$key->key_account.'</option>';
                                } ?>
                            </Select>
                    </div>
                </div>`
                
            $("div#divform_no_invoice").after(divKeyAccount);
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#tabel-ajuan-claim').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // scrollX: true,
        });
    });

</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-ajuan-claim"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>

<script>
    function toggleKonten() {
        const form = document.getElementById('form');
        const tombol_form = document.getElementById('button_form');

        form.classList.toggle('show');

        if (form.classList.contains('show')) {
            tombol_form.textContent = 'Close Form';
        } else {
            tombol_form.textContent = 'Form Ajuan';
        }
    }
</script>

<script src="<?= base_url('assets/js/form_image_retur_nka.js') ?>"></script>