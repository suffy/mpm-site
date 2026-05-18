<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">

        <?= $this->load->view('penta/component/sidebar');?>

<div class="az-content-body pd-lg-l-40 d-flex flex-column">
    <h2 class="az-content-title" id="form_spk"><?= $title; ?></h2>
    <div class="row">
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

<div class="row">
  <div class="container">
        <div class="code-block">
            <pre>Information !

Secara Otomatis, website akan mengupdate data penta di jam berikut :
06.00, 12.00, 15.00, dan 21.00 WIB
            </pre>
        </div>
</div>


<div class="card-block mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel" class="datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Closing</th>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Total Qty</th>
                        <th>Total Value</th>
                        <th>Created_at</th>
                        <th>Created_by</th>
                        <th>Token</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <?= ($p->flag_closing == 1) ? '<span class="badge badge-success">Closing</span>' : 'False' ?>
                        </td>
                        <td><?= $p->tahun ?></td>
                        <td><?= $p->bulan ?></td>
                        <td><?= number_format($p->total_qty,0) ?></td>
                        <td><?= number_format($p->total_value,0) ?></td>
                        <td><?= $p->created_at ?></td>
                        <td><?= $p->username ?></td>
                        <td><?= substr($p->token,0,10).'...' ?></td>
                        <td>
                            <a href="<?= base_url().'penta/export_stock/'.$p->signature ?>" class="pastel-btn pastel-mint">Download</a>
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
        $('#tabel').DataTable({
            "pageLength": 1000,
            "ordering": false,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : false,
            "bPaginate": false
        });
    });

   
</script>
