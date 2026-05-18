<style>
    td:hover {
        transform: scale(1.2);
    }

    th:hover {
        transform: scale(1.2);
    }
</style>

<?= $this->load->view('spk/component/title'); ?>

<?php echo form_open($url); ?>

<div class="row mt-3">
    <div class="col-lg-2">
        <label for="from">Periode</label>
    </div>
    <div class="col-lg-4">
        <div class="input-group">
            <input type="date" name="from" id="from" class="form-control" value="<?= $from ?>" required>
            <input type="date" name="to" id="to" class="form-control" value="<?= $to ?>" required>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-2">
        <label for="from">Company</label>
    </div>
    <div class="col-lg-4">
        <select id="site_code" name="site_code" class="form-control" required>
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-2">
        <label for="limit">Limit</label>
    </div>
    <div class="col-lg-4">
        <input type="number" name="limit" class="form-control" value="<?= $limit ?>">
    </div>
</div>

<div class="row mt-3 mb-5">
    <div class="col-lg-2">
        <label for="flag_delete">Flag PO</label>
    </div>
    <div class="col-lg-4">
        <select name="flag_delete" class="form-control">
            <option value="">Active</option>
            <option value="1" <?php if ($flag_delete == 1) echo "selected"; ?>>Deleted</option>
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-2">
        <label for="supp"></label>
    </div>
    <div class="col-md-10">
        <input type="submit" value="Search PO" class="btn btn-submit-orange" style="height: 44px;">
        <button type="button" class="btn btn-submit-black" onclick="convertTable()">Export to Excel</button>
        <a href="<?= base_url('spk') ?>" class="btn btn-submit-black">Kembali</a>
    </div>
</div>

<?= form_close(); ?>

<div class="card-block mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel-data" class="table-striped">
                <thead>
                    <tr>
                        <th width="10%" height="50px">Principal</th>
                        <th width="2%">Nopo</th>
                        <th width="5%">Company</th>
                        <th width="5%">Subbranch</th>
                        <th width="5%">TglPesan</th>
                        <th width="5%">TglPo</th>
                        <th width="1%">Tipe</th>
                        <th width="5%">Total</th>
                        <th width="5%">IS_PP</th>
                        <th width="5%" class="text-center">Status</th>
                        <th width="2%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td><?= $a->namasupp ?></td>
                            <td>
                                <?php
                                if ($a->nopo) { ?>
                                    <a href="<?= base_url() . "transaction/download_pdf/" . $a->id ?>" target="_blank" class="btn btn-submit-orange" style="background-color: #578FCA"><?= $a->nopo ?></a>
                                <?php
                                } else { ?>
                                    <a href="<?= base_url() . "transaction/download_pdf/" . $a->id ?>" target="_blank" class="btn btn-submit-orange" style="background-color: #D91656">belum tersedia</a>
                                <?php
                                }
                                ?>
                            </td>
                            <td><?= $a->company ?></td>
                            <td><?= $a->nama_comp ?></td>
                            <td><?= $a->tglpesan ?></td>
                            <td><?= $a->tglpo ?></td>
                            <!-- <td><?= ($a->tipe == 'A') ? 'Alokasi' : 'SPK' ?></td> -->
                            <td>
                                <?php
                                if ($a->tipe == 'A') {
                                    echo 'Alokasi';
                                } elseif ($a->tipe == 'R') {
                                    echo 'Replenishment';
                                } else {
                                    echo 'SPK';
                                }
                                ?>
                            </td>
                            <td><?= 'Rp. ' . number_format($a->total_value, 0) ?></td>
                            <td>
                                <?php 
                                    if ($a->is_pp_approval == '1'){
                                        echo 'True';
                                    } else if ($a->is_pp_approval == '0'){
                                        echo 'False';
                                    } else {
                                        echo '';
                                    }?></td>
                            <td class="text-center">
                                <?php
                                if ($a->status == '1') {
                                    $nama_status = "pending finance";
                                    $style = "font-size:14px";
                                    $class = "pending-finance";
                                } elseif ($a->status == '2') {
                                    if ($a->open == '1') {
                                        if ($a->nopo == null) {
                                            $nama_status = "pending rilis po";
                                            $style = "font-size:14px";
                                            $class = "pending-rilis-po";
                                        } else {
                                            $nama_status = "finish";
                                            $style = "font-size:14px";
                                            $class = "finish";
                                        }
                                    } else {
                                        $nama_status = "pending finance";
                                        $style = "font-size:14px";
                                        $class = "pending-finance";
                                    }
                                } else {
                                    $nama_status = "pending scm";
                                    $style = "font-size:14px";
                                    $class = "pending-scm";
                                }
                                ?>
                                <a href="<?= base_url() ?>spk/list_order_detail/<?= $a->signature ?>" class="btn btn-submit status <?= $class ?>" target="_blank" style="<?= $style ?>"><?= $nama_status ?></a>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <!-- <a href="<?= base_url('spk/delete_po/' . $a->signature . '/' . $a->tahun) ?>" class="btn-submit-black" onclick="return confirm('Hapus data ini ?')" style="background-color: #fbe7e8"><i class="fa-solid fa-trash-can" style="color: #d11a2a;"></i></a> -->

                                    <a href="<?= base_url('spk/delete_po/' . $a->signature . '/' . $a->tahun) ?>" onclick="return confirm('Ingin menghapus data ini ?')" class="delete-button" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?= form_close(); ?>

<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            "pageLength": 10,
            "ordering": false,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("spk/master_sitecode") ?>',
        data: '',
        success: function(result) {
            $("select[name = site_code]").html(result);
        }
    });
</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>