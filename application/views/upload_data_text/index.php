<div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
      <div class="container">
        <div class="az-content-left az-content-left-components">

          <div class="component-item">

            <label>Upload Data Text</label>
            <nav class="nav flex-column">
              <a href="#" class="nav-link active">Form Upload</a>
              <a href="#" class="nav-link">History</a>
            </nav>


          </div>

        </div><!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Upload data text</span>
            <span>form upload</span>
          </div>
          <!-- <h2 class="az-content-title">Summary Sales MTI</h2> -->

          <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>


          <!-- row -->

          <hr class="mg-y-30" />

          <?php echo form_open_multipart($url); ?>

          
          <p class="mg-b-20">1. Pilih Jenis Data Text</p>

          <div class="row row-sm">

            <div class="col-sm-7 col-md-6 col-lg-4">
              
                <div class="custom-file">
                    
                    <input type="radio" id="html" name="fav_language" value="HTML">
                    <label for="html">Daily</label><br>
                    <input type="radio" id="css" name="fav_language" value="CSS">
                    <label for="css">All</label><br>
                </div>

            </div>

          </div>
          <!-- row -->

          <hr class="mg-y-30" />


          <p class="mg-b-20">2. Upload Data Anda</p>

          <div class="row row-sm">

            <div class="col-sm-7 col-md-6 col-lg-4">
              
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="file" />
                    <label class="custom-file-label" for="customFile">Upload Data Anda</label>                
                </div>

            </div>

            
            
          </div>
          <!-- row -->


          <br>

          <div class="row row-sm">

            <div class="col-sm-7 col-md-6 col-lg-4">
              <?php echo form_submit('submit', 'Proses Upload', 'class="btn btn-primary"'); ?>
              <?php echo form_close(); ?>
            </div>
            
          </div>
          <!-- row -->
















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