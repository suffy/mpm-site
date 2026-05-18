<div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
      <div class="container">
        <div class="az-content-left az-content-left-components">
          <div class="component-item">
            <label>MTI Sales</label>
            <nav class="nav flex-column">
              <a href="#" class="nav-link active">Summary</a>
              <!-- <a href="#" class="nav-link">Herbal</a>
              <a href="#" class="nav-link">Candy (exclude 010121)</a>
              <a href="#" class="nav-link">RTD</a> -->
            </nav>
            
          </div><!-- component-item -->

        </div><!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Dashboard</span>
            <span>MTI</span>
            <span>Summary</span>
          </div>
          <!-- <h2 class="az-content-title">Summary Sales MTI</h2> -->

          <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>
          <p class="mg-b-20"></p>

          <!-- <div class="col mb-3"> -->
            <a href="<?= base_url().'mti/update_data_mti' ?>" class="btn btn-warning">update data terbaru</a>
          <!-- </div> -->
          <br>

          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered mg-b-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Category / Divisi</th>
                  <th>Value</th>
                  <th>Unit</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 1;
                  foreach ($get_mti->result() as $a) : 
                ?>
                <tr>
                  <th scope="row"><?= $no++; ?></th>
                  <td><?= $a->divisi ?></td>
                  <td><?= number_format($a->omzet) ?></td>
                  <td><?= number_format($a->unit) ?></td>
                  <td><?= $a->created_at ?></td>
                </tr>
                <?php endforeach; ?> 
              </tbody>
            </table>
          </div>

          <br><br>

          <div class="az-content-label mg-b-5 mt-3"><?= $title_herbal ?></div>
          <p class="mg-b-20"></p>

          <div class="table-responsive">
            <table id="herbal-breakdown" class="table table-hover table-striped table-bordered mg-b-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Category / Divisi</th>
                  <th>Branch</th>
                  <th>Sub Branch</th>
                  <th>Value</th>
                  <th>Unit</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 1;
                  foreach ($get_mti_herbal->result() as $a) : 
                ?>
                <tr>
                  <th scope="row"><?= $no++; ?></th>
                  <td><?= $a->divisi ?></td>
                  <td><?= $a->branch_name ?></td>
                  <td><?= $a->nama_comp ?></td>
                  <td><?= number_format($a->omzet) ?></td>
                  <td><?= number_format($a->unit) ?></td>
                  <td><?= $a->created_at ?></td>
                </tr>
                <?php endforeach; ?> 
              </tbody>
            </table>
          </div>

          <br><br>

          <div class="az-content-label mg-b-5 mt-3"><?= $title_candy ?></div>
          <p class="mg-b-20"></p>

          <div class="table-responsive">
            <table id="candy-breakdown" class="table table-hover table-striped table-bordered mg-b-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Category / Divisi</th>
                  <th>Branch</th>
                  <th>Sub Branch</th>
                  <th>Value</th>
                  <th>Unit</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 1;
                  foreach ($get_mti_candy->result() as $a) : 
                ?>
                <tr>
                  <th scope="row"><?= $no++; ?></th>
                  <td><?= $a->divisi ?></td>
                  <td><?= $a->branch_name ?></td>
                  <td><?= $a->nama_comp ?></td>
                  <td><?= number_format($a->omzet) ?></td>
                  <td><?= number_format($a->unit) ?></td>
                  <td><?= $a->created_at ?></td>
                </tr>
                <?php endforeach; ?> 
              </tbody>
            </table>
          </div>

          <br><br>

          <div class="az-content-label mg-b-5 mt-3"><?= $title_rtd ?></div>
          <p class="mg-b-20"></p>

          <div class="table-responsive">
            <table id="rtd-breakdown" class="table table-hover table-striped table-bordered mg-b-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Category / Divisi</th>
                  <th>Branch</th>
                  <th>Sub Branch</th>
                  <th>Value</th>
                  <th>Unit</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 1;
                  foreach ($get_mti_rtd->result() as $a) : 
                ?>
                <tr>
                  <th scope="row"><?= $no++; ?></th>
                  <td><?= $a->divisi ?></td>
                  <td><?= $a->branch_name ?></td>
                  <td><?= $a->nama_comp ?></td>
                  <td><?= number_format($a->omzet) ?></td>
                  <td><?= number_format($a->unit) ?></td>
                  <td><?= $a->created_at ?></td>
                </tr>
                <?php endforeach; ?> 
              </tbody>
            </table>
          </div>







          <hr class="mg-y-30">

          

          <div class="ht-40"></div>
        </div><!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->


    <script>
      $(document).ready(function () {
        $("#herbal-breakdown").DataTable();
        $("#candy-breakdown").DataTable();
        $("#rtd-breakdown").DataTable();
      });
    </script>