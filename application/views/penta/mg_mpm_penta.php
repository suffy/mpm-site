</div>
</div>
</div>
</div>

    <div class="container-fluid">

    <h2 id="form_spk"><?= $title; ?></h2>

    <div class="row mt-5">
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

        </div>
        </div>
        </div>
        </div>

        
    <div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <a href="<?= base_url().'master_data/post_to_penta' ?>" class="btn btn-success" target="_blank">POST TO PENTA</a>
        </div>
    </div>

<div class="card-block mb-5 mt-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel">
                <thead>
                    <tr>
                        <th>Response dari Penta</th>
                        <th>No SO</th>
                        <th>Tgl Order</th>
                        <th>Id Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Kode Cabang</th>
                        <th>Top</th>
                        <th>Id Salesman</th>
                        <th>Created At</th>
                        <th>Update At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $p->response ? $p->response : 'NULL' ?></td>
                        <td><?= $p->no_so ?></td>
                        <td><?= $p->tgl_order ?></td>
                        <td><?= $p->id_pelanggan ?></td>
                        <td><?= $p->nama_pelanggan ?></td>
                        <td><?= $p->kode_cabang ?></td>
                        <td><?= $p->top ?></td>
                        <td><?= $p->id_salesman ?></td>
                        <td><?= $p->created_at ?></td>
                        <td><?= $p->updated_at ?></td>
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
            "pageLength": 50,
            "ordering": false,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : true,
            "bPaginate": true,
            // bisa di sort
            "bSort": true
        });
    });

   
</script>
