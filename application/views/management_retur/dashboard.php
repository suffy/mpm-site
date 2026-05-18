<style>
    td {
        font-size: 11px;
    }

    th {
        font-size: 12px;
    }

    .accordion {
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }
</style>

</div>


<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="accordion" id="accordionOne">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                <font color="black">Import Nota Retur </font> <a
                                    href="<?= base_url().'management_retur/export_template' ?>"
                                    class="btn btn-warning btn-sm rounded-pill">download template terlebih dahulu</a>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionOne"
                        style="width:100%; overflow:hidden;">
                        <div class="card-body">

                            <?= form_open_multipart($url_import); ?>
                            <div class="container">

                                <div class="row mt-2">
                                    <div class="col-md-2">
                                        <label for="file_import" class="form-label">Principal</label>
                                    </div>
                                    <div class="col-md-4">
                                        <select id="supp" name="supp" class="form-control" required>
                                            <option value=""> -- pilih principal -- </option>
                                            <option value="001"> Deltomed</option>
                                            <option value="002"> Marguna </option>
                                            <option value="005"> Ultra Sakti </option>
                                            <option value="012"> Intrafood </option>
                                            <option value="013"> Strive </option>
                                            <option value="015"> MDJ </option>
                                            <option value="025"> GDP - Good Pharma Dermatology </option>
                                            <option value="026"> GSS </option>
                                            <option value="027"> PT. DUALIMA INDUSTRIES </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-2">
                                        <label for="branch" class="form-label">Branch</label>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="branch" id="branch" class="form-control" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-2">
                                        <label for="file_import" class="form-label">File Import</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control" name="file">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-2">

                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info">Import</button>
                                    </div>
                                </div>

                            </div>
                            <?= form_close();?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-5">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>
    </div>
</div>

<?= form_open($url); ?>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-3">
            <div class="col-md-2">
                <label for="branch">Customer / Branch</label>
            </div>
            <div class="col-md-5">
                <select name="branch" id="branch" class="form-control" required>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-2">
            <div class="col-md-2">
                <label for="branch">&nbsp;</label>
            </div>
            <div class="col-md-8">
                <button type="submit" class="btn btn-info">cari nota retur</button>
            </div>
        </div>
    </div>
</div>

<?= form_close();?>

<?= form_open($url_export); ?>
<hr>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-2">
            <div class="col-md-2">
                <label for="branch">Tglbuat</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="userid" value="<?= $userid ?>">
                        <!-- <input type="month" class="form-control d-inline" name="bulan" required> -->
                        <label for="from" class="form-label">From</label>
                        <input type="date" class="form-control" name="from" id="from" required>
                    </div>
                    <div class="col-md-6">
                        <label for="to" class="form-label">To</label>
                        <input type="date" class="form-control" name="to" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="branch">Principal</label>
            </div>
            <div class="col-md-6">
                <select name="supp" class="form-control mt-2" id="supp2">
                    <option value=""> -- Pilih Principal -- </option>
                    <option value="001"> Deltomed </option>
                    <option value="002"> Marguna </option>
                    <option value="005"> Ultra Sakti </option>
                    <option value="012"> Intrafood </option>
                    <option value="013"> Strive </option>
                    <option value="015"> MDJ </option>
                    <option value="023"> UICCP (Kojiesan) </option>
                    <option value="025"> GDP - Good Pharma Dermatology </option>
                    <option value="027"> PT. DUALIMA INDUSTRIES </option>
                </select>
                <input type="submit" class="btn btn-warning d-inline mt-2" value="Export data on the table" id="export">
                <input type="submit" class="btn btn-secondary d-inline mt-2" value="update_ref" name="update"
                    id="update_ref">
                <input type="submit" class="btn btn-secondary d-inline mt-2" value="update_nodo_beli (nota retur)"
                    name="update" id="update_nodo">
                <!-- <a href="<?= base_url().'management_retur/update_ref/' ?>"></a> -->
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-8">
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

    </div>
</div>

<?= form_close();?>

<?= form_open_multipart($url_coretax); ?>
<hr>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 az-content-label">
                Update Coretax
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row mt-2">
            <div class="col-md">
                <label for="branch">Import Coretax</label>
                <a href="<?= base_url().'management_retur/export_template_coretax' ?>"class="btn btn-warning btn-sm rounded-pill">download template terlebih dahulu</a>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-2">
                <label for="branch">File Import</label>
            </div>
            <div class="col-md-5">
                <input type="file" class="form-control d-inline" name="file_coretax" required>
                <button type="submit" class="btn btn-info d-inline mt-2">Import</button>
            </div>
        </div>
    </div>

</div>
<?= form_close();?>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="card-block mt-5">
            <div class="row">
                <div class="col-md-12">

                    <table id="example" class="display" width="100%">
                        <thead>
                            <tr>
                                <th style="background-color: darkslategray;" class="text-center col-2">
                                    <font color="white">company
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">no faktur supp
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">tglbuat
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">noseri
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">noseri_beli
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">nopo
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">tgldo_beli
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">nodo
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">nodo_beli
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">no coretax
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">pdf
                                </th>
                                <th style="background-color: darkslategray;" class="text-center col-2">
                                    <font color="white">#
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_retur->result() as $a) : ?>
                            <tr>
                                <td><?= $a->company; ?></td>
                                <td><?= $a->nodo_beli; ?></td>
                                <td><?= $a->tglbuat; ?></td>
                                <td><?= $a->noseri; ?></td>
                                <td><?= $a->noseri_beli; ?></td>
                                <td><?= $a->nopo; ?></td>
                                <td><?= $a->tgldo_beli; ?></td>
                                <td><?= $a->nodo; ?></td>
                                <td><?= $a->nodo_beli; ?></td>
                                <td><?= $a->no_coretax; ?></td>
                                <td>
                                    <a href="<?= base_url().'trans/retur/print_beli/'.$a->id ?>"
                                        class="btn btn-default btn-lg" target="_blank"><i
                                            class="typcn typcn-document"></i>
                                        <font size="2px">pdf</font>
                                    </a></td>

                                <td>
                                    <a href="<?= base_url().'management_retur/detail_nota_retur/'.$a->id ?>"
                                        class="btn btn-default btn-lg" target="_blank"><i class="typcn typcn-pen"></i>
                                        <font size="2px">edit</font>
                                    </a>
                                    <a href="<?= base_url().'management_retur/delete_nota_retur/'.$a->id ?>"
                                        class="btn btn-default btn-lg" onclick="return confirm('Are you sure?')"><i
                                            class="typcn typcn-trash"></i>
                                        <font size="2px">del</font>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
        $('#supp2').prop('required', false);
        $('#update_nodo').click(function () {
            $('#supp2').prop('required', true);
        });
        $('#update_ref').click(function () {
            $('#supp2').prop('required', false);
        });
        $('#export').click(function () {
            $('#supp2').prop('required', false);
        });
    });
</script>


<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("database_afiliasi/branch");?>',
        data: '',
        success: function (hasil_branch) {
            $("select[name = branch]").html(hasil_branch);
        }
    });
</script>