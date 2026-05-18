<div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
      <div class="container">
        <div class="az-content-left az-content-left-components">

          <div class="component-item">

            <label>Target Deltomed</label>
            <nav class="nav flex-column">
              <a href="#" class="nav-link active">SubBranch</a>
              <a href="#" class="nav-link">Divisi</a>
              <a href="#" class="nav-link">Kodeproduk</a>
            </nav>

            <label>Target Ultra Sakti</label>
            <nav class="nav flex-column">
              <a href="#" class="nav-link">SubBranch</a>
              <a href="#" class="nav-link">Divisi</a>
              <a href="#" class="nav-link">Kodeproduk</a>
            </nav>

          </div>

        </div><!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Target Principal</span>
            <span>Deltomed</span>
          </div>
          <!-- <h2 class="az-content-title">Summary Sales MTI</h2> -->

          <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>
          
          <hr class="mg-y-30" />

          <p class="mg-b-20">1. Download Template Target</p>

          <div class="row row-sm">
            <div class="col-sm-7 col-md-6 col-lg-4">
              <div class="custom-file">
                <a href="<?= base_url().'target_principal/template_deltomed_import_subbranch' ?>" class="btn btn-secondary">download</a>
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->

          <hr class="mg-y-30" />

          <p class="mg-b-20">2. Upload Raw Target</p>

          <?php echo form_open_multipart($url); ?>

          <div class="row row-sm">
            <div class="col-sm-7 col-md-6 col-lg-4">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" name="file" />
                <label class="custom-file-label" for="customFile">Upload Raw Target</label>
                
              </div>
            </div>

            <div class="col-sm-7 col-md-6 col-lg-4">
              <?php echo form_submit('submit', 'Proses Import', 'class="btn btn-primary"'); ?>
              <?php echo form_close(); ?>
            </div>
          </div>
          <!-- row -->

          <hr class="mg-y-30" />

          <p class="mg-b-20">Log Import</p>

          <div class="row row-sm">
            <div class="col-sm-12">
              

            <div class="table-responsive">
                <table id="data" class="table table-hover table-striped table-bordered mg-b-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pola</th>
                        <th>Count Raw</th>
                        <th>Count Mapping</th>
                        <th>Filename</th>
                        <th>CreatedAt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($get_log_import->result() as $a) : 
                    ?>
                    <tr>
                        <th scope="row"><?= $no++; ?></th>
                        <th scope="row"><?= $a->pola; ?></th>
                        <th scope="row"><?= $a->count_raw; ?></th>
                        <th scope="row"><?= $a->count_mapping; ?></th>
                        <th scope="row">
                          <a href="<?= base_url().'assets/uploads/target_principal/import/'.$a->filename ?>" target="_blank"><?= $a->filename; ?></a>                          
                        </th>
                        <th scope="row"><?= $a->created_at; ?></th>
                    </tr>
                    <?php endforeach; ?> 
                </tbody>
                </table>
            </div>



            </div>
            <!-- col -->
          </div>
          <!-- row -->

          

          <hr class="mg-y-30">

          

          <div class="ht-40"></div>
        </div><!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->


    <script>
      $(document).ready(function () {
        $("#data").DataTable();
      });
    </script>