<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 12px;
    }
    th{
        font-size: 13px; 
    }

    .accordion_sellout {
        cursor: pointer;
        padding: 1px;
        width: 130%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;
    }

</style>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="accordion_sellout" id="accordionExample">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;" id="headingDeltomedSegment">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#sell_out_deltomed_segment" aria-expanded="true" aria-controls="collapseOne"><font color="black">Sell out deltomed <i>click here</i></font>
                            </button>
                        </h5>
                    </div>

                    <div id="sell_out_deltomed_segment" class="collapse" aria-labelledby="headingDeltomedSegment" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                        <div class="card-body">

                            <div class="mb-5">
                                <label class="form-control d-inline">Last Updated at <?= $monitoring_sellout_deltomed_segment_updated; ?></label>
                            </div>

                            <div class="mt-3">
                                <a href="<?= base_url().'management_office/export_sell_out_deltomed_segment' ?>" class="btn btn-secondary btn-sm" style="background-color: darkslategray;">export sell out Detlomed Segment</a>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>