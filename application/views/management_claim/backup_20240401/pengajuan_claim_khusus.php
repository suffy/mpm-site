<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    .batas{
        border: 1px dotted grey;
        border-radius: 5px;
    }

    th{
        font-weight: bold;
        background-color: #FFEAA7;
        border: 0.5px solid #383838;
        color: #000000;
        font-size: 13px;
    }
    td{
        background-color: #ffffff;
        border: 0.5px solid #000000;
        font-size: 12px;
        /* line-height: 5px; */
        overflow:hidden;
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #365486;
    }

    .btn-hardcopy {
        color: #f0f0f0;
        background-color: #37B5B6;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-hardcopy:hover {
        color: black;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #FE7A36;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingprincipal {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
        border: 2px solid black;
    }
    
    .btn-null {
        color: black;
        background-color: #F9EFDB;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    .btn-generate {
        color: #f0f0f0;
        background-color: #638889;
        border-radius: 5px;
        border: 2px solid black;
    }

</style>

</div>

<div class="container">
    
<?php echo form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-3">
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

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Principal (*)</label> 
        </div>
        <div class="col-md-4">
            <select id="supp" name="supp" class="form-control" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001"> Deltomed </option>
                <option value="001-herbana"> Herbana </option>
                <option value="002"> Marguna </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Periode Program (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="from" type="date" name="from" required>
            <input class="form-control form-control-md" id="to" type="date" name="to" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Nomor Surat Program (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="nomor_surat" type="text" name="nomor_surat" placeholder="no surat program" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="keterangan" class="form-label">Keterangan (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <textarea class="form-control" id="keterangan" name="keterangan" cols="5" rows="5" placeholder="keterangan" required></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_pengirim" class="form-label">Nama Pengirim (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="nama_pengirim" type="text" name="nama_pengirim" placeholder="nama pengirim" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="email_pengirim" class="form-label">Email Pengirim (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="email_pengirim" type="text" name="email_pengirim" placeholder="email pengirim" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nominal" class="form-label">Nominal Value (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="nominal" type="text" name="nominal" placeholder="Rp." required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="upload_jpg" class="form-label">Upload Dokumen 1 (Excel)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md"  type="file" name="upload_dok_1">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="upload_jpg" class="form-label">Upload Dokumen 2 (Pdf)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md"  type="file" name="upload_dok_2">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="upload_jpg" class="form-label">Upload Dokumen 3 (Zip)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md"  type="file" name="upload_dok_3">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4 d-flex flex-row">
            <button type="submit" class="btn btn-generate">Submit Claim</button>
        </div>
    </div>
</form>

</div>
</div>

<div class="container mt-1">

    <div class="card-block mt-2 mb-5">
        <div class="row">
            <div class="col-md-12">
                <hr class="batas">
            </div>
        
            <div class="col-md-12 mt-4">

                <table id="example" class="display" style="overflow-x: scroll;">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Principal</th>
                            <th class="text-center">Nomor</th>
                            <th class="text-center">Periode</th>                            
                            <th class="text-center">Keterangan</th>                            
                            <th class="text-center">Nama Pengirim</th>                            
                            <th class="text-center">Email Pengirim</th>                            
                            <th class="text-center">Nominal</th>                            
                            <th class="text-center">Upload 1</th>                            
                            <th class="text-center">Upload 2</th>                            
                            <th class="text-center">Upload 3</th>                            
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_ajuan_claim_khusus->result() as $a) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>       
                            <td><?= $a->nama_status ?></td>                    
                            <td><?= $a->supp ?></td>                    
                            <td><?= $a->nomor_surat_program ?></td>                    
                            <td><?= $a->periode_program_from.' - '.$a->periode_program_to ?></td>                    
                            <td><?= $a->keterangan ?></td>                    
                            <td><?= $a->nama_pengirim ?></td>                    
                            <td><?= $a->email_pengirim ?></td>                    
                            <td><?= $a->nominal_value ?></td>                    
                            <td><?= $a->upload_dokumen_1 ?></td>                    
                            <td><?= $a->upload_dokumen_2 ?></td>                    
                            <td><?= $a->upload_dokumen_3 ?></td>                    
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable(
                {
                    scrollX: true
                }
            );
        });
    </script>

    <script>    
        $("select[name = status_template]").on("change", function() {
            var status_template_terpilih = document.getElementById('status_template').value;
            let element = document.getElementById("mydiv");
            console.log(status_template_terpilih);
            if (status_template_terpilih == 1) { //jika ya
                document.getElementById("template_program").required = true;
                element.removeAttribute("hidden");
            }else{
                element.setAttribute("hidden", "hidden");
                document.getElementById('template_program').removeAttribute('required');
            }
        });
    </script>