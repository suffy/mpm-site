<style>
    :root {
        /* ---bs-body-bg: #f8f9fa; */
    }

    .graphContainer {
        display: flex;
        flex-direction: row;
        gap: 30px;
        width: 100%;
        margin-bottom: 30px;
    }

    .graphBox {
        position: relative;
        background-color: var(---bs-body-bg);
        padding: 20px;
        flex: 1;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        min-height: 300px;border: 1px solid #e0e0e0;
    }

    canvas {
        width: 100% !important;
        height: 380px !important;
    }

    @media (max-width: 991px) {
        .graphContainer {
            flex-direction: column;
        }
        
        .graphBox {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <div class="graphContainer">
        <div class="graphBox">
            <canvas id="earning"></canvas>
        </div>
        <div class="graphBox">
            <canvas id="xxx"></canvas>
        </div>
    </div>
</div>

<?php 
    foreach ($get_omzet_by_bulan as $a) {
        $nama_comp[] = $a->nama_comp;   
        $omzet[] = $a->omzet;
    }

    foreach ($get_omzet_by_tahun as $a) {
        $bulan[] = $a->bulan;   
        $omzet_tahun[] = $a->omzet;
    }

    foreach ($get_omzet_by_tahun_current as $a) {
        $bulan[] = $a->bulan;   
        $omzet_tahun_current[] = $a->omzet;
    }
    
    foreach ($get_omzet_by_tahun_old as $a) {
        $bulan[] = $a->bulan;   
        $omzet_tahun_old[] = $a->omzet;
    }
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const earning = document.getElementById('earning');

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

