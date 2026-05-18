<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><?= $title ?></h3>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table-bordered" id="table-ai" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Agent</th>
                                            <th>Deskripsi</th>
                                            <th>Tipe</th>
                                            <th>Active</th>
                                            <th>CreatedAt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $no = 1; 
                                        foreach($list_agent->result() as $agent) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                
                                                <a href="<?= base_url() ?>ai/detail_agent/<?= $agent->signature ?>"><?= $agent->nama_agent ?></a>
                                            </td>
                                            <td><?= $agent->deskripsi ?></td>
                                            <td><?= $agent->tipe ?></td>
                                            <td><?= $agent->is_active ?></td>
                                            <td><?= $agent->created_at ?></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#table-ai').DataTable({
            "pageLength": 10,
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