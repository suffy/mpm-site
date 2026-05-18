
</div>
<div class="container-fluid">

    <!-- seesion flash -->
    <div class="row">
        <div class="col-md-12 text-center">
            <?php 
                if($this->session->flashdata('pesan')){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('pesan'); ?>
                    </div>
                <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('pesan_success'); ?>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>
    <!-- end seesion flash -->

    <div class="card">

        <h5 class="card-title"><?= $title ?></h5>
        <!-- form input -->
        <?php echo form_open_multipart($url); ?>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="from">Bulan</label>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <input type="month" class="form-control" id="month" name="month" value="<?= date('Y-m') ?>" required>
                </div>
            </div>
        </div>
        
        <!-- <?php if ($userid == 749)
        { ?>
            <div class="row mt-3">
                <div class="col-lg-2">
                    <label for="from">Company</label>
                </div>
                <div class="col-lg-4">
                    <select id="site_code" name="site_code" class="form-control select2" required>
                    </select>
                </div>
            </div>
        <?php } ?> -->

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="from">File Import</label>
            </div>
            <div class="col-lg-4">
                <input type="file" class="form-control" name="file" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="supp"></label>
            </div>
            <div class="col-md-10">
                <input type="hidden" name="sitecode" value="<?= $sitecode ?>">
                <input type="submit" value="Import Stock" class="btn btn-submit-orange" style="height: 44px;">
                <a href="<?= base_url('management_stock/download_template_stock') ?>" class="btn btn-submit-black">Download Template Stock</a>
            </div>
        </div>

        

        <?= form_close(); ?>
        <!-- end form input -->
    </div>

    <div class="card mt-2">
        <h5 class="card-title" style="text-align: center;"><?= $title2 ?></h5>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-data-history" class="tabel-data-history table-striped table-hover" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>        
                            <th class="text-center">SiteCode</th>
                            <th class="text-center">SubBranch</th>       
                            <th class="text-center">Bulan</th>       
                            <th class="text-center">Filename</th>     
                            <th class="text-center">CreatedAt</th>       
                        </tr>
                    </thead>
                    <tbody>  
                        <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : 
                        ?>  
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center"><?= $a->site_code ?></td> 
                                <td class="text-center"><?= $a->nama_comp ?></td>
                                <td class="text-center"><?= $a->bulan ?></td> 
                                <td class="text-center"><?= $a->filename ?></td>
                                <td class="text-center"><?= $a->created_at ?></td> 
                            </tr>
                        <?php endforeach; ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Tema Bootstrap 5 untuk Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</body>
</html>

<script>
    $(document).ready(function () {
        // 1. Inisialisasi DataTable
        $('#tabel-data-history').DataTable({
            pageLength: 10,
            ordering: true,
            order: [[0, 'asc']],
            aLengthMenu: [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });

        // 2. AJAX untuk ambil data site_code
        $.ajax({
            type: 'POST',
            url: '<?= base_url("bridging/master_sitecode") ?>',
            success: function (result) {
                $('#site_code').select2({
                    theme: 'bootstrap-5',
                    // placeholder: '-- Pilih Company --',
                    width: '100%',
                    minimumResultsForSearch: 15, 
                });
            },
            error: function () {
                alert('Gagal mengambil data site code.');
            }
        });
    });
</script>


<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("bridging/master_sitecode") ?>',
        data: '',
        success: function(result) {
            $("select[name = site_code]").html(result);
        }
    });
</script>

