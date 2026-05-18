<style>
    .map-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }
    .map-wrapper {
        flex: 1;
        min-width: 300px;
    }
    .map {
        height: 500px;
        width: 100%;
    }
    .map-error {
        display: none;
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        text-align: center;
    }
    .map-title {
        font-weight: 600;
        text-align: center;
        margin-bottom: 10px;
        font-size: 1.2rem;
    }
    .city-filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .city-filter-container .form-control {
        max-width: 300px;
        margin-right: 10px;
    }
    .city-filter-label {
        margin-right: 10px;
        font-weight: 600;
        white-space: nowrap;
    }
    @media (max-width: 768px) {
        .city-filter-container {
            flex-direction: column;
            align-items: stretch;
        }
        .city-filter-container .form-control {
            margin-bottom: 10px;
        }
    }
</style>

</div>
<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
        <form action="<?= $url ?>" method="get">

        <div class="row mt-5">
            <div class="col-md-2">
                <label for="from">Periode</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from" id="from" min="2025-05-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                    <input type="date" name="to" id="to" min="2025-05-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="user">User</label> 
            </div>
            <div class="col-md-4">
                <select name="user" id="user" class="form-control">
                    <option value="all">All</option>
                    <?php foreach ($users->result() as $user) { ?>
                        <option value="<?= $user->username ?>"><?= $user->username ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp"></label> 
            </div>
            <div class="col-md-10">
                <input type="submit" class="btn btn-submit-red" name="submit" value="search" style="height: 45px;">  
                <!-- export -->
                <input type="submit" class="btn btn-submit-black" name="submit" value="export" style="height: 45px;">
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>

    <?php if (is_object($get_data)) { ?>

    <div class="card-block mt-1 mb-5">
        <div class="row">

            <div class="col-md-12">
                <table id="tabel" style="width:100%">
                    <thead>
                        <tr>       
                            <th class="text-center">username</th>         
                            <th class="text-center">nama toko</th>         
                            <th class="text-center">kategori</th> 
                            <th class="text-center">durasi visit</th> 
                            <th class="text-center">result visit</th> 
                            <th class="text-center">result kompetitor</th> 
                            <th class="text-center">image</th> 
                        </tr>
                    </thead>
                    <tbody>     
                        <?php
                            foreach ($get_data->result() as $a) : ?>        
                            <tr> 
                                <td><?= $a->username ?></td>   
                                <td><?= $a->nama_toko ?></td>   
                                <td><?= $a->category ?></td>   
                                <td><?= $a->durasi ?></td>   
                                <td><?= $a->result_visit ?></td>   
                                <td><?= $a->result_competitor ?></td>   
                                <td>
                                <?php 
                                    if ($a->image == null) 
                                    {
                                        echo "No Image";
                                    }else{ ?>
                                    <a href="<?= $a->image ?>" target="_blank">
                                        <img src=<?= $a->image ?> alt<?= $a->nama_toko ?> style="width: 100px; height: 100px;">
                                    </a>
                                    <?php
                                    } ?>
                                </td>
                            </tr>
                            <?php 
                            endforeach; 
                        ?>   
                    </tbody>
                </table>

            </div>


        </div>
    </div>

    <?php } ?>

</div>

<script>
    $(document).ready(function () {
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