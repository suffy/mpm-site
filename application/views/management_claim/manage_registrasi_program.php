</div>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="card-block mb-2">
        <div class="row">
            <div class="col-md-12 mt-4">
                <table id="example2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Surat</th>
                            <th>Nama Program</th>
                            <th>siteCode</th>
                            <th>BranchName</th>
                            <th>SubBranch</th>
                            
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_registrasi_program->result() as $a) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $a->nomor_surat ?></td>
                            <td><?= $a->nama_program ?></td>
                            <td><?= $a->site_code ?></td>
                            <td><?= $a->branch_name ?></td>
                            <td><?= $a->nama_comp ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <a href="<?= base_url() ?>management_claim/delete_registrasi_program_site_code/<?= $signature_program ?>" class="btn btn-submit-red" style ="width: 100%;">clear data</a>
            </div>
        </div>
    </div>

<hr class = "mt-5">
    
<form action="<?= base_url() ?>management_claim/manage_registrasi_program/<?= $signature_program ?>/" method="get">

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            <?= $title2 ?>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-2">
            <label for="tahun" class="form-label">Tahun</label> 
        </div>
        <div class="col-lg-4">
            
            <input type="hidden" name = "signature_program" value="<?= $signature_program ?>">
            <select id="tahun" name="tahun" class="form-control" required>
                <option value=""> -- pilih tahun -- </option>
                <option value="2024" <?= $tahun == '2024' ? 'selected' : '' ?>> 2024 </option>
                <option value="2025" <?= $tahun == '2025' ? 'selected' : '' ?>> 2025 </option>
            </select>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-lg-2">
            
        </div>
        <div class="col-lg-4 d-flex flex-row">
            <button type="submit" class="btn btn-submit-black">Tampilkan Data</button>
        </div>
    </div>
</form>


<?php echo form_open_multipart($url_save); ?>

    <div class="card-block mt-5 mb-5">
        <div class="row">
        
            <div class="col-md-12 mt-4">
                <table id="example">
                    <thead>
                        <tr>
                            <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                <font size="1px" color="white"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                            </th>
                            <th>No</th>
                            <th>siteCode</th>
                            <th>BranchName</th>
                            <th>SubBranch</th>
                            
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($master_site->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                <input type="text" name = "site_code[]" value="<?= $a->site_code ?>" hidden>
                                </center>
                            </td>  
                            <td><?= $no++ ?></td>
                            <td><?= $a->site_code ?></td>
                            <td><?= $a->branch_name ?></td>
                            <td><?= $a->nama_comp ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>



    <div class="row mt-3 mb-5">
        <div class="col-md-12">
            <input type="hidden" name = "signature_program" value="<?= $signature_program ?>">
            <button type="submit" id="btnBack" class="btn btn-submit-black" style ="width: 100%;">Submit Data</button>
        </div>
    </div>
</div>
<?= form_close(); ?>

    <script>
        $(document).ready(function () {
            $("#btnBack").show();
            $("#btnLoading").hide();
            $('#example').DataTable({
                "pageLength": 200,
                "ordering": false,
                "order": [0, 'asc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "fixedHeader": {
                    header: true,
                    footer: true
                },
                scrollY: '500px'
            });
            $('#example2').DataTable({
                "pageLength": 10,
                "ordering": false,
                "order": [0, 'asc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "fixedHeader": {
                    header: true,
                    footer: true
                },
            });
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


<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>