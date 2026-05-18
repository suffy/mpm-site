<!-- </div>
<div class="container-fluid"> -->
<div class="p-3">
  <div class="row mt-5">
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
          <h5 class="card-title"><?= $title ?></h5>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-12">
          <span class="btn btn-light">data updated_at : <?= $data_date ?></span>
          <a href="<?= base_url().'pareto/master_outlet_mti' ?>" class="btn btn-primary">master outlet</a>
        </div>
      </div>
      <hr>
      <div class="row mt-4">
        <div class="col-md-12">
          <table id="tabel" style="width:100%">
            <thead>
              <tr>
                <th width="1%">Rank 2025</th> 
                <th width="1%">Rank 2026</th> 
                <th>Group</th>            
                <th>Outlet</th>            
                <th>Count 2025</th>            
                <th>Count 2026</th>            
                <th>Bruto 2025</th>       
                <th>Bruto 2026</th>    
                <th>#</th>   
              </tr>
            </thead>
            <tbody>
              <?php 
                foreach($get_data->result() as $a) : ?>
                <tr>
                  <td><?= $a->rank ?></td>
                  <td><?= $a->actual_rank ?></td>
                  <td><?= $a->sub_group ?></td>
                  <td><?= $a->outlet ?></td>
                  <td><?= $a->count_sub_group ?></td>
                  <td><?= $a->actual_count_sub_group ?></td>
                  <td><?= number_format($a->bruto) ?></td>
                  <td><?= number_format($a->actual_bruto) ?></td>
                  <td>
                    <a href="<?= base_url().'pareto/rank_mti_detail/'.$a->actual_rank ?>" class="btn btn-dark">detail</a>
                  </td>
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
      "order": [1, 'asc'],
      "aLengthMenu": [
          [10, 20, 50, -1],
          [10, 20, 50, "All"]
      ],
      scrollX: true,
    });

    

  });
</script>