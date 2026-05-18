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
                <div class="col-md-12 mt-4">  
                    <table id="tabel-data">
                        <thead>
                            <tr>   
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="white"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th width="10%">KodeProduk</th> 
                                <th width="20%">NamaProduk</th> 
                                <th width="10%">IsiSatuan</th> 
                                <th width="10%">Qty1</th> 
                                <th width="10%">Qty2</th> 
                                <th width="10%">Qty3</th> 
                        </thead>
                        <tbody>  
                            <?php
                            foreach ($get_data->result() as $a) : ?>
                            <tr>
                                <td>
                                    <center>
                                    <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id; ?>">       
                                    </center>
                                </td> 
                                <td><?= $a->kodeprod ?></td> 
                                <td>
                                    <input type="text" name="namaprod[<?= $a->id ?>]" class="form-control" value="<?= $a->namaprod ?>">
                                </td> 
                                <td>
                                    <input type="number" name="isisatuan[<?= $a->id ?>]" class="form-control" value="<?= $a->isisatuan ?>">
                                </td> 
                                <td>
                                    <input type="number" name="qty1[<?= $a->id ?>]" class="form-control" value="<?= $a->qty1 ?>">
                                </td> 
                                <td>
                                    <input type="number" name="qty2[<?= $a->id ?>]" class="form-control" value="<?= $a->qty2 ?>">
                                </td> 
                                <td>
                                    <input type="number" name="qty3[<?= $a->id ?>]" class="form-control" value="<?= $a->qty3 ?>">
                                </td> 
                            
                            </tr>
                            <?php endforeach; ?>   
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="row mb-5 mt-3">
                <div class="col-lg-12 d-flex justify-content-center btn-group">
                    <input type="submit" value="Submit Data" class="btn btn-submit-orange" style="width: 100%;">
                </div>
            </div>
            <?php echo form_close(); ?>

    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-data').DataTable({
            "pageLength": 25,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : true,
            "bPaginate": true
        });
    });
</script>