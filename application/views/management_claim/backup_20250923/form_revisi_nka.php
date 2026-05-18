<?php if ($get_data->row()->status == 3 || $get_data->row()->status == 5 || $get_data->row()->status == 7) { ?>
<div class="container-fluid">
    <div class="col-md-12">
        <!-- form -->
        <div class="row mt-3">
            <div class="card">
                <div class="card-body">
                    <?= form_open_multipart($url,  ['method' => 'post'])?>
                        <input type="text" name="signature" value="<?= $signature;?>" hidden>
                        <div class="row mt-3">
                        <div class="col-md" id="divform1">
                                <h5>Form Revisi</h5>
                                <div class="row mt-1" id="divform_no_klaim">
                                    <div class="col-lg-3">
                                        <label for="no_klaim">Nomor Klaim</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="no_klaim" id="no_klaim" value="<?= $get_data->row()->nomor_ajuan; ?>">
                                    </div>
                                </div>

                                <div class="row mt-1" id="divform_no_klaim">
                                    <div class="col-lg-3">
                                        <label for="no_invoice">Nomor Invoice/SKP/Trading Term</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="no_invoice" id="no_invoice" value="<?= $get_data->row()->nomor_invoice; ?>">
                                    </div>
                                </div>

                                <div class="row mt-1" id="divform_no_klaim">
                                    <div class="col-lg-3">
                                        <label for="kategori">Kategori</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input class="form-control" name="kategori" value="<?= $get_data->row()->kategori; ?>" id="kategori" readonly required>
                                    </div>
                                </div>

                                <div class="row mt-1" id="divform_channel">
                                    <div class="col-lg-3">
                                        <label for="channel">Channel</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input class="form-control" name="channel" value="<?= $get_data->row()->channel; ?>" id="channel" readonly required>
                                    </div>
                                </div>

                                <div class="row mt-1" id="divform_periode">
                                    <div class="col-lg-3">
                                        <label for="from">Periode</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="date" name="from" id="from" min="2023-12-01" value="<?= $get_data->row()->periode_start; ?>" class="form-control" required>
                                            <input type="date" name="to" id="to" min="2023-12-01" value="<?= $get_data->row()->periode_end; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1" id="divform_keterangan">
                                    <div class="col-lg-3">
                                        <label for="keterangan">Keterangan Revisi</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukan Keterangan Revisi" required></textarea>
                                    </div>
                                </div>

                                <div class="row mt-1" id="divform_claim">
                                    <div class="col-lg-3">
                                        <label for="nominal_dpp">Nominal Claim</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="number" class="form-control" name="nominal_dpp" id="nominal_dpp" placeholder="Masukan Nominal DPP" value="<?= $get_data->row()->nominal_dpp; ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-3" style="text-align: center;">
                            <div class="col-md-12">
                                <?= form_submit('submit', 'Submit', 'class="btn btn-submit-black"'); ?>
                            </div>
                        </div>
                    <?= form_close(); ?>
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

    var uploadDiv = '';
    
    function createFileInputRevisi(id, labelText, kategori, required, existingFile = null) {
        let preview = '';
        if (existingFile) {
            preview = `
                <div class="mt-1">
                    Lihat file lama : <a href="<?= base_url();?>/assets/uploads/management_claim/nka/${kategori}/${existingFile}" target="_blank" class="text-primary">
                        (${existingFile})
                    </a>
                </div>
            `;
        }

        return (
            '<div class="col-12">' +
                '<div class="row mt-1">' +
                    '<div class="col-md-4">' +
                        `<label for="${id}" class="form-label">${labelText} ${required ? '(Wajib)' : ''}</label>` +
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
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('skk', 'SKK', kategori, false, files['skk']) +
                createFileInputRevisi('kwitansi', 'Kwitansi', kategori, false, files['kwitansi']) +
                createFileInputRevisi('faktur', 'Faktur', kategori, false, files['faktur']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
            '</div>' +
        '</div>';
    } else if (kategori == 'Biaya Relaunch') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur', 'Faktur Pajak', kategori, false, files['faktur']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Cut Price') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice', kategori, false, files['invoice']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Data Share') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('invoice', 'Invoice', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Deposit') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skpr', 'SKPR', kategori, false, files['skpr']) +
                createFileInputRevisi('kwitansi', 'Kwitansi', kategori, false, files['kwitansi']) +
                createFileInputRevisi('faktur', 'Faktur', kategori, false, files['faktur']) +
                createFileInputRevisi('realisasi_program', 'Realisasi Program', kategori, false, files['realisasi_program']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Fixed Rabate' || kategori == 'Monthly' || kategori == 'Promotion Cost') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('invoice', 'Invoice', kategori, false, files['invoice']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
                createFileInputRevisi('sellout', 'Sellout', kategori, false, files['sellout']) +
                createFileInputRevisi('rincian_penjualan', 'Rincian Penjualan', kategori, false, files['rincian_penjualan']) +
                createFileInputRevisi('treding_term', 'Treding Term', kategori, false, files['treding_term']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPH23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Grand Opening') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('skk', 'SKK', kategori, false, files['skk']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
                createFileInputRevisi('kwitansi', 'Kwitansi', kategori, false, files['kwitansi']) +
                createFileInputRevisi('faktur', 'Faktur', kategori, false, files['faktur']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Mailer') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('sp3m', 'SP3M (Surat Permohonan Partisipasi Promo Mailer)', kategori, false, files['sp3m']) +
                createFileInputRevisi('invoice', 'Invoice', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPH 23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Promo Fund') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('invoice', 'Invoice', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potong PPh 23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Promo Instore') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skpr', 'SKPR', kategori, false, files['skpr']) +
                createFileInputRevisi('kwitansi', 'Kwitansi', kategori, false, files['kwitansi']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Rafaksi') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('promo_agreement', 'Promo Agreement', kategori, false, files['promo_agreement']) +
                createFileInputRevisi('kwitansi', 'Kwitansi', kategori, false, files['kwitansi']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Seasonal (New Year, Idul Fitri, Anniversary)') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('nota_debet', 'Nota Debet', kategori, false, files['nota_debet']) +
                createFileInputRevisi('kwitansi', 'Kwitansi', kategori, false, files['kwitansi']) +
                createFileInputRevisi('faktur', 'Faktur', kategori, false, files['faktur']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('treding_term', 'Treding Term', kategori, false, files['treding_term']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Sewa Backwall') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('invoice', 'Invoice', kategori, false, files['invoice']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('skp', 'SKP', kategori, false, files['skp']) +
                createFileInputRevisi('perjanjian_sewa', 'Perjanjian Sewa ', kategori, false, files['perjanjian_sewa']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Sewa Shelving') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                '<h5>Persyaratan</h5>' +
                createFileInputRevisi('surat_klaim', 'Surat Klaim Penta', kategori, false, files['surat_klaim']) +
                createFileInputRevisi('skk', 'surat kesepakatan Kerjasama(SKK)', kategori, false, files['skk']) +
                createFileInputRevisi('kwitansi', 'Kwitansi', kategori, false, files['kwitansi']) +
                createFileInputRevisi('faktur', 'Faktur', kategori, false, files['faktur']) +
                createFileInputRevisi('faktur_pajak', 'Faktur Pajak', kategori, false, files['faktur_pajak']) +
                createFileInputRevisi('bupot', 'Bukti Potongan PPh 23', kategori, false, files['bupot']) +
            '</div>'+
        '</div>';
    } else if (kategori == 'Add Diskon') {
        uploadDiv =
        '<div class="col-md-5" id="divform2">' +
            '<div class="row mt-1" id="divform_upload">' +
                "<h5>Persyaratan</h5>" +
                createFileInputRevisi("surat_klaim", "Surat Klaim Penta", kategori, false, files['surat_klaim']) +
                createFileInputRevisi("rekap_claim", "Rekap Claim Add Diskon", kategori, false, files['rekap_claim']) +
                createFileInputRevisi("rekap_penjualan", "Rekap Penjualan / Approval", kategori, false, files['rekap_penjualan']) +
                createFileInputRevisi("faktur", "Faktur Min 5 (.zip)", kategori, false, files['faktur']) +
            "</div>" +
        "</div>";
    }

    $("#divform2").remove();
    $("div#divform1").after(uploadDiv);
</script>

