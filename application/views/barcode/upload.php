<?php echo form_close(); ?> 
<?php echo form_open_multipart($url); ?>
<div class="az-content">
    <div class="container-fluid">

        <?= $this->load->view('barcode/component/sidebar');?>

<div class="az-content-body pd-lg-l-40 d-flex flex-column">
    <h2 id="form_spk"><?= $title; ?></h2>
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
                <pre><strong>Information ! 
Kami hanya menerima file yang bersumber dari menu di SDS yaitu dengan format .XML</strong></pre>
            </div>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-12">


            <div class="row mt-3">
                <div class="col-lg-3">
                    <label for="nama" >Nama</label> 
                </div>
                <div class="col-lg-5">
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
            </div>  

            <div class="row mt-3">
                <div class="col-lg-3">
                    <label for="hp" >No HP/Whatsapp (Pastikan No Telp Aktif)</label> 
                </div>
                <div class="col-lg-5">
                    <input type="text" name="hp" id="hp" class="form-control" required>
                </div>
            </div>  

            <div class="row mt-3">
                <div class="col-lg-3">
                    <label for="total" >Total Barcode</label> 
                </div>
                <div class="col-lg-5">
                    <input type="text" name="total_barcode" id="total_barcode" class="form-control" required>
                </div>
            </div> 

            <div class="row mt-5">
                <div class="container">
                    <div class="code-block text-start">
                        <pre><strong>Untuk mempercepat pengiriman dan optimalisasi budget, maka pengiriman akan di tujukan ke alamat HO. Dengan UP : BM masing-masing</strong></pre>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-3">
                    <label for="alamat_penerima" >Alamat HO Penerima Barcode</label> 
                </div>
                <div class="col-lg-5">
                    <textarea name="alamat_penerima" id="alamat_penerima" class="form-control" cols="30" rows="5" required ></textarea>
                </div>
            </div>  

            <div class="row mt-3">
                <div class="col-lg-3">
                    <label for="file" >Upload file (format .xml / zip)</label> 
                </div>
                <div class="col-lg-5">
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>
            </div>
            

            <div class="row mt-5">
                <div class="col-lg-3">

                </div>
                <div class="col-lg-5">
                    <button type="submit" class="pastel-btn pastel-mint" id="btnKirim" onclick="return button()">Ajukan Request</button>
                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                    ... Please wait ...
                    </button>                    
                </div>
            </div>           
        </div>
    </div>

        </div>
        </div>
    


<?php echo form_close(); ?> 
<div class="card-block mt-5 mb-5">
    <div class="row ms-2">
        <div class="col-md-12">
            <table id="tabel" class="datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>HP</th>
                        <th>Alamat Penerima</th>
                        <th>File</th>
                        <th>Total Barcode</th>
                        <th>Estimasi Penyelesaian (Jam)</th>
                        <th>Status</th>
                        <th>CreatedAt</th>
                        <th>CreatedBy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->nama ?></td>
                        <td><?= $a->hp ?></td>
                        <td><?= $a->alamat_penerima ?></td>
                        <td>
                            <?php 
                                if ($a->file) { ?>
                                    <a href="<?= base_url() ?>assets/uploads/barcode/<?= $a->file ?>" class="btn pastel-mint" target="_blank">
                                        <?= strlen($a->file) > 20 ? substr($a->file, 0, 20) . '...' . substr($a->file, -4) : $a->file ?>
                                    
                                    </a>
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= $a->total_barcode ?></td>
                        <td><?= $a->total_jam.' Jam' ?></td>
                        <td>
                            <?php
                            if ($a->status == '1') {                                
                                // $nama_status = "pending";
                                $style = "font-size:14px";
                                $class = "pending-finance";
                            } elseif ($a->status == '3') {  
                                $style = "font-size:14px";
                                $class = "pending-rilis-po";
                            }else {
                                // $nama_status = "finish";
                                $style = "font-size:14px";
                                $class = "pending-scm";
                            }
                            ?>
                            <a href="<?= base_url() ?>barcode/update_status/<?= $a->signature ?>" class="btn <?= $class ?>" style = "<?= $style ?>" onclick="return confirm('Selesaikan status ini ?')"><?= $a->nama_status ?></a>
                            
                        </td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->username ?></td>
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
            "pageLength": 10,
            "ordering": false,
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

<script>
    function button()
    {
        
        var bulan  = document.getElementById('bulan').value;
        if (bulan) 
        {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
        $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>
