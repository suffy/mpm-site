<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3><?= $title ?></h3>
        </div>
    </div>
</div>

<!-- <div class="container-fluid">
    <div class="row mt-3" style='background-color: #f2f2f2;'>
        <div class="col-md-12">
            <div class="input-group">
                <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to') ?>" required>                
            </div>
        </div>
        <div class="col-md-4">
            
            <input type="submit" value="Search Data" class="btn btn-submit-black">
        </div>
    </div>
</div> -->

<form action="management_office" method="GET">
<div class="container-fluid mt-1 d-flex flex-row-reverse">
    <div class="col-md-4 d-flex flex-row">
        <div class="input-group">
            <!-- <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
            <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to') ?>" required>     -->
            
             <input type="month" name="month" class="form-control" value="<?= $this->input->get('month') ?>" required>  
        </div>

        <div class="col-md-4">
            <input type="submit" value="Search Data" class="btn btn-submit-black">
        </div>
    </div>
</div>
</form>