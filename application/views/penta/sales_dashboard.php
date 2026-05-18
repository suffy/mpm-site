<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">
        <?= $this->load->view('penta/component/sidebar');?>

<div class="az-content-body pd-lg-l-40 d-flex flex-column">
  <h2 id="form_spk"><?= $title; ?></h2>
  <div class="row">
    <div class="col-md-12">
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

    <div class="row mt-1">
        <div class="col-md-12">

            <?php echo form_open($url); ?>

            <div class="row mt-3">
                <div class="col-lg-2">
                    <label for="supp">Bulan</label> 
                </div>
                <div class="col-lg-4">
                    <input type="month" name="bulan" class="form-control" value=<?= $pilih_bulan; ?>>
                </div>
                <div class="col-lg-4">
                    <!-- <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Show Data</button> -->
                    <button class="pastel-orange-btn" id="btnKirim" onclick="return button()">Show Data</button>
                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>            
        </div>
    </div>

    <div class="row mt-5">

      <main class="main">
        <section class="widget">
          <h3>Sales in Last 10 Days</h3>
          <?php 
          foreach ($get_summary_sales_by_tanggal->result() as $p) : ?>
          <div class="metric">
              <span class="label"><?= $p->tanggal_invoice ?></span>
              <span class="value"><?= number_format($p->total_net,0) ?></span>
          </div>
          <?php endforeach; ?>
        </section>

        <section class="widget">
          <h3>Top 10 Products Net</h3>
          <?php 
          foreach ($get_summary_sales_by_product->result() as $p) : ?>
          <div class="metric">
              <span class="label"><?= substr($p->namaprod,0,18) ?>..</span>
              <span class="value"><?= number_format($p->total_net,0) ?></span>
          </div>
          <?php endforeach; ?>
        </section>

        <section class="widget">
          <h3>Top 10 Outlet Net</h3>
          <?php 
          foreach ($get_summary_sales_by_outlet->result() as $p) : ?>
          <div class="metric">
              <span class="label"><?= substr($p->nama_outlet,0,18) ?>..</span>
              <span class="value"><?= number_format($p->total_net,0) ?></span>
          </div>
          <?php endforeach; ?>
          </section>
      </main>

    </div>

    <div class="row mt-5">
      <div class="col-md-12">
        <h3>Detail Sales</h3>
      </div>
      <div class="col-md-12 mt-2">
        <!-- <a href="" class="export-excel-btn" onclick="convertTable()">Export to Excel</a> -->
        <button type="button" class="export-excel-btn" onclick="convertTable()">Export to Excel</button>
      </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
          <table id="tabel" class="display table-striped table-bordered" style="width: 100%;">
                <thead>
                    <tr>
                        <th colspan="5" class="text-center"> -- Sales By AreaID -- </th>
                    </tr>
                    <tr>
                        <!-- <th>id</th>
                        <th>id_log</th>
                        <th>bulan</th>
                        <th>tahun</th>
                        <th>principal_id</th>
                        <th>area_id</th>
                        <th>nama_area</th>
                        <th>tanggal_invoice</th>
                        <th>nomor_invoice</th>
                        <th>nomor_sales_order</th>
                        <th>customer_po_number</th>
                        <th>kode_outlet</th>
                        <th>kode_outlet_lama</th>
                        <th>nama_outlet</th>
                        <th>category_produk</th>
                        <th>sales_order_line</th>
                        <th>kode_produk</th>
                        <th>kode_produk_lama</th>
                        <th>inventory_item_id</th>
                        <th>item_id_vend</th>
                        <th>id_item_sapora</th>
                        <th>category_product_principal</th>
                        <th>nama_produk</th>
                        <th>qty</th>
                        <th>uom</th>
                        <th>price</th>
                        <th>total_disc</th>
                        <th>total_vat</th>
                        <th>total_gross</th>
                        <th>total_net</th>
                        <th>bonus</th>
                        <th>discount_value_distributor</th>
                        <th>discount_value_prinsipal</th>
                        <th>discount_value_extra</th>
                        <th>discount_persen_distributor</th>
                        <th>discount_persen_prinsipal</th>
                        <th>discount_persen_extra</th>
                        <th>nomor_discount_distributor</th>
                        <th>nomor_discount_prinsipal</th>
                        <th>nomor_discount_extra</th>
                        <th>type_data</th>
                        <th>nama_sales</th>
                        <th>batch</th>
                        <th>type_promo</th>
                        <th>keterangan_promo</th>
                        <th>created_at</th>
                        <th>created_by</th>
                        <th>signature</th> -->
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Area ID</th>
                        <th>Nama Area</th>
                        <th>Total Gross</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $p->tahun ?></td>
                        <td><?= $p->bulan ?></td>
                        <td><?= $p->area_id ?></td>
                        <td><?= $p->nama_area ?></td>
                        <td><?= $p->total_gross ?></td>
                        <!-- <td><?= $p->id ?></td>
                        <td><?= $p->id_log ?></td>
                        <td><?= $p->bulan ?></td>
                        <td><?= $p->tahun ?></td>
                        <td><?= $p->principal_id ?></td>
                        <td><?= $p->area_id ?></td>
                        <td><?= $p->nama_area ?></td>
                        <td><?= $p->tanggal_invoice ?></td>
                        <td><?= $p->nomor_invoice ?></td>
                        <td><?= $p->nomor_sales_order ?></td>
                        <td><?= $p->customer_po_number ?></td>
                        <td><?= $p->kode_outlet ?></td>
                        <td><?= $p->kode_outlet_lama ?></td>
                        <td><?= $p->nama_outlet ?></td>
                        <td><?= $p->category_produk ?></td>
                        <td><?= $p->sales_order_line ?></td>
                        <td><?= $p->kode_produk ?></td>
                        <td><?= $p->kode_produk_lama ?></td>
                        <td><?= $p->inventory_item_id ?></td>
                        <td><?= $p->item_id_vend ?></td>
                        <td><?= $p->id_item_sapora ?></td>
                        <td><?= $p->category_product_principal ?></td>
                        <td><?= $p->nama_produk ?></td>
                        <td><?= $p->qty ?></td>
                        <td><?= $p->uom ?></td>
                        <td><?= $p->price ?></td>
                        <td><?= $p->total_disc ?></td>
                        <td><?= $p->total_vat ?></td>
                        <td><?= $p->total_gross ?></td>
                        <td><?= $p->total_net ?></td>
                        <td><?= $p->bonus ?></td>
                        <td><?= $p->discount_value_distributor ?></td>
                        <td><?= $p->discount_value_prinsipal ?></td>
                        <td><?= $p->discount_value_extra ?></td>
                        <td><?= $p->discount_persen_distributor ?></td>
                        <td><?= $p->discount_persen_prinsipal ?></td>
                        <td><?= $p->discount_persen_extra ?></td>
                        <td><?= $p->nomor_discount_distributor ?></td>
                        <td><?= $p->nomor_discount_prinsipal ?></td>
                        <td><?= $p->nomor_discount_extra ?></td>
                        <td><?= $p->type_data ?></td>
                        <td><?= $p->nama_sales ?></td>
                        <td><?= $p->batch ?></td>
                        <td><?= $p->type_promo ?></td>
                        <td><?= $p->keterangan_promo ?></td>
                        <td><?= $p->created_at ?></td>
                        <td><?= $p->created_by ?></td>
                        <td><?= $p->signature ?></td> -->
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>

<script>
$(document).ready(function () {
    $('#tabel').DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [0, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        scrollX: true,
    });
});
</script>

<script>
    function button()
    {
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>