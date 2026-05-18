</div>

<style>
td.mepet-atas {
    vertical-align: top!important;
    padding-top: 10!important;
    padding-bottom: 0!important;
}
</style>

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
                <a href='<?= base_url("$url_back")?>' class="btn btn-dark">Kembali</a>
                <button class="btn btn-submit-cream" onclick="toggleForm()" id="button-form">Lihat Detail</button>
            </div>
        </div>

        <div class="row mt-3 mb-4" id="detail">
            <div class="col-md-12">
                <div class="row">
                    <h4 style="text-decoration: underline;">Detail</h4>
                    <div class="col-md-6 mt-2">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="site_code">No Tiket</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="site_code">: <?= $helpdesk->row()->no_tiket ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="site_code">Site Code</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="site_code">: <?= $helpdesk->row()->site_code ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="site_code">Sub Branch</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="site_code">: <?= $helpdesk->row()->nama_comp ?></label>
                                </div>
                            </div>
        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="supp">Principal</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="supp">: <?= $helpdesk->row()->namasupp ?></label>
                                </div>
                            </div>
        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="surat_jalan">Surat Jalan</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="surat_jalan">: <?= $helpdesk->row()->surat_jalan ?></label>
                                </div>
                            </div>
        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="ekspedisi">Ekspedisi</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="surat_jalan">: <?= $helpdesk->row()->ekspedisi ?></label>
                                </div>
                            </div>
        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="pic">PIC</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="surat_jalan">: <?= $helpdesk->row()->pic ?></label>
                                </div>
                            </div>
        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="telp">No. Telpon</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="surat_jalan">: <?= $helpdesk->row()->telp ?></label>
                                </div>
                            </div>
        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="email">Email</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="surat_jalan">: <?= $helpdesk->row()->email ?></label>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="kategori">Kategori</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="surat_jalan" style="text-transform: capitalize;">: <?= $helpdesk->row()->nama_kategori ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="card">
                <div class="table-responsive">
                    <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th style="text-align: center;" >Pesan</th>
                            <th style="text-align: center;" >Attachment</th>
                            <th style="text-align: center;" >Video</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($helpdesk_detail->result() as $key): ?>
                            <tr>
                                <td style="width: 10%;" class="mepet-atas"><?= $key->username; ?></td>
                                <td style="width: 50%;" class="mepet-atas"><?= $key->pesan; ?></td>
                                <td style="width: 10%;" class="mepet-atas">
                                    <?php if ($key->file_berita) { ?>
                                        <a href="<?= base_url() . 'assets/uploads/helpdesk/' . $key->file_berita; ?>">
                                            <button class="btn btn-submit pending-rilis-po btn-sm">Berita Acara</button>
                                        </a>
                                    <?php }?>
                                    <?php
                                        if ($key->file_attachment) {
                                            // Decode JSON jadi array PHP
                                            $dataArray = json_decode($key->file_attachment, true);
                                            $no = 1;

                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                // Loop array dan tampilkan
                                                foreach ($dataArray as $fileName) {
                                                    $link = base_url("assets/uploads/helpdesk/$fileName");
                                                    echo "
                                                    <a href='$link'>
                                                        <button class='btn btn-submit pending-rilis-po btn-sm'>Attachment $no</button>
                                                    </a>
                                                    ";
                                                    $no++;
                                                }
                                            }
                                        }
                                    ?>
                                </td>
                                <td style="width: 10%;">
                                    <?php 
                                        // echo $key->file_video;
                                        // echo base_url().'assets/uploads/helpdesk/video/'.$key->file_video; 
                                        if ($key->file_video) { ?>
                                            <video width="250" controls>
                                                <source src="<?= base_url().'assets/uploads/helpdesk/video/'.$key->file_video; ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php }else{ echo "none";}
                                    ?>
                                </td>
                                <td style="width: 10%;" class="mepet-atas"><?= $key->nama_status; ?></td>
                                <td style="width: 10%;" class="mepet-atas"><?= $key->created_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>

        

        <div class="row mt-3">
            <div class="card">
                <div class="col-md-12 ">
                    <?= form_open_multipart($url_input,['method' => 'post']); ?>
                    <div class="row mt-2">
                        <input type="text" name="id_helpdesk" class="form-control" id="id_helpdesk" value="<?= $helpdesk->row()->id ?>" hidden>
                        <input type="text" name="signature" class="form-control" id="signature" value="<?= $helpdesk->row()->signature ?>" hidden>
                            <div class="col-md-12">
                                <label for="pesan" >Pesan (*)</label>
                                <textarea type="text" class="form-control" rows="4" name="pesan" id="pesan" placeholder="Masukkan pesan anda disini..." required></textarea>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="status" >Status (*)</label>
                                <!-- <?php echo "username : ".$username; ?> -->
                                <select name="status" class="form-select" id="status" required>
                                    
                                    <?php if ($username == 'suffy' || $username == 'tria' || $username == 'melinda') {
                                        echo '
                                        <option value="">- Pilih -</option>
                                        <option value="0|Pending DP">Pending DP</option>
                                        <option value="1|Pending MPM">Pending MPM</option>
                                        <option value="2|Pending Principal">Pending Principal</option>
                                        <option value="3|Closed">Closed</option>
                                        ';
                                    } else {
                                        echo '
                                        <option value="1|Pending MPM">Pending MPM</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2 mb-3">
                            <div class="col-md-12">
                                <label for="video" >File Video</label>
                                <input type="file" name="video" class="form-control" id="video" >
                            </div>
                        </div>

                        <div class="row mt-2 mb-3">
                            <div class="col-md-12">
                                <label for="attachment" >File Tambahan (Opsional)</label>
                                <input type="file" name="attachments[]" class="form-control" id="attachment" >
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
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#detail').hide();
        $('#example').DataTable({
            searching: false,
            paging: false,
            scrollCollapse: true,
            scrollY: '300px',
            scrollX: true,
            ordering: true, // Aktifkan fitur urut
            order: [[5, 'asc']] // Urutkan berdasarkan kolom ke-0 (kolom pertama), secara descending
        });
    });
</script>


<script>
    function toggleForm() {
        var content = document.getElementById("detail");
        var button = document.getElementById("button-form");
        if (content.style.display === "none") {
            content.style.display = "block";
            button.textContent = "Close";
            button.classList.remove("btn-submit-cream");
            button.classList.add("btn-danger");
            
        } else {
            content.style.display = "none";
            button.textContent = "Lihat Detail";
            button.classList.remove("btn-danger");
            button.classList.add("btn-submit-cream");
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