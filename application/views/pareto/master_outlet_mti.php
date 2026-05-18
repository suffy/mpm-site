</div>
<div class="container-fluid">

  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h5 class="card-title"><?= $title ?></h5>
        </div>
      </div>

      <hr>

      <div class="row mt-4">
        <div class="col-md-12">
          <p>Isi Form di Bawah Ini</p>
        </div>
      </div>

      <?= form_open($url, ['method' => 'post'])?> 

      <div class="row mt-2">
        <div class="col-lg-2">
          <label for="site_code">Site Code</label> 
        </div>
        <div class="col-lg-8">
          <input type="text" class="form-control" name="site_code" id="site_code" placeholder="Masukan Subbranch ..." required>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-lg-2">
          <label for="outlet">Outlet</label> 
        </div>
        <div class="col-lg-8">
          <input type="text" class="form-control" name="outlet" id="outlet" placeholder="Masukan Outlet ..." required>
        </div>
      </div>
      
      <div class="row mt-2">
        <div class="col-lg-2">
          <label for="nama_outlet">Nama Outlet</label> 
        </div>
        <div class="col-lg-8">
          <input type="text" class="form-control" name="nama_outlet" id="nama_outlet" placeholder="Masukan Nama Outlet ..." required>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-lg-2">
          
        </div>
        <div class="col-lg-8">
          <?= form_submit('submit', 'Submit Pengajuan Claim', 'class="btn btn-primary" id="submitBtn"'); ?>
          <a href="<?= base_url().'pareto/truncate_master_outlet_mti' ?>" class="btn btn-danger">truncate data</a>
        </div>
      </div>      
      <?= form_close(); ?>

    </div>
  </div>

  <?= form_open_multipart($url_import, ['method' => 'post'])?> 

  <div class="card mt-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <p>Import Data</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-2">
          <label for="file">File</label> 
        </div>
        <div class="col-lg-8">
          <input type="file" class="form-control" name="file" id="file" required>
        </div>
      </div>
      <div class="row mt-2">
        <div class="col-lg-2">
          
        </div>
        <div class="col-lg-8">
          <?= form_submit('submit', 'Import Data', 'class="btn btn-primary" id="submitBtn"'); ?>
          <a href="<?= base_url().'pareto/export_template_master_outlet_mti' ?>" class="btn btn-dark">download template</a>
        </div>
      </div>      
      <?= form_close(); ?>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-12 text-center">
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

  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <p>Tabel Master Outlet MTI</p>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <a href="<?= base_url().'pareto/export_master_outlet_mti' ?>" class="btn btn-warning">export data</a>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-md-12">
          <table id="tabel" style="width:100%">
            <thead>
              <tr>
                <th width="1%">No</th> 
                <th>Subbranch</th> 
                <th>Outlet</th>            
                <th>Nama</th>            
                <th>SubGroup</th>            
                <th>Active</th>     
              </tr>
            </thead>
            <tbody>
              <?php 
                $no = 1;
                foreach($get_data->result() as $a) : ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $a->site_code.' - '.$a->branch_name.' - '.$a->nama_comp ?></td>
                  <td><?= $a->outlet ?></td>
                  <td><?= $a->nama_outlet ?></td>
                  <td><?= $a->sub_group ?></td>
                  <td><?= $a->is_active ? 'true' : 'false' ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  $(document).ready(function () {
    $('#tabel').DataTable({
      "pageLength": 100,
      "ordering": true,
      "order": [0, 'asc'],
      "aLengthMenu": [
          [10, 20, 50, -1],
          [10, 20, 50, "All"]
      ],
      scrollX: true,
    });
  });
</script>