<style>
    .graphBox{
        position: relative;
        width: 100%;
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr 2fr;
        grid-gap: 30px;
        min-height: 200px;
    }

    .graphBoxYear{
        position: relative;
        width: 100%;
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr;
        grid-gap: 30px;
        min-height: 200px;
    }

    .containerChart{
        position: relative;
        width: 100%;
        padding: 20px;
        /* display: grid;
        grid-template-columns: 1fr 2fr;
        grid-gap: 30px; */
        min-height: 200px;
        /* background: var(--bs-dark-text-emphasis);
        color: var(--bs-body-bg); */
    }

    .graphBox .box{
        position: relative;
        /* background: #fff; */
        /* background-color: var(--bs-dark-text-emphasis); */
        background-color: var(---bs-body-bg);
        padding: 20px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
    }

    .graphBoxYear .boxYear{
        position: relative;
        /* background: #fff; */
        /* background-color: var(--bs-dark-text-emphasis); */
        background-color: var(---bs-body-bg);
        padding: 20px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
    }

    @media (max-width: 991px) {
        .graphBox{
            grid-template-columns: 1fr;
            height: auto;
        }
    }



</style>

<!-- <div class="container">
    <div class="row">
        <div class="col-md-6">
            <canvas id="myChart" style="width: 100%; height: 400px;"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="earning" style="width: 100%; height: 400px;"></canvas>
        </div>
    </div>
</div> -->
<div class="graphBox">
    <div class="box text-center">
        <h5>Top Sales By Product</h5>
        <canvas id="myChart"></canvas>
    </div>
    <div class="box">
        <canvas id="earning" style="width: 200px; height: 100px;"></canvas>
    </div>
</div>

<div class="graphBox">
    <div class="box text-center">
        <h5>Top Sales By Segment</h5>
        <canvas id="segment" style="width: 200px; height: 100px;"></canvas>
    </div>
    <div class="box">
        <canvas id="kode_type" style="width: 200px; height: 100px;"></canvas>
    </div>
</div>

<div class="graphBoxYear">
    <div class="boxYear">
         <canvas id="xxx"></canvas>
    </div>
</div>




<?php 
    foreach ($get_omzet_by_bulan->result() as $a) {
        $nama_comp[] = $a->nama_comp;   
        $omzet[] = $a->omzet;
    }

    foreach ($get_omzet_by_produk_bulan->result() as $a) {
        $namaprod[] = $a->namaprod;   
        $omzet_produk[] = $a->omzet;
    }

    foreach ($get_omzet_by_kode_type->result() as $a) {
        $nama_type[] = $a->nama_type;   
        $omzet_type[] = $a->omzet;
    }

    foreach ($get_omzet_by_segment->result() as $a) {
        $segment[] = $a->segment;   
        $omzet_segment[] = $a->omzet;
    }

    foreach ($get_omzet_by_tahun->result() as $a) {
        $bulan[] = $a->bulan;   
        $omzet_tahun[] = $a->omzet;
    }

    foreach ($get_omzet_by_tahun_current->result() as $a) {
        $bulan[] = $a->bulan;   
        $omzet_tahun_current[] = $a->omzet;
    }
    
    foreach ($get_omzet_by_tahun_old->result() as $a) {
        $bulan[] = $a->bulan;   
        $omzet_tahun_old[] = $a->omzet;
    }

    // var_dump($bulan);
    // var_dump($omzet_tahun);

?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');
  const earning = document.getElementById('earning');
  const kode_type = document.getElementById('kode_type');
  const segment = document.getElementById('segment');

  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: <?php echo json_encode($namaprod); ?>,
      datasets: [{
        label: 'Top Sales By Product',
        data: <?php echo json_encode($omzet_produk); ?>,
        backgroundColor: [
            'rgba(255, 99, 132, 0.9)',
            'rgba(255, 159, 64, 0.9)',
            'rgba(255, 205, 86, 0.9)',
        ],
        borderWidth: 1
      }]
    },
    options: {
    //   responsive: true
    }
  });

  new Chart(earning, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($nama_comp); ?>,
      datasets: [{
        label: 'Top Sales By SubBranch',
        data: <?php echo json_encode($omzet); ?>,
        backgroundColor: [
            'rgba(255, 99, 132, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(255, 205, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(153, 102, 255, 0.5)',
        ],
        borderWidth: 1
      }]
    },
    options: {
    //   responsive: true
    }

    
  });

  new Chart(kode_type, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($nama_type); ?>,
      datasets: [{
        label: 'Top Sales By Type',
        data: <?php echo json_encode($omzet_type); ?>,
        // backgroundColor: [
        //     'rgba(255, 99, 132, 0.5)',
        //     'rgba(255, 159, 64, 0.5)',
        //     'rgba(255, 205, 86, 0.5)',
        //     'rgba(75, 192, 192, 0.5)',
        //     'rgba(54, 162, 235, 0.5)',
        //     'rgba(153, 102, 255, 0.5)',
        // ],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1,
        borderWidth: 3
      }]
    },
    options: {
    //   responsive: true
    }

    
  });

  new Chart(segment, {
    type: 'polarArea',
    data: {
      labels: <?php echo json_encode($segment); ?>,
      datasets: [{
        label: 'Top Sales By Product',
        data: <?php echo json_encode($omzet_segment); ?>,
        backgroundColor: [
            'rgba(255, 99, 132, 0.9)',
            'rgba(255, 159, 64, 0.9)',
            'rgba(255, 205, 86, 0.9)',
        ],
        borderWidth: 1
      }]
    },
    options: {
    //   responsive: true
    }
  });


  const xValues = ["jan", "feb", "mar", "apr", "mei", "jun", "jul", "ags", "sep", "okt", "nov", "des"];

  new Chart("xxx", {
    type: "line",
    data: {
      labels: xValues,
      datasets: [
        {
          label: "Total Sales in 2023",
          backgroundColor: '#FFCF50',
          borderColor: '#FFCF50',
          data: <?php echo json_encode($omzet_tahun_old); ?>,
          fill: false,
        },
        {
          label: "Total Sales in 2024",
          backgroundColor: "rgb(255, 99, 132)",
          borderColor: "rgb(255, 99, 132)",
          data: <?php echo json_encode($omzet_tahun); ?>,
          fill: false,
        },
        {
          label: "Total Sales in 2025",
          backgroundColor: "rgb(54, 162, 235)",
          borderColor: "rgb(54, 162, 235)",
          data: <?php echo json_encode($omzet_tahun_current); ?>,
          fill: false,
        },
      ],
    },
  });

  


</script>

