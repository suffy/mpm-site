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

            <?php echo form_open_multipart($url); ?>
            <!-- <form action="<?= $url_search_produk ?>"> -->

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">Cycle</label> 
                </div>
                <div class="col-md-4">
                    <select name="cycle" class="form-control" required>
                        <option value=""> -- Pilih Cycle -- </option>
                       
                        <option value="6"> 6 Bulan kebelakang</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Submit Generate</button>
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
                                <th colspan="4"></th>
                                <th colspan="2" class="text-center">Average</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th width="1%">No</th>               
                                <th width="10%">Branch</th>              
                                <th width="10%">Kodeprod</th>              
                                <th width="10%">Namaprod</th>              
                                <th width="10%">Unit</th>              
                                <th width="10%">Karton</th>              
                                <th width="10%">createdAt</th>              
                            </tr>
                        </thead>
                        <tbody>     
                            <?php
                            $no = 1;
                            foreach ($get_data->result() as $a) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $a->nama_comp.' - '.$a->site_code ?></td>
                                <td><?= $a->kodeprod ?></td>
                                <td><?= $a->namaprod ?></td>
                                <td align="right"><?= $a->average_unit ?></td>
                                <td align="right"><?= $a->average_karton ?></td>
                                <td><?= $a->created_at ?></td>
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
        $("#btnLoading").hide();
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-data').DataTable({
            "pageLength": 100,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
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