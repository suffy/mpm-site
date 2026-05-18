</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 id="form_spk"><?= $title; ?></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            if ($this->session->flashdata('pesan')) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php
            } elseif ($this->session->flashdata('pesan_success')) { ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-md-3">
            Kode Surat Jalan   
        </div>
        <div class="col-md-7">
            <input type="text" class="form-control" name="nodo" value="<?= $kode_surat_jalan ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            Nomor Do    
        </div>
        <div class="col-md-7">
            <input type="text" class="form-control" name="nodo" value="<?= $nodo ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            Tgl Do    
        </div>
        <div class="col-md-7">
            <input type="text" class="form-control" name="nodo" value="<?= $tgldo ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">company</div>
        <div class="col-md-7">
            <input type="text" class="form-control" name="nodo" value="<?= $company ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">nopo</div>
        <div class="col-md-7">
            <input type="text" class="form-control" name="nodo" value="<?= $nopo ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">tglpo</div>
        <div class="col-md-7">
            <input type="text" class="form-control" name="nodo" value="<?= $tglpo ?>" readonly>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 mt-4">
            <?php echo form_open('spk/update_surat_jalan_detail_us'); ?>
            <table id="tabel" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 10%; text-align: center">Kode Produk</th>
                        <th style="text-align: center">Nama Produk</th>
                        <th style="width: 10%; text-align: center">Unit</th>
                        <th style="width: 10%; text-align: center">Karton</th>
                        <th style="width: 10%; text-align: center">Berat</th>
                        <th style="width: 10%; text-align: center">Volume</th>
                        <th style="width: 10%; text-align: center">action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0; 
                    foreach ($get_detail->result() as $a):?>  
                    <tr>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td>
                            <input type="hidden" class="form-control" name="data[<?= $i ?>][id]" value="<?= $a->id ?>">
                            <input type="hidden" class="form-control" name="data[<?= $i ?>][kode_surat_jalan]" value="<?= $kode_surat_jalan_formatted ?>">
                            <input type="text" class="form-control" name="data[<?= $i ?>][banyak]" value="<?= $a->banyak ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="data[<?= $i ?>][total_karton]" value="<?= $a->total_karton ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="data[<?= $i ?>][total_karton_berat]" value="<?= $a->total_karton_berat ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="data[<?= $i ?>][total_karton_volume]" value="<?= $a->total_karton_volume ?>">
                        </td>
                        <td>
                            <button class="btn btn-primary" type="submit" name="update" value="<?= $a->id ?>">Update</button>
                        </td>
                    </tr>
                <?php $i++; endforeach;?>
                </tbody>
            </table>
        </div>
    </div>


</div>

<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });
</script>