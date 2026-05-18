</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 text-center">
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

    <div class="card mb-3">
        <div class="card-body">
            
            <?= form_open_multipart($url,  ['method' => 'get'])?> 
                <div class="row mt-3">
                    <div class="col-md-7" id="divform1">
                        <div class="row mt-1" id="divform_periode">
                            <div class="col-lg-4">
                                <label for="from">Periode</label> 
                            </div>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from');?>" required>
                                    <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to');?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1" id="divform_channel">
                            <div class="col-lg-4">
                                <label for="channel">Channel</label>
                            </div>
                            <div class="col-lg-8">
                                    <select name="channel" id="channel" class="form-select">
                                        <option value="">-- Pilih Channel --</option>
                                        <option value="nka">NKA</option>
                                        <option value="pharma">PHARMA</option>
                                    </select>
                            </div>
                        </div>

                        <div class="row mt-1" id="divform_kategori">
                            <div class="col-lg-4">
                                <label for="kategori">Kategori</label>
                            </div>
                            <div class="col-lg-8">
                                <Select class="form-select" name="kategori" id="kategori">
                                <option value=""> -- Pilih Kategori -- </option>
                                </Select>
                            </div>
                        </div>
        
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label></label> 
                    </div>
                    <div class="col-lg-9">
                        <button type="submit" value="search" name="submit" class="btn btn-submit-red" style="height: 45px;">Search</button>
                        <a href="<?= base_url($url);?>" class="btn btn-submit-black" style="height: 45px;">Reset View</a>
                        <button type="submit" value="export" name="submit" class="btn btn-submit-black" style="height: 45px;">Export</button>
                    </div>
                </div>
            <?= form_close(); ?>
            
            <hr>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-submit-orange" onclick="convertTable()" style="border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Convert data below to Excel</button>
                </div>
            </div>

            <div class="card-block mt-3 mb-5">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabel-ajuan-claim">
                            <thead>
                                <tr>
                                    <th class="text-center">No Ajuan</th>
                                    <th class="text-center">No Klaim</th>
                                    <th class="text-center">No Invoice</th>
                                    <th class="text-center">Channel</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Key Acccount</th>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Keterangan</th> 
                                    <th class="text-center">On Duty</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>     
                                <?php foreach ($get_data->result() as $key)  {?>
                                    <tr>
                                        <td><?= $key->nomor_ajuan;?></td>
                                        <td><?= $key->nomor_klaim;?></td>
                                        <td><?= $key->nomor_invoice;?></td>
                                        <td style="text-transform: uppercase;"><?= $key->channel;?></td>
                                        <td><?= $key->kategori;?></td>
                                        <td><?= $key->channel == 'nka' ? $key->key_account : '-';?></td>
                                        <td>
                                            <?php
                                                if($key->periode_end != null){
                                                    echo date( 'd F Y', strtotime($key->periode_start)) . ' - ' . date( 'd F Y', strtotime($key->periode_end));
                                                } else {
                                                    echo date( 'F Y', strtotime($key->periode_start));
                                                }
                                            ;?>
                                        </td>
                                        <td><?= $key->keterangan;?></td>
                                        <td style="text-transform: capitalize;"><?= $key->on_duty_name;?></td>
                                        <td style="text-transform: uppercase;">
                                            <?php 
                                                if ($key->status == 1) { // PROSES PENDING KAM
                                                    $color = "btn-warning btn-sm rounded";
                                                } elseif($key->status == 2){ // PROSES PENDING MPM
                                                    $color = "btn-warning btn-sm rounded";
                                                } elseif($key->status == 3){ // PROSES REJECT KAM
                                                    $color = "btn-danger btn-sm rounded"; 
                                                } elseif($key->status == 4){ // PROSES PENDING ADMIN MPM
                                                    $color = "btn-warning btn-sm rounded";
                                                } elseif($key->status == 5){ // PROSES REJECT MPM
                                                    $color = "btn-danger btn-sm rounded";
                                                } elseif($key->status == 6){ // PROSES APPROVE ADMIN MPM
                                                    $color = "btn-success btn-sm rounded";
                                                } elseif($key->status == 7){ // PROSES REJECT ADMIN MPM
                                                    $color = "btn-danger btn-sm rounded";
                                                }                           
                                            ?>
                                    <a href='<?= base_url("$url_akses/$key->signature"); ?>'
                                        class="btn <?= $color ?> btn-sm" target="_blank"><?= $key->nama_status ?></a>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
        $('#tabel-ajuan-claim').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // scrollX: true,
        });
    });
</script>

<script>
    $("select[name = channel]").on("change", function() 
    {    
        $("#divform2").remove();
        let channel = document.getElementById('channel').value;   
        // alert(channel);         

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_kategori_nka') ?>',
            data: {
                'channel': channel,     
            },
            success: function(result) {
                $("select[name = kategori]").html(result);
            }
        });
        
    });

</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-ajuan-claim"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>