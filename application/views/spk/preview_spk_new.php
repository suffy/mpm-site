<style>
    .span-text{
        font-weight: bold;
        /* background-color: #f1f1f1; */
        padding: 5px 10px 5px 10px;
        border-radius: 10px;
        line-height: 25px;
        display: inline-block;
    }
</style>

<?= $this->load->view('spk/component/title'); ?>

<?php echo form_open($url); ?>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="company">Di kirim kepada</label>
    </div>
    <div class="col-md-6">
        <span class="span-text"><?= $company ?></span>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="npwp">NPWP</label>
    </div>
    <div class="col-md-6">
        <span class="span-text"><?= $npwp ?></span>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="alamat">Alamat DP</label>
    </div>
    <div class="col-md-6">
        <span class="span-text"><?= $alamat ?></span>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="email">Email</label>
    </div>
    <div class="col-md-6">
        <span class="span-text"><?= $email ?></span>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="alamat">Tipe Dok</label>
    </div>
    <div class="col-md-5">
        <span class="span-text"><?= $tipe == 'S' ? 'SPK' : 'Alokasi' ?></span>
        <!-- <input type="text" name="tipe_tampil" id="tipe_tampil" value="<?= $tipe == 'S' ? 'SPK' : 'Alokasi' ?>" readonly> -->
        <input type="hidden" name="tipe" id="tipe" class="form-control" value="<?= $tipe ?>" readonly>
        <input type="hidden" name="kode_alamat" id="kode_alamat" class="form-control" value="<?= $kode_alamat ?>" readonly>
        <input type="hidden" name="id_header" id="id_header" class="form-control" value="<?= $id_header ?>" readonly>
        <input type="hidden" name="company" value="<?= $company ?>" readonly>
        <input type="hidden" name="npwp" value="<?= $npwp ?>" readonly>
        <input type="hidden" name="email" value="<?= $email ?>" readonly>

    </div>
</div>

<div class="card-block mt-1 mb-1">
    <div class="row">
        <div class="col-md-12">
<table>
    <thead>
        <tr>
            <th>Principal</th>
            <th>Product</th>
            <th>Order (karton)</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>MOQ (khusus US)</th>
            <th>PP</th>
            <th>Ratio</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            // Inisialisasi variabel
            $current_supplier = '';
            $subtotal_karton = 0;
            $subtotal_berat = 0;
            $subtotal_volume = 0;
            $grandtotal_karton = 0;
            $grandtotal_berat = 0;
            $grandtotal_volume = 0;
            $first_row = true;
            
            foreach ($get_data->result() as $a) :
                
                // Cek jika supplier berubah
                if ($current_supplier != $a->namasupp) 
                {    
                    // Tampilkan subtotal supplier sebelumnya (kecuali untuk baris pertama)
                    if (!$first_row) { ?>
                    <tr style="font-weight: bold; background-color: #e8f4fd;">
                        <td colspan="2" style="text-align: right;">TOTAL</td>
                        <td><?= $subtotal_karton; ?></td>
                        <td><?= $subtotal_berat; ?></td>
                        <td><?= $subtotal_volume; ?></td>
                        <td colspan="3"></td>
                    </tr>
                    <?php
                    }
                    
                    // Reset subtotal untuk supplier baru
                    $current_supplier = $a->namasupp;
                    $subtotal_karton = 0;
                    $subtotal_berat = 0;
                    $subtotal_volume = 0;
                    $first_row = false;
                }
                
                // Akumulasi subtotal per supplier
                $subtotal_karton += $a->jml_karton;
                $subtotal_berat += $a->total_berat;
                $subtotal_volume += $a->total_volume;
                
                // Akumulasi grand total
                $grandtotal_karton += $a->jml_karton;
                $grandtotal_berat += $a->total_berat;
                $grandtotal_volume += $a->total_volume;
        ?>                         
        <tr>
            <td><?= $a->namasupp; ?></td>
            <td><?= $a->namaprod.' ('.$a->kodeprod.')'; ?></td>
            <td style="<?= ($a->jml_karton > $a->pp_karton) ? 'color: red; font-weight: bold;' : '' ?>">
                <?= $a->jml_karton; ?>
            </td>
            <td><?= $a->total_berat; ?></td>
            <td><?= $a->total_volume; ?></td>
            <td><?= $a->moq_us; ?></td>
            <td><?= $a->pp_karton; ?></td>
            <td><?= $a->ratio; ?></td>
        </tr>
        <?php endforeach ?>
        
        <!-- Tampilkan subtotal untuk supplier terakhir -->
        <?php if (!$first_row) { ?>
        <tr style="font-weight: bold; background-color: var(--bs-primary-bg-subtle);">
            <td colspan="2" style="text-align: right;">TOTAL</td>
            <td><?= $subtotal_karton; ?></td>
            <td><?= $subtotal_berat; ?></td>
            <td><?= $subtotal_volume; ?></td>
            <td colspan="3"></td>
        </tr>
        <?php } ?>
        
        <!-- Baris Grand Total -->
        <tr style="font-weight: bold; background-color: var(--bs-danger-bg-subtle);">
            <td colspan="2" style="text-align: right;">GRAND TOTAL:</td>
            <td><?= $grandtotal_karton; ?></td>
            <td><?= $grandtotal_berat; ?></td>
            <td><?= $grandtotal_volume; ?></td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>

            <?php 
            if($status_textarea == 1){ ?>
                <!-- Textarea di bawah tabel -->
                <div style="margin-top: 20px;">
                    <label for="alasan_order" style="display: block; margin-bottom: 8px; font-weight: bold;">Alasan SPK :</label>
                    <textarea 
                        id="alasan_order" 
                        name="alasan_order" 
                        rows="4" 
                        cols="50" 
                        placeholder="Masukkan alasan spk anda di sini..." 
                        required
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-family: Arial, sans-serif;"
                    ></textarea>
                </div>  
            <?php
            }
            ?>           
            
        </div>
    </div>
</div>

<div class="form-check mb-1">
  <input class="form-check-input" name="check" type="checkbox" value="1" id="check" required>
  <label class="form-check-label" for="check">
    Saya sudah mengecek ulang semua data di atas, yaitu data pesanan, informasi pengiriman, purchase plan, ratio, berat, volume, dan lainnya
  </label>
</div>

<div class="form-check mb-4">
  <input class="form-check-input" name="check2" type="checkbox" value="1" id="check2" required>
  <label class="form-check-label" for="check2">
    Khusus US, pesanan akan dikirim ke alamat HO jika total berat kurang dari MOQ
  </label>
</div>


<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
        <a href="<?= base_url('spk/pengiriman') ?>" class="btn btn-submit-black" style="width: 50%">Kembali</a>
        <input type="submit" value="Checkout" class="btn btn-submit-orange" style="width: 50%">
    </div>
</div>

<br><br><br>

<script>
    $(document).ready(function() {
        $('#001').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#002').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#004').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#005').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#010').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#011').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#012').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
    });
</script>