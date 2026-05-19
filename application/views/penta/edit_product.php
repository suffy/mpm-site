<div class="az-content">
<div class="container-fluid">

<?= $this->load->view('penta/component/sidebar');?>

<div class="az-content-body pd-lg-l-40">

<h3><?= $title; ?></h3>

<form method="post" action="<?= base_url($url); ?>">

<div class="card p-4">

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
<div class="row">

    <!-- KOLOM KIRI -->
    <div class="col-md-6">

        <div class="form-group">
            <label>Kode Produk Penta</label>
            <input type="text" 
                   name="kode_produk_penta" 
                   class="form-control"
                   value="<?= $row->kode_produk_penta ?>"disabled>
        </div>

        <div class="form-group">
            <label>Item ID Vend Penta</label>
            <input type="text" 
                   name="item_id_vend_penta" 
                   class="form-control"
                   value="<?= $row->item_id_vend_penta ?>" disabled>
        </div>

        <div class="form-group">
            <label>Nama Produk Penta</label>
            <input type="text" 
                   name="nama_produk_penta" 
                   class="form-control"
                   value="<?= $row->nama_produk_penta ?>" disabled>
        </div>

        <div class="form-group">
            <label>UOM</label>
            <input type="text" 
                   name="uom" 
                   class="form-control"
                   value="<?= $row->uom ?>" disabled>
        </div>

    </div>

    <!-- KOLOM KANAN -->
    <div class="col-md-6">

        <div class="form-group">
            <label>Kode Produk MPM</label>
            <input type="text" 
                   name="kode_produk_mpm" 
                   class="form-control"
                   value="<?= $row->kode_produk_mpm ?>">
        </div>

        <div class="form-group">
            <label>Nama Produk MPM</label>
            <input type="text" 
                   name="nama_produk_mpm" 
                   class="form-control"
                   value="<?= $row->nama_produk_mpm ?>" disabled>
        </div>

        <div class="form-group">
            <label>Qty Konversi</label>
            <input type="number" 
                   name="qty" 
                   class="form-control"
                   value="<?= $row->qty ?>">
        </div>

        <div class="form-group">
            <label>Tabel</label>
            <input type="text" 
                   name="tabel" 
                   class="form-control"
                   value="<?= $row->tabel ?>"
                   readonly>
        </div>

    </div>

</div>

<hr>

<div class="text-right">

    <a href="<?= base_url('penta/request_sales'); ?>" 
       class="btn btn-secondary">
        Kembali
    </a>

    <button type="submit" 
            class="btn btn-primary">
        Update Product
    </button>

</div>

</div>

</form>

</div>
</div>
</div>