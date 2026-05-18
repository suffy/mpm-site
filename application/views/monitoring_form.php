<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<?php $this->load->view('management_claim/css/style') ?>

</div>

<form action="<?= $url ?>" method="get">
<div class="container-fluid mb-1">

    <div class="card">
        <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>


    <div class="row mt-5">
        <div class="col-lg-2">
            <label class="form-label">Periode Program </label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="date" name="from" id="from" class="form-control custom-input" value="<?= $this->input->get('from') ?>" required>
                <input type="date" name="to" id="to" class="form-control custom-input" value="<?= $this->input->get('to') ?>" required>
            </div>
        </div>
    </div>

    

    <div class="row mt-1">
        <div class="col-lg-2">
            <label for="breakdown" class="form-label">Breakdown</label> 
        </div>
        <div class="col-md-4">
            <select name="breakdown" class="form-control" required>
                <option value="">Pilih Breakdown</option>
                <option value="status_internal" <?= $breakdown == 'status_internal' ? 'selected' : '' ?>>by status internal</option>
                <option value="status" disabled>by status (coming soon)</option>
                <option value="nomor_surat" <?= $breakdown == 'nomor_surat' ? 'selected' : '' ?>>by program</option>
                <option value="principal" disabled>by principal (coming soon)</option>
                <option value="date" disabled>by date (coming soon)</option>
                <option value="category" disabled>by category (coming soon)</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-2">
            <label for="nama_program"></label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="submit" value="Search Data" class="btn btn-submit-red">
            </div>
        </div>
    </div>

</div>
    </div>
</div>
</form>