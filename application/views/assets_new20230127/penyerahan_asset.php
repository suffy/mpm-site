<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    table th,
    table td {
        text-transform: Capitalize;
        white-space: normal !important;
    }
</style>

<a href="#" class="btn btn-primary" role="button" onclick="input_penyerahan_asset()"><span
        class="glyphicon glyphicon-plus" aria-hidden="true"></span>Tambah</a>
<hr>
<div class="dt-responsive table-responsive">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th>No. POF</th>
                <th>No. PR</th>
                <th>Product</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data_purchase_asset as $a) : ?>
            <tr>
                <td><a href="#" onclick="Detail('<?= $a->no_po;?>')"><?= $a->no_po;?></a></td>
                <td><a href="#" onclick="Detail_pr('<?= $a->no_pr;?>')"><?= $a->no_pr;?></a></td>
                <td><?= $a->nama_barang.' '.$a->tipe; ?></td>
                <td>
                    <a href="<?= base_url("assets_new/detail_mutasi/$a->no_po");?>" class="btn btn-warning btn-sm">Mutasi</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>No. PO</th>
                <th>No. PR</th>
                <th>Product</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<?=
$this->load->view('assets_new/modal_input_penyerahan_asset');
$this->load->view('assets_new/modal_detail_purchase_asset');
$this->load->view('purchase_requistion/modal_detail');
?>

<script>
    function input_penyerahan_asset() {
        $("a.detail").hide();
        $("input#input_no_po").val('');
        $("input#no_po").val('');
        $("input#no_pr").val('');
        $("input#tanggal").val('').attr('readonly', false);
        $('#input_penyerahan_asset').modal();
        $("input#input_no_po").change(function () {
            var no_po = document.getElementById("input_no_po").value;
            if (no_po == '0') {
                $("a.detail").hide();
            } else {
                $("a.detail").attr('onclick', 'Detail("' + no_po + '")').show();
            }
            $.ajax({
                type: "GET",
                url: "<?= base_url().'assets_new/get_data';?>",
                data: {
                    id: no_po
                },
                dataType: "json",
                success: function (response) {
                    $("input#no_po").val(response.get_pr[0].no_po+ '-' + response.get_pr[0].id_barang)
                    $("input#no_pr").val(response.get_pr[0].no_pr)
                        .change()
                }
            });
        });
    }

    function Detail(param) {
        $('#detail_purchase_asset').modal();
        $('div#barang').remove();
        $.ajax({
            type: "GET",
            url: "<?= base_url().'assets_new/get_data';?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                const d = new Date(response.get_pr[0].tgl_po);
                const tahun = d.getFullYear();
                const bulan = d.getMonth();
                const tgl = d.getDate();
                const tanggal = tahun + '-' + bulan + '-' + tgl;
                $('input#no_po').val(response.get_pr[0].no_po).attr('readonly', true);
                $('input#no_pr').val(response.get_pr[0].no_pr).attr('readonly', true);
                $('input#username_request').val(response.get_pr[0].username).attr('readonly', true);
                $('input#tanggal').val(response.get_pr[0].tgl_po).attr('readonly', true);
                $('input#nama_toko').val(response.get_pr[0].nama_toko).attr('readonly', true);
                $('textarea#alamat').val(response.get_pr[0].alamat).attr('readonly', true);
                $('input#no_telp').val(response.get_pr[0].telp).attr('readonly', true);
                $('input#fax').val(response.get_pr[0].fax).attr('readonly', true);
                $('input#attn').val(response.get_pr[0].attn).attr('readonly', true);

                for (let index = 0; index < response.get_pr.length; index++) {
                    var i = (+index + 1);
                    $('div.barang').append('<div id="barang"><label for="barang">Barang ' + i +
                        '</label><input type="text" name="barang[]" id="barang' + index +
                        '" class="form-control"></div>');
                    $('input#barang' + index).val(response.get_pr[index].nama_barang + ' ' + response
                        .get_pr[index].tipe).attr("readonly", true);
                }
            }
        });
    }

    function Detail_pr(param) {
        $('#detail').modal();
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                const d = new Date(response.pr[0].created_at);
                const tahun = d.getFullYear();
                const bulan = d.getMonth();
                const tgl = d.getDate();
                const tanggal = tahun + '-' + bulan + '-' + tgl;
                $('input#no_pr').val(response.pr[0].no_pr).attr("readonly", true);
                $('input#username').val(response.pr[0].username).attr("readonly", true);
                $('input#tanggal').val(response.pr[0].created_at).attr("readonly", true);
                $('input#divisi').val(response.pr[0].divisi).attr("readonly", true);
                $('textarea#barang').val(response.pr[0].barang).attr("readonly",
                    true);
                $('textarea#spesifikasi').val(response.pr[0].spesifikasi).attr("readonly",
                    true);

                $('textarea#keterangan').val(response.pr[0].keterangan).attr("readonly", true);
                $('textarea#keterangan_atasan').val(response.pr[0].keterangan_atasan).attr("readonly",
                true);
                $('textarea#keterangan_it').val(response.pr[0].keterangan_it).attr("readonly", true);
                $('textarea#keterangan_finance').val(response.pr[0].keterangan_finance).attr("readonly",
                    true);
                $('textarea#keterangan_purchasing').val(response.pr[0].keterangan_purchasing).attr(
                    "readonly", true);
            }
        });
    }
</script>