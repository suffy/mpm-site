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
                            <th>Product</th>
                            <th>NamaProduct</th>
                            <th>Group</th>
                            <th>SubGroup</th>
                            
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_registrasi_program_product->result() as $a) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $a->nomor_surat ?></td>
                            <td><?= $a->nama_program ?></td>
                            <td><?= $a->kodeprod ?></td>
                            <td><?= $a->namaprod ?></td>
                            <td><?= $a->grup ?></td>
                            <td><?= $a->subgroup ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <a href="<?= base_url() ?>management_claim/delete_registrasi_program_product/<?= $signature_program ?>" class="btn btn-submit-red" style ="width: 100%;">clear data</a>
            </div>
        </div>
    </div>
    
<hr class = "mt-5">
  

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
                            <th>Kodeproduk</th>
                            <th>Nama Product</th>
                            <th>Group</th>
                            <th>Sub Group</th>
                            
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_product_by_supp->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->kodeprod; ?>" name="options[]" class="<?= $a->kodeprod; ?>" value="<?= $a->kodeprod; ?>">
                                <input type="text" name = "kodeprod[]" value="<?= $a->kodeprod ?>" hidden>
                                </center>
                            </td>  
                            <td><?= $no++ ?></td>
                            <td><?= $a->kodeprod ?></td>
                            <td><?= $a->namaprod ?></td>
                            <td><?= $a->nama_group ?></td>
                            <td><?= $a->nama_sub_group ?></td>
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