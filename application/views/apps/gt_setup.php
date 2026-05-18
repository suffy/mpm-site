<!-- Menambahkan CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->

<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>

<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
        <form action="<?= $url ?>" method="post">

            <div class="row mt-5">
                <div class="col-md-2">
                    <label for="nama_pasar">Nama Pasar</label> 
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nama_pasar" id="nama_pasar">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">
                    <label for="region">Region</label> 
                </div>
                <div class="col-md-4">
                    
                    <select name="region" id="region" class="form-control" selected>
                        <option value="" class="form-control"> -- Pilih Region --</option>
                        <?php foreach ($get_region->result() as $a) { ?>
                            <option value="<?= $a->region ?>"><?= $a->region ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">
                    <label for="provinsi">Provinsi</label> 
                </div>
                <div class="col-md-4">
                    <select id="provinsi" name="provinsi" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">
                    <label for="kabupaten">Kabupaten/kota</label> 
                </div>
                <div class="col-md-4">
                    <select id="kabupaten" name="kabupaten" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">
                    <label for="site">Site</label> 
                </div>
                <div class="col-md-4">
                    <select name="site" id="site" class="form-control select2">
                    <option value="" class="form-control"> -- Pilih Site --</option>
                    <?php foreach ($get_site->result() as $site) { ?>
                        <option value="<?= $site->site_code ?>"><?= $site->branch_name.' - '.$site->nama_comp.' - '.$site->site_code ?></option>
                    <?php } ?>
                </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-submit-red">Simpan</button>
                </div>
            </div>

        </form>
    </div>

    <div class="card-body mt-5">
        <h5 class="card-title">Master Pasar</h5>
        <table id="tabel" class="table table-striped" style="width:100%">
            <thead>
                <tr>       
                    <th class="text-center">kode pasar</th>         
                    <th class="text-center">nama pasar</th>         
                    <th class="text-center">provinsi</th>         
                    <th class="text-center">kabupaten/kota</th>         
                    <th class="text-center" style="width: 10%">is active</th>         
                </tr>
            </thead>
            <tbody>     
                <?php foreach ($get_data->result() as $a) : ?>        
                    <tr> 
                        <td><?= $a->kode_pasar ?></td>   
                        <td><?= $a->nama_pasar ?></td>   
                        <td><?= $a->provinsi ?></td>   
                        <td><?= $a->kabupaten ?></td>   
                        <td>
                            <?php if ($a->is_active == 1) { ?>
                                <a class="badge badge-success" onclick="return confirm('Are you sure to change status?')" href="<?= base_url('apps/active_pasar/'.$a->kode_pasar.'/'.$a->is_active) ?>">Active</a> 
                            <?php } else { ?>
                                <a class="badge badge-danger" onclick="return confirm('Are you sure to change status?')" href="<?= base_url('apps/active_pasar/'.$a->kode_pasar.'/'.$a->is_active) ?>">Not Active</a> 
                            <?php } ?>
                        </td>   
                    </tr>
                <?php endforeach; ?>   
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 10,
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

<script>
    $("select[name = region]").on("change", function() 
    {    
        let region = document.getElementById('region').value;            

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('apps/master_provinsi') ?>',
            data: {
                'region': region,     
            },
            success: function(result) {
                $("select[name = provinsi]").html(result);
            }
        });
        
    });

    $("select[name = provinsi]").on("change", function() 
    {    
        let provinsi = document.getElementById('provinsi').value;            

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('apps/master_kabupaten') ?>',
            data: {
                'provinsi': provinsi,     
            },
            success: function(result) {
                $("select[name = kabupaten]").html(result);
            }
        });
        
    });

</script>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 dengan konfigurasi pencarian
        $('.select2').select2({
            placeholder: "-- Pilih Site --",
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Data tidak ditemukan";
                }
            }
        });
        
        // Opsional: Menangani event ketika opsi dipilih
        $('#site').on('select2:select', function (e) {
            var data = e.params.data;
            console.log('Site terpilih:', data);
            // Tambahkan logika lain yang diperlukan di sini
        });
    });
</script>

<script>
    function is_active(kode_pasar) {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('apps/is_active') ?>',
            data: {
                'kode_pasar': kode_pasar,     
            },
            success: function(result) {
                location.reload();
            }
        });
    }
</script>