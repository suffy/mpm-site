<style>
  /* Style untuk loading overlay */
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }
  
  .loading-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    max-width: 300px;
  }
  
  .spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #dc3545;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .loading-text {
    color: #333;
    font-size: 16px;
    margin-bottom: 10px;
  }
  
  .loading-subtext {
    color: #666;
    font-size: 14px;
  }
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-content">
    <div class="spinner"></div>
    <div class="loading-text">Memproses Revisi Claim</div>
    <div class="loading-subtext">Mohon tunggu sebentar...</div>
  </div>
</div>

<?php if ($get_data->row()->status == 11 || $get_data->row()->status == 12 || $get_data->row()->status == 13 || $get_data->row()->status == 15 || $get_data->row()->status == 16) { ?>
<div class="container-fluid">
  <div class="col-md-12">
    <!-- form -->
    <div class="row mt-3">
      <div class="card">
        <div class="card-body">
          <?= form_open_multipart($url,  ['method' => 'post', 'id' => 'revisiForm'])?>
          <input type="text" name="signature" value="<?= $signature;?>" hidden>
          <div class="row">
            <div class="col-md-6" id="divform1">
              <h4>Revisi Claim NKA</h4>
                <div class="row mt-3" id="divform_no_klaim">
                  <div class="col-md-3">
                    <label for="no_klaim">Nomor Klaim</label>
                  </div>
                  <div class="col-md-7">
                    <input type="hidden" class="form-control" name="no_klaim" id="no_klaim" value="<?= $get_data->row()->nomor_ajuan; ?>" readonly>
                    <label for=""><?= $get_data->row()->nomor_ajuan; ?></label>
                  </div>
                </div>

                <div class="row mt-1" id="divform_no_klaim">
                  <div class="col-md-3">
                    <label for="kategori">Kategori</label>
                  </div>
                  <div class="col-md-7">
                    <input type="hidden" class="form-control" name="kategori" value="<?= $get_data->row()->kategori; ?>" id="kategori" readonly required>
                    <label for=""><?= $get_data->row()->kategori; ?></label>
                  </div>
                </div>

                <div class="row mt-1" id="divform_channel">
                    <div class="col-md-3">
                    <label for="channel">Channel</label>
                  </div>
                  <div class="col-md-7">
                    <input type="hidden" class="form-control" name="channel" value="<?= $get_data->row()->channel; ?>" id="channel" readonly required>
                    <label for=""><?= $get_data->row()->channel; ?></label>
                  </div>
                </div>

                <div class="row mt-1" id="divform_channel">
                  <div class="col-md-3">
                    <label for="channel">PIC</label>
                  </div>
                  <div class="col-md-7">
                    <input type="hidden" class="form-control" name="pic_nama" value="<?= $get_data->row()->pic_nama; ?>" id="pic_nama" readonly required>
                    <label for=""><?= $get_data->row()->pic_nama; ?></label>
                  </div>
                </div>

                <div class="row mt-1" id="divform_channel">
                  <div class="col-md-3">
                    <label for="channel">PIC Email</label>
                  </div>
                  <div class="col-md-7">
                    <input type="hidden" class="form-control" name="pic_email" value="<?= $get_data->row()->pic_email; ?>" id="pic_email" readonly required>
                    <label for=""><?= $get_data->row()->pic_email; ?></label>
                  </div>
                </div>

                <div class="row mt-3" id="divform_no_klaim">
                  <div class="col-md-3">
                    <label for="no_invoice">Nomor Klaim Penta</label>
                  </div>
                  <div class="col-md-7">
                    <input type="text" class="form-control" name="no_klaim" id="no_klaim" value="<?= $get_data->row()->nomor_klaim; ?>">
                  </div>
                </div>

                <div class="row mt-1" id="divform_no_klaim">
                  <div class="col-md-3">
                    <label for="no_invoice">Nomor Invoice/SKP/Trading Term</label>
                  </div>
                  <div class="col-md-7">
                    <input type="text" class="form-control" name="no_invoice" id="no_invoice" value="<?= $get_data->row()->nomor_invoice; ?>">
                  </div>
                </div>

                <div class="row mt-1" id="divform_periode">
                  <div class="col-md-3">
                    <label for="from">Periode</label>
                  </div>
                  <div class="col-md-7">
                    <div class="input-group">
                      <input type="date" name="from" id="from" min="2026-02-01" value="<?= $get_data->row()->periode_start; ?>" class="form-control" required>
                      <input type="date" name="to" id="to" min="2026-02-01" value="<?= $get_data->row()->periode_end; ?>" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row mt-1" id="divform_keterangan">
                  <div class="col-md-3">
                    <label for="keterangan">Keterangan</label>
                  </div>
                  <div class="col-md-7">
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required><?= $get_data->row()->keterangan; ?></textarea>
                  </div>
                </div>

                <div class="row mt-1" id="divform_claim">
                  <div class="col-md-3">
                    <label for="nominal_dpp">Nominal Claim</label>
                  </div>
                  <div class="col-md-7">
                    <input type="number" class="form-control" name="nominal_dpp" id="nominal_dpp" placeholder="Masukan Nominal DPP" value="<?= $get_data->row()->nominal_dpp; ?>" required>
                  </div>
                </div>

                <div class="row mt-2" id="divform_claim">
                  <div class="col-md-3">
                  </div>
                  <div class="col-md-7">
                    <!-- cek can_approve -->
                    <?php 
                    if($can_approve){ ?>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit Data</button>
                    <?php
                    }else{ ?>
                        <label class="form-label" style="color: red; border: 1px solid red; padding: 2px;" >menunggu response dari : <?= $username_on_duty; ?></label>
                    <?php
                    }
                    ?>
                  </div>
                </div>
                <?= form_close(); ?>

                
                </div>
            </div>


                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>

<script>
    const files = <?= $get_data->row()->attachment; ?>;
    // console.log(files['surat_klaim'])

    var kategori = document.getElementById('kategori').value;
    var channel = document.getElementById('channel').value;
    console.log(channel);

    var uploadDiv = '';
    
    function createFileInputRevisi(id, labelText, kategori, required, existingFile = null) {
        let preview = '';
        if (existingFile) {
            preview = `
                <div class="mt-1">
                    Lihat file lama : <a href="<?= base_url();?>/assets/uploads/management_claim/nka/${kategori}/${existingFile}" target="_blank" class="text-dark" style="background-color: var(--bs-warning); padding: 2px 5px; border-radius: 5px;">
                        (${existingFile})
                    </a>
                </div>
            `;
        }

        return (
            '<div class="col-md-12">' +
                '<div class="row mt-1">' +
                    '<div class="col-md-3">' +
                        `<label for="${id}">${labelText} ${required ? '(Wajib)' : ''}</label>` +
                    '</div>' +
                    '<div class="col-md-8">' +
                        `<input type="file" class="form-control" id="${id}" name="${id}" ${required ? 'required' : ''}>` +
                        `<input type="text" class="form-control" id="${id}_old" name="${id}_old" value="${existingFile}" hidden>` +
                        preview +
                    '</div>' +
                '</div>' +
            '</div>'
        );
    }

    if (kategori == 'B2B') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('skk', 'SKK', kategori, false, files['skk']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('faktur_komersial', 'Faktur Komersial', kategori, false, files['faktur_komersial']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>' +
        '</div>';
    } else if (kategori == 'Biaya Relaunch') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Cut Price') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Data Share') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Deposit') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skpr', 'SKPR', kategori, false, files['skpr']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('faktur_komersial', 'Faktur Komersial', kategori, false, files['faktur_komersial']) +
                createFileInputRevisi('realisasi_program', 'Realisasi Program', kategori, false, files['realisasi_program']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Fixed Rabate' || kategori == 'Monthly' || kategori == 'Promotion Cost') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
                createFileInputRevisi('sellout', 'Sellout', kategori, false, files['sellout']) +
                createFileInputRevisi('rincian_penjualan', 'Rincian Penjualan', kategori, false, files['rincian_penjualan']) +
                createFileInputRevisi('treding_term', 'Treding Term', kategori, false, files['treding_term']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Grand Opening') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('skk', 'SKK', kategori, false, files['skk']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('faktur_komersial', 'Faktur Komersial', kategori, false, files['faktur_komersial']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Mailer / leaflet') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('sp3m', 'SP3M (Surat Permohonan Partisipasi Promo Mailer)', kategori, false, files['sp3m']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPH 23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Promo Fund') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPh 23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Promo Instore') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skpr', 'SKPR', kategori, false, files['skpr']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPh 23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Rafaksi') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('promo_agreement', 'Promo Agreement', kategori, false, files['promo_agreement']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Seasonal (New Year, Idul Fitri, Anniversary)') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('faktur_komersial', 'Faktur Komersial', kategori, false, files['faktur_komersial']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('treding_term', 'Treding Term', kategori, false, files['treding_term']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Sewa Backwall') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('perjanjian_sewa', 'Perjanjian Sewa', kategori, false, files['perjanjian_sewa']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Sewa Shelving') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skk', 'Surat Kesepakatan Kerjasama (SKK)', kategori, false, files['skk']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('faktur_komersial', 'Faktur Komersial', kategori, false, files['faktur_komersial']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Add Diskon') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('rekap_claim', 'Rekap Claim Add Diskon', kategori, false, files['rekap_claim']) +
                createFileInputRevisi('rekap_penjualan', 'Rekap Penjualan / Approval', kategori, false, files['rekap_penjualan']) +
                createFileInputRevisi('faktur_komersial', 'Faktur Min 5 (.zip)', kategori, false, files['faktur_komersial']) +
                createFileInputRevisi('form_claim_add_diskon', 'Form Claim Add Diskon', kategori, false, files['form_claim_add_diskon']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Super Hemat') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('kwitansi_outlet', 'Kwitansi Outlet', kategori, false, files['kwitansi_outlet']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'COC') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Band Activation Stadee Rack') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Biaya Wobler') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Sewa Floor Display') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Registrasi New Product') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Peralihan Vendor') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Sewa Gondola') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Membership Fee') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Super Promo') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('tanda_terima', 'Tanda Terima', kategori, false, files['tanda_terima']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Biaya IDM Connect') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Listing') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Compensasi End User') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Billing Discount') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice/Kwitansi', kategori, false, files['invoice']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    } else if (kategori == 'Monthly Service Fee') {
        uploadDiv =
        '<div class="col-md-6" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
                (channel == 'nka_herbana' ? createFileInputRevisi('photo', 'Foto', kategori, true, files['photo']) : '') +
            '</div>'+
        '</div>';
    }

    $("#divform2").remove();
    $("div#divform1").after(uploadDiv);
</script>

<script>
  // Script untuk menampilkan loading saat submit
  document.getElementById('revisiForm').addEventListener('submit', function(e) {
    // Validasi form
    if (this.checkValidity()) {
      // Tampilkan loading overlay
      document.getElementById('loadingOverlay').style.display = 'flex';
      
      // Disable tombol submit untuk mencegah double submit
      const submitBtn = document.getElementById('submitBtn');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';
      }
    }
  });
</script>

<script>
  // Sembunyikan loading jika ada flashdata (setelah halaman direfresh)
  window.addEventListener('load', function() {
    // Cek apakah ada flashdata (indikasi form sudah diproses)
    <?php if($this->session->flashdata('pesan') || $this->session->flashdata('pesan_success')): ?>
      document.getElementById('loadingOverlay').style.display = 'none';
      const submitBtn = document.getElementById('submitBtn');
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Data';
      }
    <?php endif; ?>
  });
</script>