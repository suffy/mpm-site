</div>
<div class="container-fluid">
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

        <div class="row mt-4 ">
            <div class="col-md">
                <button class="btn btn-submit-cream" onclick="toggleForm()" id="button-form">Form Helpdesk</button>
            </div>
        </div>

        <div class="row mt-3 mb-4" id="form-helpdesk">
            <div class="card">
                <h4 style="text-decoration: underline;">Form Helpdesk</h4>
                <div class="col-md-12 mt-4">
                    <?= form_open_multipart($url_input,['method' => 'post']); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="site_code">Sub Branch (*)</label>
                                <select class="form-select" name="site_code" id="site_code" required>
                                    <option value="">- Pilih DP -</option>
                                    <?php foreach ($site_code->result() as $key) { ?>
                                        <option value="<?= $key->site_code?>"><?= $key->nama_comp ." - ($key->site_code)" ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="supp">Principal (*)</label>
                                <select class="form-select" name="supp" id="supp" required>
                                    <option value="">- Pilih Principal -</option>
                                    <?php foreach ($get_principal->result() as $key) { ?>
                                        <option value="<?= $key->supp?>"><?= $key->namasupp ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="surat_jalan">Surat Jalan (*)</label>
                                <input type="text" class="form-control" name="surat_jalan" id="surat_jalan" placeholder="Masukan Nomor Surat Jalan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="ekspedisi">Ekspedisi (*)</label>
                                <input type="text" class="form-control" name="ekspedisi" id="ekspedisi" placeholder="Masukan Ekspedisi" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="pic">PIC (*)</label>
                                <input type="text" class="form-control" name="pic" id="pic" placeholder="Masukan Nama PIC" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="telp">Telp/Whatsapp (*)</label>
                                <input type="tel" class="form-control" name="telp" id="telp" placeholder="Masukan Telp/Whatsapp" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email (*)</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Masukan Email" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md">
                                <label for="kategori">Kategori (*)</label>
                                <select name="kategori" class="form-control" id="kategori" onchange="tampilkanDiv()" required>
                                    <option value="">- Pilih Kategori -</option>
                                    <option value="1">Barang Kurang</option>
                                    <option value="2">Barang Lebih</option>
                                    <option value="3">Barang Rusak</option>
                                    <option value="4">Lainnya</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-6" id="divMasalah">
                                <label for="masalah">Masalah (*)</label>
                                <input name="masalah" class="form-control" id="masalah" placeholder="Sampaikan masalah anda disini">
                            </div> -->
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="kronologis">Kronologis (*)</label>
                                <textarea type="text" class="form-control" rows="4" name="kronologis" id="kronologis" placeholder="Masukan kronologis anda disini" ></textarea>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="berita">File Berita Acara (*)</label>
                                <input type="file" name="berita" class="form-control" id="berita" required>
                            </div>
                            <div class="col-md-6">
                                <label for="video">File Video (*)</label>
                                <input type="file" name="video" class="form-control" id="video" required>
                            </div>
                        </div>

                        <div class="row mt-3 mb-3">
                            <div class="col-md-12" id="addFile">
                            </div>
                        </div>

                        <a type="button" style="color: red;" onclick="addFile()">+ Tambah File Attachment</a>

                        <hr>

                        <div class="row mt-4 mb-2" style="text-align: center">
                            <div class="col-md-12">
                                <?= form_submit('submit', 'Simpan', 'class="btn btn-submit"'); ?>
                            </div>
                        </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
        
        <div class="row mt-4 mb-5">
            <h4 style="text-decoration: underline;">Tabel Helpdesk</h4>
            <div class="form-inline row mt-3">
                <h5>Periode</h5>
                <div class="col-md-12">
                    <?= form_open($url_search, ['method' => 'post']);?>
                        From
                        <input class="form-control" type="date" name="from" value="<?= $search['from'] ?>"
                            required />
                        To
                        <input class="form-control" type="date" name="to" value="<?= $search['to'] ?>"
                            required />
                        <select name="status" class="form-control" required>
                            <option value="" <?= $search === Null ? 'selected' : '' ?>> - Pilih Status - </option>
                            <option value="0" <?= $search['status'] === '0'? 'selected' : '' ?>> Pending DP </option>
                            <option value="1" <?= $search['status'] === '1' ? 'selected' : '' ?>> Pending MPM </option>
                            <option value="2" <?= $search['status'] === '2' ? 'selected' : '' ?>> Pending Principal </option>
                            <option value="3" <?= $search['status'] === '3' ? 'selected' : '' ?>> Closed </option>
                            <option value="999" <?= $search['status'] === '999' ? 'selected' : '' ?>> All Status </option>
                        </select>
                        <button type="submit" value="1" class="btn btn-outline-danger btn-sm"
                            name="search">Search</button>
                        <button type="submit" value="2" class="btn btn-outline-danger btn-sm" name="type">Export To
                            CSV</button>
                        <a href="<?= base_url('spk/helpdesk') ?>" class="btn btn-outline-dark btn-sm">Reset</a>
                    <?= form_close();?>
                </div>
            </div>

            <div class="row mt-4">
                <table id="tabel-data" class="table-striped dataTable no-footer" style="text-transform: uppercase;">    
                    <thead>
                        <tr>                
                            <th style="text-align: center;">Status</th>
                            <th>No. Ticket</th>
                            <th>Principal</th>
                            <th>Subbranch</th>
                            <th>Kategori</th>    
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data_helpdesk->result() as $a) : ?>
                        <tr>
                            <td style="text-align: center;">
                                <?php $btn_class = ($a->status == 3) ? 'pending-rilis-po' : 'pending-finance' ; ?>
                                <!-- <button type="button" class="btn btn-submit btn-sm <?= $btn_class; ?>" role="button">
                                    <?= $a->nama_status;?>
                                </button> -->
                                <a href="<?= base_url() . "spk/helpdesk_detail/$a->signature"; ?>" type="button" class="btn btn-submit <?= $btn_class; ?> btn-sm" style="padding: 8px 5px 5px 10px;" target="_blank">
                                    <?= $a->nama_status;?>
                                </a>
                            </td>
                            <td>
                                <?= $a->no_tiket; ?>
                            </td>
                            <td>
                                <?= $a->namasupp; ?>
                            </td>
                            <td>
                                <?= $a->nama_comp; ?>
                            </td>
                            <td>
                                <?= $a->nama_kategori;?>
                            </td>
                            <td>
                                <?= date("d-m-Y",strtotime($a->created_at)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const masalah = document.getElementById("divMasalah");
        masalah.style.display = "none";
        $('#tabel-data').DataTable({
            "pageLength": 10,
            "order": [[0, 'asc']]
        });
        $('#form-helpdesk').hide();
    });
</script>

<script>
    function toggleForm() {
        var content = document.getElementById("form-helpdesk");
        var button = document.getElementById("button-form");
        if (content.style.display === "none") {
            content.style.display = "block";
            button.textContent = "Close Form";
            button.classList.remove("btn-submit-cream");
            button.classList.add("btn-danger");
            
        } else {
            content.style.display = "none";
            button.textContent = "Form Helpdesk";
            button.classList.remove("btn-danger");
            button.classList.add("btn-submit-cream");
        }
    }
</script>

<script>
    function tampilkanDiv() {
        const value = document.getElementById("kategori").value;
        const masalah = document.getElementById("divMasalah");
        const inputMasalah = document.getElementById("masalah");

        masalah.style.display = "none";
        inputMasalah.required = false;

        if (value == 4) {
            inputMasalah.required = true;
            masalah.style.display = "block";
        }
    }
</script>

<script>
    let counter = 1; // Keeps track of how many inputs have been added
    
    function addFile() {
        // 1. Create label
        const label = document.createElement("label");
        label.textContent = "File Tambahan (Opsional)";
        label.htmlFor = "attachment" + counter; // 'for' attribute links to input id
        label.classList.add("form-label"); // Tambah class ke label

        // 2. Create input
        const input = document.createElement("input");
        input.type = "file";
        input.id = "attachment" + counter;       // Must match label.htmlFor
        input.name = "attachments[]";          // Optional
        input.className = "form-control"; // Optional class attribute

        // 3. Append to container
        const container = document.getElementById("addFile");
        container.appendChild(label);
        container.appendChild(input);

        // 4. Optional: line break for spacing
        container.appendChild(document.createElement("br"));

        counter++; // Increment for the next pair
    }
</script>