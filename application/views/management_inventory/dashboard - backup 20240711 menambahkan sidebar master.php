</div>

</div>

<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('management_inventory/component/sidebar');?>

                <div class="col">
                    <!-- event -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" id="event">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Dashboard</h4>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
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
                            <div class="row mt-3">
                                <h5>Pilih Periode</h5>
                                <div class="form-inline row ">
                                    <form action="<?= $url_search ?>">
                                        From
                                        <input class="form-control" type="date" name="from"
                                            value="<?= $this->input->get('from') ?>" required />
                                        To
                                        <input class="form-control" type="date" name="to"
                                            value="<?= $this->input->get('to') ?>" required />
                                        <select name="status" class="form-control">
                                            <option value="0" <?= $this->input->get('status') == 0 ? 'selected' : '' ?>>
                                                All
                                                Status
                                            </option>
                                            <option value="1" <?= $this->input->get('status') == 1 ? 'selected' : '' ?>>
                                                Pending DP
                                            </option>
                                            <option value="2" <?= $this->input->get('status') == 2 ? 'selected' : '' ?>>
                                                Pending MPM
                                            </option>
                                            <option value="3" <?= $this->input->get('status') == 3 ? 'selected' : '' ?>>
                                                Pending
                                                Principal Area </option>
                                            <option value="4" <?= $this->input->get('status') == 4 ? 'selected' : '' ?>>
                                                Pending
                                                Principal HO </option>
                                            <option value="5" <?= $this->input->get('status') == 5 ? 'selected' : '' ?>>
                                                Pending Kirim
                                                Barang </option>
                                            <option value="6" <?= $this->input->get('status') == 6 ? 'selected' : '' ?>>
                                                Pending Terima
                                                Barang </option>
                                            <option value="8" <?= $this->input->get('status') == 8 ? 'selected' : '' ?>>
                                                Barang di
                                                Terima </option>
                                            <option value="7" <?= $this->input->get('status') == 7 ? 'selected' : '' ?>>
                                                Pending
                                                Pemusnahan </option>
                                            <option value="9" <?= $this->input->get('status') == 9 ? 'selected' : '' ?>>
                                                Pemusnahan
                                                Selesai </option>
                                            <option value="10"
                                                <?= $this->input->get('status') == 10 ? 'selected' : '' ?>>
                                                Reject
                                                Principal Ho </option>
                                            <option value="11"
                                                <?= $this->input->get('status') == 11 ? 'selected' : '' ?>>
                                                Retur Sample
                                            </option>
                                        </select>
                                        <button type="submit" value="1" class="btn btn-outline-danger btn-sm"
                                            name="type">Search</button>
                                        <?php if ($this->session->userdata('supp') == 005) { ?>

                                        <?php }else{ ?>
                                        <button type="submit" value="2" class="btn btn-outline-danger btn-sm"
                                            name="type">Export
                                            To
                                            CSV</button>
                                        <?php }?>
                                        <a href="<?= base_url() ?>management_inventory"
                                            class="btn btn-outline-dark btn-sm">Reset</a>
                                    </form>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="example">
                                            <thead>
                                                <tr>
                                                    <th>Tgl</th>
                                                    <th>No Retur</th>
                                                    <th>Principal</th>
                                                    <th>Tipe</th>
                                                    <th>Company</th>
                                                    <th>Site</th>
                                                    <th>Status</th>
                                                    <th>Override Status</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($get_pengajuan->result() as $a) : ?>
                                                <tr>
                                                    <td><?= $a->tanggal_pengajuan ?></td>
                                                    <td>
                                                        <a href="<?= base_url().'management_inventory/generate_pdf/'.$a->signature.'/'.$a->supp ?>"
                                                            class="btn btn-submit-black"
                                                            target="_blank"><?= ($a->no_pengajuan) ? $a->no_pengajuan : 'NULL'; ?></a>
                                                    </td>
                                                    <td><?= $a->namasupp ?></td>
                                                    <td style="text-transform: uppercase"><?= $a->tipe ?></td>
                                                    <td><?= $a->branch_name ?></td>
                                                    <td><?= $a->nama_comp ?></td>
                                                    <td>
                                                        <?php 
                                                            if ($a->status == 1) { // PROSES DP
                                                                $color = "btn-info btn-sm rounded";
                                                            }elseif($a->status == 2){ // PROSES MPM
                                                                $color = "btn-warning btn-sm rounded";
                                                            }elseif($a->status == 3){ // PROSES PRINCIPAL AREA
                                                                $color = "btn-danger btn-sm rounded"; 
                                                            }elseif($a->status == 4){ // PROSES PRINCIPAL HO
                                                                $color = "btn-danger btn-sm rounded";
                                                            }elseif($a->status == 5){ // PROSES KIRIM BARANG
                                                                $color = "btn-info btn-sm rounded";
                                                            }elseif($a->status == 6){ // PROSES TERIMA BARANG
                                                                $color = "btn-danger btn-sm rounded";
                                                            }elseif($a->status == 7){ // PROSES PEMUSNAHAN
                                                                $color = "btn-info btn-sm rounded";
                                                            }elseif($a->status == 8 || $a->status == 9){ // BARANG DITERIMA dan Pemusnahan
                                                                $color = "btn-dark btn-sm rounded";
                                                            }elseif($a->status == 10){ // REJECT PRINCIPAL HO
                                                                $color = "btn-dark btn-sm rounded";
                                                            }elseif($a->status == 13){ // REJECT
                                                                $color = "btn-dark btn-sm rounded";
                                                            }else{
                                                                $color = "btn-info btn-sm rounded";
                                                            }
                                                            
                                                        ?>
                                                        <a href="<?= base_url().'management_inventory/routing/'.$a->signature ?>"
                                                            class="btn <?= $color ?> btn-sm"
                                                            target="_blank"><?= $a->nama_status ?></a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url().'management_inventory/form_override_status/'.$a->signature ?>"
                                                            class="btn btn-submit-black" target="_blank">override</a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url().'management_inventory/delete_pengajuan/'.$a->signature ?>"
                                                            class="btn btn-submit-red" target="_blank"
                                                            onclick="return confirm('Are you sure?')"><i
                                                                class="fa fa-trash"></i></a>
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
                    <!-- end event -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // table
            // .columns(3)
            // .search(this.value)
            // .draw()
        });

        var table = new DataTable('#example');

        // #column3_search is a <input type="text"> element
        $('#column3_search').on('keyup', function () {
            table
                .columns(4)
                .search(this.value)
                .draw();
        });

    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>