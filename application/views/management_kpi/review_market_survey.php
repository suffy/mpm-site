<style>
    .th-review{
        font-weight: bold;
        background-color: #f0f0f0;
        border: 0.5px solid #383838;
        color: #000000;
        font-size: 13px;
        border-radius: 10px;
        height: 50px;
    }
    .td-review{
        background-color: #ffffff;
        border: 0.1px solid #000000;
        font-size: 12px;
        /* line-height: 5px; */
        overflow:hidden;
        height: 100px;
        border-radius: 10px;
        padding: 10px;
    }
</style>

</div>

<div class="container-fluid">

<div class="row mt-1">
    <div class="col-md-12 az-content-label text-center">
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
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

</form>

    <?= form_open($url); ?>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Review</label>
        </div>
        <div class="col-md-5">
            <textarea name="review" class="form-control" id="review" cols="30" rows="10"></textarea>
        </div>
    </div>  
    
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Point (1-100)</label>
        </div>
        <div class="col-md-5">
            <input type="range" name="point" class="form-range" min="0" max="100" step="10" value="0" id="timerange" style="width: 100%">
            <label class="form-label">Point: <span id="timetext">0</span></label>
        </div>
    </div>  


    <div class="row mt-4">
        <div class="col-md-2">
            <input type="hidden" name='id_workspace' value = <?= $id_workspace ?>>
            <input type="hidden" name='signature_workspace' value = <?= $signature_workspace ?>>
            <input type="hidden" name='signature_market_survey' value = <?= $signature_market_survey ?>>
            <input type="hidden" name='id_market_survey' value = <?= $id_market_survey ?>>
        </div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-generate" id="btnKirim" onclick="return button()">Save Review</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
            <a href="<?= base_url('kpi/manage_workspace/'.$signature_workspace) ?>" class="btn btn-back" id="btnBack">back to event list</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

</div>

<div class="container">
    <div class="row mt-5 ms-5">
        <div class="col-md-12 az-content-label text-center">
            History Review
        </div>
    </div>

    <div class="row mt-3 mb-5">
        <div class="col-md-12">
            <table id="review" class="display" style="overflow-x: scroll; width: 100%;">
                <thead>
                    <tr>
                        <th class="th-review text-center col-1">No</th>
                        <th class="th-review text-center col-1">ReviewBy</th>
                        <th class="th-review text-center col-2">ReviewAt</th>
                        <th class="th-review text-center">Review</th>
                        <th class="th-review text-center col-1">Point</th>
                        <th class="th-review text-center col-1">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_data_review->result() as $a) : ?>
                    <tr>
                        <td class="td-review" align="center"><?= $no++ ?></td>
                        <td class="td-review"><?= $a->name ?></td>                       
                        <td class="td-review"><?= $a->created_at ?></td>                       
                        <td class="td-review"><?= $a->review ?></td>                       
                        <td class="td-review text-center"><?= $a->point ?></td>  
                        <td class="td-review text-center">
                            <a href="<?= base_url('kpi/delete_review_market_survey/'.$a->signature.'/'.$signature_workspace.'/'.$signature_market_survey) ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> del</a> 
                        </td>                     
                    </tr>
                    <?php endforeach; ?> 
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    function button()
    {
        var review   = document.getElementById('review').value;
        var timerange = document.getElementById('timerange').value;
        if (review && timerange) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>


<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
    $("#review").DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [0, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        "fixedHeader": {
            header: true,
            footer: true
        },
        scrollX: true
    });
    });
</script>

<script>
    const events = ['mousemove', 'touchmove']

    $.each(events, function(k,v) {
    $('#timerange').on(v, function() {
        $('#timetext').text($('#timerange').val());
    });
    })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
