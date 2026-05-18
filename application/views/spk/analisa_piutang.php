<div class="az-content">
    
    <div class="container-fluid">
    
        <?php echo form_open($url); ?>
        
        <div class="row">
            <div class="col-md-12">
                <h2 id="form_spk"><?= $title; ?></h2>
            </div>
            <div class="col-md-12 mt-3">
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

            <div class="col-md-4">
                <label for="tanggal">Cut Off</label>
            </div>
            <div class="col-md-7">
                <div class="input-group">
                    <input type="date" name="tanggal" id="tanggal" min="2025-01-01" class="form-control" value="<?= $this->input->get('tanggal') ?>" required>
                </div>
            </div>

            <br>

            <div class="col-md-4">
            </div>
            <div class="col-md-7 mt-4">
                <button class="pastel-orange-btn" id="btnKirim">Retrieve Data</button>
            </div>
        </div>

        <?php echo form_close(); ?>

    </div>
</div>

<hr>


<div class="container-fluid">
    <?php if (!empty($get_data)): ?>
        <div class="card-block mt-1 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <a href="<?= base_url($url_export); ?>" class="btn btn-success" role="button">Export to Excel</a>
                    <a href="<?= base_url($url_export_detail); ?>" class="btn btn-primary" role="button">Export detail to Excel</a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 table-responsive">
                    <table id="tabel" class="table-striped" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">Company</th>              
                                <th class="text-center">CustomerId</th> 
                                <th class="text-center">1-7</th>              
                                <th class="text-center">8-15</th>              
                                <th class="text-center">16-30</th>              
                                <th class="text-center">31-45</th>              
                                <th class="text-center">46-60</th>              
                                <th class="text-center">>60</th>              
                                <th class="text-center">Total</th>              
                            </tr>
                        </thead>
                        <tbody>     
                            <?php
                                foreach ($get_data->result() as $a) : ?>
                                    <tr>
                                        <td><?= $a->group_descr ?></td>   
                                        <td><?= $a->customerid ?></td>          
                                        <td><?= number_format($a->a) ?></td>   
                                        <td><?= number_format($a->b) ?></td>   
                                        <td><?= number_format($a->c) ?></td>   
                                        <td><?= number_format($a->d) ?></td>   
                                        <td><?= number_format($a->e) ?></td>   
                                        <td><?= number_format($a->f) ?></td>   
                                        <td><?= number_format($a->total) ?></td>   
                                    </tr>
                            <?php endforeach; ?>  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php elseif (isset($tanggal)): ?>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function () {         
        $('#tabel').DataTable({
            "pageLength": 200,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });
</script>