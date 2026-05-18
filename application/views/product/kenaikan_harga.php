</div>
<div class="container-fluid mt-4">
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
        <button class="btn btn-danger" onclick="toggleForm()" id="button-form">Hide Form</button>
        <a href="<?= base_url().'products/report_product_nasional' ?>" class="btn btn-success">Report</a>
      </div>
    </div>

    <div class="row mt-3 mb-4" id="form-helpdesk">
      <div class="card">
        <h4 style="text-decoration: underline;">Form Pengajuan</h4>
        <div class="col-md-12 mt-4">
          <?= form_open_multipart($url_input,['method' => 'post']); ?>

            <div class="row mt-2">
              <div class="col-md-6">
                <label for="supp">Principal (*)</label>
                <select class="form-select" name="supp" id="supp" required>
                    <option value="">- Pilih Principal -</option>
                    <?php foreach ($get_principal->result() as $key) { ?>
                        <option value="<?= $key->supp?>"><?= $key->namasupp ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <label for="keterangan">Keterangan (*)</label>
                <textarea class="form-control" name="keterangan" id="keterangan" rows="4" placeholder="Masukan Keterangan" required></textarea>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <label for="keterangan">Memo ID (*)</label>
                <input type="text" class="form-control" name="memo_id" placeholder="masukkan memo id" required>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <label for="keterangan">Tgl Memo (*)</label>
                <input type="date" class="form-control" name="tgl_memo" required>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <label for="keterangan">Tgl Naik (*)</label>
                <input type="date" class="form-control" name="tgl_naik" required>
              </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="berita">File (*)</label>
                    <input type="file" name="file" class="form-control" id="file" required>
                    <span class="text-muted small">Jenis file : pdf, jpg, jpeg, png, .docx, .xlsx</span>
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
                    <button type="submit" class="btn btn-danger" style="height:44px;width:180px">Submit</button>
                </div>
            </div>
          <?= form_close(); ?>
        </div>
      </div>
    </div>

    <div class="row mt-4 mb-5">
        <table id="tabel-data" class="table-striped dataTable no-footer">    
            <thead>
                <tr>                
                    <th>No</th>
                    <th>Ticket</th>
                    <th>Principal</th>
                    <th>Keterangan</th>
                    <th>File</th>
                    <th>Created</th>
                    <th>Status (on duty)</th>
                    <th>Monitoring</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $no_urut = 1;
                foreach ($get_data->result() as $a) : 
                
            ?>
                <tr>
                    <td><?= $no_urut++; ?></td>
                    <td><?= $a->nomor_ticket ?></td>
                    <td><?= $a->namasupp ?></td>
                    <td><?= $a->keterangan ?></td>
                    <td><a href="<?= base_url() . "assets/uploads/kenaikan_harga/$a->file" ?>" target="_blank" class="btn btn-submit pending-rilis-po btn-sm" style="padding-top: 8px;">File</a>
                    <?php
                        if ($a->attachments) {
                            // Decode JSON jadi array PHP
                            $dataArray = json_decode($a->attachments, true);
                            $no = 1;

                            if (json_last_error() === JSON_ERROR_NONE) {
                                // Loop array dan tampilkan
                                foreach ($dataArray as $fileName) {
                                    $link = base_url("assets/uploads/kenaikan_harga/$fileName");
                                    echo "
                                    <a href='$link'>
                                        <button class='btn btn-submit pending-rilis-po btn-sm' target='_blank'>Attachment $no</button>
                                    </a>
                                    ";
                                    $no++;
                                }
                            }
                        }
                    ?>
                    </td>
                    <td><?= date("d-m-Y",strtotime($a->created_at))." (".$a->created_by_username.')'; ?></td>
                    <td>
                        <a href="<?= base_url().'products/kenaikan_harga_header/'.$a->signature ?>" class="btn btn-submit btn-sm" target="_blank" style="padding-top: 8px;"><?= $a->nama_status." (".$a->on_duty_username.')'; ?></a>
                    </td>
                    <td>
                        <a href="<?= base_url().'products/monitoring/'.$a->signature ?>" class="btn btn-submit-red btn-sm" target="_blank" style="padding-top: 8px;">click here</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
      
      
  </div>
</div>

<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            "pageLength": 10,
            "order": [[0, 'desc']]
        });
        $('#form-helpdesk').show();
    });
</script>

<script>
    function toggleForm() {
        var content = document.getElementById("form-helpdesk");
        var button = document.getElementById("button-form");
        if (content.style.display === "none") {
            content.style.display = "block";
            button.textContent = "Hide Form";
            button.classList.remove("btn-submit-cream");
            button.classList.add("btn-danger");
            
        } else {
            content.style.display = "none";
            button.textContent = "Show Form";
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
        label.classList.add("mt-2"); // Tambah class ke label

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