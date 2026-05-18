</div>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_dp.js"></script>

<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('management_inventory/component/sidebar'); ?>

                <div class="col">
                    <!-- master mapping area -->
                    <div class="row" id="master-mapping-area">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="az-content-title">Master Mapping Area</h2>
                                    </div>
                                </div>

                                <form action="<?= base_url($url_tambah); ?>" method="post" id="form-input-area">
                                    <div class="col-md-12">
                                        <?php
                                        if ($this->session->flashdata('pesan_gagal_master_area')) { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $this->session->flashdata('pesan_gagal_master_mapping_area'); ?>
                                            </div>
                                        <?php
                                        } elseif ($this->session->flashdata('pesan_success_master_mapping_area')) { ?>
                                            <div class="alert alert-success" role="alert">
                                                <?= $this->session->flashdata('pesan_success_master_mapping_area'); ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label for="supp" class="form-label">Supplier</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select name="supp[]" class="form-control supp" multiple="multiple" required>
                                                <option value=""> -- Pilih Principal -- </option>
                                                <option value="001-GT"> Deltomed - GT </option>
                                                <option value="001-GT-PHARMA"> Deltomed - GT - PHARMA </option>
                                                <option value="001-MTI"> Deltomed - MTI </option>
                                                <option value="001-NKA"> Deltomed - NKA </option>
                                                <option value="001-herbana"> Deltomed - Herbana Herbamojo </option>
                                                <option value="001-RTD"> Deltomed - RTD </option>
                                                <!-- <option value="002"> Marguna </option> -->
                                                <option value="004"> Jaya Agung Makmur </option>
                                                <option value="005"> Ultra Sakti </option>
                                                <option value="012"> Intrafood </option>
                                                <option value="013"> Strive </option>
                                                <option value="015"> MDJ </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label for="user" class="form-label">PIC</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select id="user" name="user" class="form-control" required>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label for="" class="form-label">Status</label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" value=""
                                                    id="status1">
                                                <label class="form-check-label" for="status1">
                                                    Principal Area
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" value="1"
                                                    id="status2" checked>
                                                <label class="form-check-label" for="status2">
                                                    Principal HO
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <pre>(* Jika status pic adalah pabrik, silahkan hubungi IT)</pre>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label for="area" class="form-label">Area</label>
                                    </div>

                                    <hr>

                                    <div class="row mt-3">
                                        <div class="col-4 mb-3">
                                            <button type="button" class="btn btn-submit-black" id="all" value="all" onclick="checkbox_all()">All</button>
                                            <button type="button" class="btn btn-submit-black" id="mpi" value="mpi" onclick="checkbox_mpi()">MPI</button>
                                            <button type="button" class="btn btn-submit-black" id="penta" value="penta" onclick="checkbox_penta()">PENTA</button>
                                        </div>

                                        <div class="col-sm-12 mt-3">
                                            <div class="form-group row">
                                                <?php foreach ($area as $key) { ?>
                                                    <div class="col-sm-4" id="test">
                                                        <input type="checkbox" class="checkbox <?= $key['raw_region']; ?> " id="<?= $key['site_code']; ?>" name="options[]" value="<?= $key['site_code']; ?>">
                                                        <label for="<?= $key['site_code']; ?>">
                                                            <?= $key['nama_comp'] . ' - (' . $key['site_code'] . ') '; ?>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mb-5" style="text-align: center;">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-submit-black"
                                                onclick="return button()">Submit Master Mapping Area</button>
                                            <button class="btn btn-loading" id="btnLoadingMasterArea" type="button"
                                                disabled>
                                                ... Please wait ...
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <hr>

                            <div class="row" id="table-master-mapping-area">
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="button" class="mb-3 btn btn-success"
                                            onclick="convertTableMappingArea()">Export Table</button>
                                        <div class="table-responsive">
                                            <table id="table-mapping-area">
                                                <thead>
                                                    <tr>
                                                        <th>Supplier</th>
                                                        <th>PIC</th>
                                                        <th>PIC Status</th>
                                                        <th>Area</th>
                                                        <th>Site Code</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($get_master_mapping_area->result() as $key) : ?>
                                                        <tr>
                                                            <td><?= $key->supp ?></td>
                                                            <td style="text-transform: capitalize;"><?= $key->username ?>
                                                            </td>
                                                            <td style="text-transform: capitalize;"><?= $key->status ?></td>
                                                            <td><?= $key->nama_comp ?></td>
                                                            <td><?= $key->site_code ?></td>
                                                            <td><a href="<?= base_url('management_inventory/master_mapping_area_delete/' . md5($key->id)); ?>"
                                                                    class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Are you sure?')">Delete</a>
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
                    </div>
                </div>
                <!-- end master mapping area -->
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
        $("#btnLoadingMasterArea").hide();
        $('#table-mapping-area').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [1, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "info": false,
        });
    });
</script>

<!-- fungsi ajax select user dan area-->
<script>
    $.ajax({
        type: 'POST',
        url: "<?= base_url('management_inventory/master_user_mpm'); ?>",
        data: '',
        success: function(result) {
            $("select[name = user]").html(result);
        }
    });
</script>
<!-- end fungsi ajax select user dan area -->

<!-- fungsi js select supp -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $(".supp").select2({
            placeholder: "-- Silahkan Pilih --"
        });
    });
</script>
<!-- end fungsi js select supp -->

<!-- fungsi export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
    const convertTableMappingArea = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-mapping-area"));
        XLSX.writeFile(convertedTable, "<?= $title . '_Mapping_Area' ?>.xlsx");
    }
</script>
<!-- end fungsi export -->