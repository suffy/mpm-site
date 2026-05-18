<div class="az-content-left az-content-left-components">
    <div class="component-item">

        <a href="<?= base_url().'spk/dashboard' ?>" class="nav-link"><label><strong>Dashboard</label></strong></a>
        <nav class="nav flex-column"> 
            <a href="<?= base_url().'spk/list_order' ?>" class="nav-link-new" target="_blank">List Order</a>
            <a href="<?= base_url().'inventory/laporan_po' ?>" class="nav-link-new" target="_blank">Report PO</a>
            <a href="<?= base_url().'inventory/po_outstanding' ?>" class="nav-link-new" target="_blank">Report PO Outstanding</a>
            <a href="<?= base_url().'spk/po_outstanding' ?>" class="nav-link-new" target="_blank">Report PO Outstanding (new)</a>
        </nav>

        <label class="mt-4">SPK</label>
        <nav class="nav flex-column"> 
            <a href="<?= base_url().'spk/form_pesanan' ?>" class="nav-link-new">Input SPK</a>
            <a href="<?= base_url().'spk/keranjang_belanja' ?>" class="nav-link-new">Keranjang Belanja</a>
            <a href="<?= base_url().'inventory/konfirmasi_po' ?>" class="nav-link-new">Konfirmasi Terima PO</a>
            <a href="<?= base_url().'helpdesk' ?>" class="nav-link-new">Helpdesk Support</a>
        </nav>

        <label>Alokasi</label>
        <nav class="nav flex-column">
            <a href="<?= base_url().'spk/keranjang_alokasi' ?>" class="nav-link-new">Keranjang Alokasi</a>
            <a href="<?= base_url().'c_repl/form_repl' ?>" class="nav-link-new" target="_blank">Replineshment</a>
            <a href="<?= base_url().'spk/list_order' ?>" class="nav-link-new" target="_blank">List Order</a>
        </nav>

        <label>DC</label>
        <nav class="nav flex-column">
            <a href="<?= base_url().'dc/dashboard' ?>" class="nav-link-new" target="_blank">Dashboard</a>
        </nav>

        <label>Generate Data</label>
        <nav class="nav flex-column">                           
            <a href="<?= base_url().'spk/form_generate_average_sales' ?>" class="nav-link-new">Generate Average Sales DP</a>
            <a href="<?= base_url().'spk/form_generate_po_outstanding' ?>" class="nav-link-new">Generate PO Outstanding Daily</a>
        </nav>

        <label>Master Data</label>
        <nav class="nav flex-column">
            <a href="<?= base_url().'all_po/po_monitoring' ?>" class="nav-link-new">PO Monitoring</a>
            <a href="<?= base_url().'spk/master_produk' ?>" class="nav-link-new">Master Produk</a>
            <a href="<?= base_url().'spk/master_principal' ?>" class="nav-link-new">Master Principal</a>
            <a href="#" class="nav-link-new">Master DP</a>
            <a href="<?= base_url().'spk/purchase_plan' ?>" class="nav-link-new">Master Purchase Plan</a>
            <a href="<?= base_url().'spk/import_do' ?>" class="nav-link-new">Import DO (Batch)</a>
        </nav>
    </div>
</div>