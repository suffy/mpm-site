<div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
      <div class="container">
        
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Target Principal</span>
            <span>Deltomed</span>
          </div>
          <!-- <h2 class="az-content-title">Summary Sales MTI</h2> -->

          <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>
          
          <hr class="mg-y-10" />


          <div class="row row-sm">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="mapping" class="table table-hover table-striped table-bordered mg-b-0">
                    <thead>
                        <tr>
                            <th width="1px">No</th>
                            <th>Bulan</th>
                            <th>site code</th>
                            <th>target in unit</th>
                            <th>target in value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($get_data_import->result() as $a) : 
                        ?>
                        <tr>
                            <th scope="row"><?= $no++; ?></th>
                            <th scope="row"><?= $a->bulan; ?></th>
                            <th scope="row"><?= $a->site_code; ?></th>
                            <th scope="row"><?= $a->target_in_unit; ?></th>
                            <th scope="row"><?= $a->target_in_value; ?></th>
                        </tr>
                        <?php endforeach; ?> 
                    </tbody>
                    </table>
                </div>
            </div>
            <!-- col -->

            <div class="col-sm-12 mt-4">
                <?php echo form_open_multipart($url); ?>
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <?php echo form_submit('submit', 'Start Mapping', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>


          </div>
          <!-- row -->

          <hr class="mg-y-30" />



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
        $("#mapping").DataTable();
      });
    </script>