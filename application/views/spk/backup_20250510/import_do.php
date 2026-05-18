</div>
<div class="container-fluid">

    <div class="az-content">
        <div class="container-fluid">
            <?php
            if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul' || $this->session->userdata('username') == 'suffy') { ?>
                <?= $this->load->view('spk/component/sidebar_admin'); ?>
            <?php
            } else { ?>
                <?= $this->load->view('spk/component/sidebar'); ?>
            <?php
            }
            ?>

            <div class="az-content-body pd-lg-l-40 d-flex flex-column">
                <h2 id="form_spk"><?= $title; ?></h2>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if ($this->session->flashdata('pesan')) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $this->session->flashdata('pesan'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->flashdata('pesan_success')) { ?>
                            <div class="alert alert-success" role="alert">
                                <?= $this->session->flashdata('pesan_success'); ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-12 mt-4">
                        <button class="btn btn-submit-black" onclick="toggleInsert(this)">Insert DO</button>
                        <a href="<?= $url_delto; ?>" class="btn btn-submit-black">Go to Web Deltomed</a>
                        <a href='<?= base_url("$url_po_outstanding") ?>' class="btn btn-submit-black">PO Outstanding</a>
                    </div>
                </div>

                <div class="row mt-1" id="form_insert" style="display:none;">
                    <div class="col-md-6 mt-4">
                        <form action="<?= base_url($url_insert); ?>" method="post" enctype="multipart/form-data">
                            <label for="file">Pilih File :</label>
                            <input type="file" class="form-control" name="file" id="file">

                            <button class="btn btn-submit-black mt-3" type="submit">Submit</button>
                        </form>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 mt-4">
                        <h4>History Do Deltomed</h4>
                        <table id="tabel-produk">
                            <thead>
                                <tr>
                                    <th>Tanggal DO</th>
                                    <th>Total Unit DO</th>
                                    <th>Updated By</th>
                                    <th>Last Update</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($do_delto->result() as $key):?>
                                <tr>
                                    <td><?= $key->tgldo ?></td>
                                    <td><label class="btn btn-submit status pending-rilis-po" style="font-size:14px"><?= number_format($key->unit_do) ?></td>
                                    <td style="text-transform: capitalize;"><?= $key->username ?></td>
                                    <td><?= $key->lastupdate ?></td>
                                    <td><a href='<?= base_url("spk/export_do_deltomed/$key->tgldo")?>' type="button" class="btn btn-warning">Export</a></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleInsert(button) {
        const div = document.getElementById("form_insert");
        const isHidden = div.style.display === "none" || div.style.display === "";
        div.style.display = isHidden ? "block" : "none";
        button.textContent = isHidden ? "Close" : "Insert DO";
    }
</script>