<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


</div>
<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
        
        <!-- <form action="<?= $url ?>">     -->
        <form action="<?= base_url().$url ?>">    

        <div class="row mt-5">
            <div class="col-md-2">
                <label for="from">Periode</label> 
            </div>
            <div class="col-md-4">
                <input type="month" class="form-control" name="bulan" id="bulan" value="<?= $this->input->get('bulan') ?>" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp"></label> 
            </div>
            <div class="col-md-10">
                <input type="submit" class="btn btn-submit-red" value="search" style="height: 45px;">   
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>


    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="col-md-12">
                <table id="tabel-ajuan-claim" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Bulan</th>           
                            <th class="text-center">Category</th>           
                            <th class="text-center">Total unit</th>           
                            <th class="text-center">Total value</th>           
                        </tr>
                    </thead>
                    <tbody>       
                        <?php
                        // var_dump($get_herbal);
                        // die;
                        foreach ($get_herbal->result() as $a) : ?>        
                        <tr>
                            <td><?= $this->input->get('bulan') ?></td>   
                            <td>Herbal MTI</td>   
                            <td><?= number_format($a->total_unit,0) ?></td>   
                            <td><?= number_format($a->total_value,0) ?></td>   
                        </tr>
                        <?php endforeach; ?>   
                        <?php
                        foreach ($get_candy->result() as $a) : ?>        
                        <tr>
                            <td><?= $this->input->get('bulan') ?></td>   
                            <td>Candy MTI</td>   
                            <td><?= number_format($a->total_unit,0) ?></td>   
                            <td><?= number_format($a->total_value,0) ?></td>   
                        </tr>
                        <?php endforeach; ?>   
                        <?php
                        foreach ($get_deltomed_mti->result() as $a) : ?>        
                        <tr>
                            <td><?= $this->input->get('bulan') ?></td>   
                            <td>Deltomed MTI</td>   
                            <td><?= number_format($a->total_unit,0) ?></td>   
                            <td><?= number_format($a->total_value,0) ?></td>   
                        </tr>
                        <?php endforeach; ?>   
                        <?php
                        foreach ($get_all_principal_mti->result() as $a) : ?>        
                        <tr>
                            <td><?= $this->input->get('bulan') ?></td>   
                            <td>Principal MTI ALL </td>   
                            <td><?= number_format($a->total_unit,0) ?></td>   
                            <td><?= number_format($a->total_value,0) ?></td>   
                        </tr>
                        <?php endforeach; ?>   
                        <?php
                        foreach ($get_herbal_apotik->result() as $a) : ?>        
                        <tr>
                            <td><?= $this->input->get('bulan') ?></td>   
                            <td>Herbal Apotik</td>   
                            <td><?= number_format($a->total_unit,0) ?></td>   
                            <td><?= number_format($a->total_value,0) ?></td>   
                        </tr>
                        <?php endforeach; ?> 
                        <?php
                        foreach ($get_candy_apotik->result() as $a) : ?>        
                        <tr>
                            <td><?= $this->input->get('bulan') ?></td>   
                            <td>Candy Apotik</td>   
                            <td><?= number_format($a->total_unit,0) ?></td>   
                            <td><?= number_format($a->total_value,0) ?></td>   
                        </tr>                       
                        <?php endforeach; ?> 
                        <?php
                        foreach ($get_deltomed_apotik->result() as $a) : ?>        
                        <tr>
                            <td><?= $this->input->get('bulan') ?></td>   
                            <td>Deltomed Apotik</td>   
                            <td><?= number_format($a->total_unit,0) ?></td>   
                            <td><?= number_format($a->total_value,0) ?></td>   
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
        $('#tabel-ajuan-claim').DataTable({
            "pageLength": 10,
            "ordering": false,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });
</script>

</body>
</html>