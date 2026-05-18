</div>

<div class="container-fluid">

<div class="az-content">
    <div class="container-fluid">

        <?php 
            if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul' ) { ?>
                <?= $this->load->view('spk/component/sidebar_admin');?>
            <?php
            }else{ ?>
                <?= $this->load->view('spk/component/sidebar');?>
            <?php
            }
        ?>    

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

            <?php echo form_open($url); ?>

            <div class="row mt-1">
                <div class="col-md-2">
                    <label for="supp" class="form-label">Tahun</label> 
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="tahun" value="" id="datepicker" required>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-9">  
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()" style="width: 200px;">Submit</button>
                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>

            <div class="row mt-5">
                <div class="col-md-12">
                    <table id="tabel-data" class="display" style="display: inline-block; overflow-y: scroll; width: 100%">
                        <thead>
                            <tr>
                                <th width="1%">No</th>             
                                <th>Username</th>             
                                <th>CreatedAt</th>             
                                <th>finishedAt</th>             
                                <th>CreatedBy</th>             
                            </tr>
                        </thead>
                        <tbody>     
                            <?php
                            $no = 1;
                            foreach ($get_data->result() as $a) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $a->username ?></td>
                                <td><?= $a->created_at ?></td>
                                <td><?= $a->finished_at ?></td>
                                <td><?= $a->created_by ?></td>
                            </tr>
                            <?php endforeach; ?>   
                        </tbody>
                    </table>

                </div>
            </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-data').DataTable({
            "pageLength": 1000,
            "ordering": true,
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

<script>
    function button()
    {
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show();
    }
</script>

<!-- fungsi untuk menampilkan format tanggal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
    rel="stylesheet" />
<script>
    $(document).ready(function () {
        $('#datepicker').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });
    });
</script>