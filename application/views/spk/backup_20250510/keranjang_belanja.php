<?= $this->load->view('spk/component/title'); ?>

<?php echo form_open_multipart($url_import); ?>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="expose" class="form-label">File Import</label>
    </div>
    <div class="col-md-4">
        <input type="file" class="form-control" name="file" required>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">

    </div>
    <div class="col-md-9">
        <button type="submit" class="btn btn-submit-orange" style="height: 44px; width: 80px">Import</button>
        <a href="<?= base_url('spk/export_template_spk') ?>" class="btn btn-submit-black">Download Template</a>
        <button type="button" class="btn btn-submit-black" onclick="convertTable()">Export to Excel</button>
        <a href="<?= base_url('spk/list_order') ?>" class="btn btn-submit-black">go to list order</a>
    </div>
</div>
<?= form_close(); ?>

<?php echo form_open($url); ?>
<div class="card-block mt-5 mb-1">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel-data" class="display" style="display: inline-block; overflow-y: scroll; width: 100%">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th width="20%">Principal</th>
                        <th width="10%">Kode Produk</th>
                        <th>Nama Produk</th>
                        <th width="12%">Jumlah Karton</th>
                        <th width="5%" class="text-center">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $a->namasupp ?></td>
                            <td><?= $a->kodeprod ?></td>
                            <td><?= $a->namaprod ?></td>
                            <td>
                                <input type="hidden" name="id_header" class="form-control" value="<?= $a->id ?>">
                                <input type="hidden" name="id_detail[]" class="form-control" value="<?= $a->id_detail ?>">
                                <input type="number" id="<?= 'produk' . $no ?>" name="jml_karton[]" class="form-control quantity" data-x="<?= $a->moq ?>" value="<?= $a->jml_karton ?>" onchange="quantity(<?= $no; ?>)">
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('spk/delete_keranjang/' . $a->signature_detail) ?>" class="btn-submit-black" onclick="return confirm('Are you sure?')" style="background-color: #D20062"><i class="fa-solid fa-trash-can" style="color: white"></i></a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    endforeach;
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
        <a href="<?= base_url('spk#form_spk') ?>" class="btn btn-submit-black" style="width: 50%">Kembali</a>
        <input type="submit" value="Simpan data & Lanjut ke Verifikasi Data" class="btn btn-submit-orange" style="width: 50%">
    </div>
</div>

<?= form_close(); ?>

<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // scrollX: true
        });

        // // script untuk munculkan min order
        // var produk = document.getElementsByClassName('quantity');
        // for (let index = 1; index <= Object.keys(produk).length; index++) {
        //     var element = document.getElementById('produk' + index);
        //     let produk_value = parseInt(document.getElementById('produk' + index).value);
        //     let produk_min = parseInt(element.dataset.x);
        //     if (produk_value < produk_min) {
        //         $("input#produk" + index).before('<b id="note' + index + '"  style="color:red;">Min Order : ' + produk_min + '</b>');
        //     } else {
        //         $("#note" + index).remove()
        //     }
        // }
        // // end script untuk munculkan min order

    });
</script>

<!-- <script>
    function quantity(params) {
        var element = document.getElementById('produk' + params);
        let produk_value = parseInt(document.getElementById('produk' + params).value);
        let produk_min = parseInt(element.dataset.x);
        if (produk_value < produk_min) {
            $("#note" + params).remove()
            $("input#produk" + params).before('<b id="note' + params + '" style="color: red;">Min Order : ' + produk_min + '</b>');
        } else if (produk_value >= produk_min) {
            $("#note" + params).remove()
        }
    }
</script> -->

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>