</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 az-content-label text-center">
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

    <div class="row mt-5">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th>No Pengajuan</th>
                        <th>Status Retur</th>
                        <th>Status Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get_retur_log_email->result() as $a) : ?>
                    <tr>
                        <td><?= $a->no_pengajuan ?></td>
                        <td><?= $a->nama_status ?></td>
                        <td style="text-align: center;">
                            <?php if($a->status_email == '9'){ ?>
                            <a href="<?= base_url('management_inventory/retur_log_proses/'.$a->id_pengajuan) ?>"
                                class="btn btn-danger btn-rounded btn-sm"><?= $a->nama_status_email ?></a>
                            <?php }else{ ?>
                            <a href="#" class="btn btn-success btn-rounded btn-sm"><?= $a->nama_status_email ?></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
            "fixedHeader": {
                header: true,
                footer: true
            },
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