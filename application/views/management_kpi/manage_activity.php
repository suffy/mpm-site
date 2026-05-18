</div>

<div class="container-fluid">

    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('management_kpi/component/sidebar');?>

                <div class="col">
                    <!-- event -->
                    <div class="row">
                        <?php echo form_open_multipart($url_event); ?>
                        <div class="col-md-12">
                            <div class="row" id="event">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Input Event Yang Terlaksana</h4>
                                </div>
                            </div>
                            <div class="row mt-3">
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
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Nama Event</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_event" id="nama_event" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Tanggal Event</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col">
                                            <label for="from" class="form-label">Mulai</label>
                                            <input type="datetime-local" name="from" id="from" class="form-control"
                                                required>
                                        </div>
                                        <div class="col">
                                            <label for="to" class="form-label">Selesai</label>
                                            <input type="datetime-local" name="to" id="to" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Lokasi Event</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="lokasi_event" id="lokasi_event"
                                        required>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-3">
                                    <label for="biaya">Biaya</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="biaya" id="biaya"
                                        onkeyup="keyupFunction()" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="omzet">Omzet</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="omzet" id="omzet"
                                        onkeyup="keyupFunction()" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="cost_ratio">Cost Ratio</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="cost_ratio" id="cost_ratio" readonly>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-3">
                                    <label for="crowd">Crowd</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="crowd" id="crowd">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="brand">Brand</label>
                                </div>
                                <div class="col-md-9">
                                    <select id="brand" name="brand" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p class="mt-2"><strong>Attachment</strong></p>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="nama_program">Proposal Referensi</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach1" name="attach1" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">foto</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach2" name="attach2" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">kpi event</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach3" name="attach3" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-submit-black" id="btnKirim"
                                        onclick="return button()">Submit Event</button>
                                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="table-event" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Status</th>
                                                <th>NoEvent</th>
                                                <th>Pelaksana</th>
                                                <th>NamaEvent</th>
                                                <th>Tanggal</th>
                                                <th>Lokasi</th>
                                                <th>Biaya</th>
                                                <th>Value</th>
                                                <th>Ratio</th>
                                                <th>Crowd</th>
                                                <th>Brand</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($get_event->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td>
                                                    <a href="<?= base_url().'kpi/verifikasi_event/'.$a->signature ?>"
                                                        class="btn btn-submit-black"><?= $a->nama_status ?></a>
                                                </td>
                                                <td><?= $a->no_pelaporan_event ?></td>
                                                <td><?= $a->name ?></td>
                                                <td><?= $a->nama_event ?></td>
                                                <td><?= $a->event_from.' - '.$a->event_to ?></td>
                                                <td><?= $a->lokasi_event ?></td>
                                                <td><?= number_format($a->biaya) ?></td>
                                                <td><?= number_format($a->omzet) ?></td>
                                                <td><?= round($a->cost_ratio,3) ?></td>
                                                <td><?= $a->crowd ?></td>
                                                <td><?= $a->brand ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <!-- end event -->

                    <hr>

                    <!-- pemerataan product -->
                    <div class="row mt-5">
                        <?php echo form_open_multipart($url_pemerataan); ?>
                        <div class="col-md-12">
                            <div class="row" id="pemerataan">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Input Pemeratan Product Non OB DP Yang Terlaksana</h4>
                                </div>
                            </div>
                            <div class="row mt-3">
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
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Tanggal</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col">
                                            <!-- <label for="from" class="form-label">Mulai</label> -->
                                            <input type="datetime-local" name="from" id="from" class="form-control"
                                                required>
                                        </div>
                                        <!-- <div class="col">
                                            <label for="to" class="form-label">Selesai</label>
                                            <input type="datetime-local" name="to" id="to" class="form-control"
                                                required>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Nama Toko</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_toko" id="nama_toko" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Alamat</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" rows="4" name="alamat" id="alamat"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Product Kompetitor <p style="color: grey;">(Opsional)</p></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" rows="4" name="product_kompetitor" id="product_kompetitor"></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Product Existing <p style="color: grey;">(Opsional)</p></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" rows="4" name="product_existing" id="product_existing"></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p class="mt-2"><strong>Attachment</strong></p>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="nama_program">Foto</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach1" name="attach1" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-submit-black" id="btnKirim"
                                        onclick="return button()">Submit Event</button>
                                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="table-pemerataan-product" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Status</th>
                                                <th>No Pemerataan Product</th>
                                                <th>Tanggal</th>
                                                <th>Pelaksana</th>
                                                <th>Nama Toko</th>
                                                <!-- <th>PIC Toko</th> -->
                                                <th>Alamat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($get_pemerataan_product->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><a href="<?= base_url().'kpi/verifikasi_pemerataan_product/'.$a->signature ?>"
                                                        class="btn btn-submit-black"><?= $a->nama_status ?></a></td>
                                                <td><?= $a->no_pelaporan; ?></td>
                                                <td><?= $a->tanggal ?></td>
                                                <td><?= $a->name ?></td>
                                                <td><?= $a->nama_toko ?></td>
                                                <!-- <td><?= $a->pic_toko ?></td> -->
                                                <td><?= $a->alamat ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <!-- end pemerataan product -->

                    <hr>

                    <!-- visibility -->
                    <div class="row mt-5">
                        <?php echo form_open_multipart($url_visibility); ?>
                        <div class="col-md-12">
                            <div class="row" id="visibility">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Input Visibility/Branding OB DP Yang Terlaksana</h4>
                                </div>
                            </div>
                            <div class="row mt-3">
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
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Tanggal</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col">
                                            <!-- <label for="from" class="form-label">Mulai</label> -->
                                            <input type="datetime-local" name="from" id="from" class="form-control"
                                                required>
                                        </div>
                                        <!-- <div class="col">
                                            <label for="to" class="form-label">Selesai</label>
                                            <input type="datetime-local" name="to" id="to" class="form-control"
                                                required>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Nama Toko</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_toko" id="nama_toko" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Alamat</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" rows="4" name="alamat" id="alamat"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Bentuk Branding</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="checkbox" id="brand1" name="brand[]" value="Flyer">
                                    <label for="brand1"> Flyer</label><br>
                                    <input type="checkbox" id="brand2" name="brand[]" value="Banner">
                                    <label for="brand2"> Banner</label><br>
                                    <input type="checkbox" id="brand3" name="brand[]" value="Spanduk">
                                    <label for="brand3"> Spanduk</label><br>
                                    <input type="checkbox" id="brand4" name="brand[]" value="Sticker">
                                    <label for="brand4"> Sticker</label><br>
                                    <input type="checkbox" id="brand5" name="brand[]" value="Other">
                                    <label for="brand5"> Other</label>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p class="mt-2"><strong>Attachment</strong></p>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="nama_program">Foto</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach1" name="attach1" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-submit-black" id="btnKirim"
                                        onclick="return button()">Submit Event</button>
                                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="table-visibility" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Status</th>
                                                <th>No Visibility</th>
                                                <th>Tanggal</th>
                                                <th>Pelaksana</th>
                                                <th>Nama Toko</th>
                                                <!-- <th>PIC Toko</th> -->
                                                <th>Alamat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($get_visibility->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><a href="<?= base_url().'kpi/verifikasi_visibility/'.$a->signature ?>"
                                                        class="btn btn-submit-black"><?= $a->nama_status ?></a></td>
                                                <td><?= $a->no_pelaporan; ?></td>
                                                <td><?= $a->tanggal ?></td>
                                                <td><?= $a->name ?></td>
                                                <td><?= $a->nama_toko ?></td>
                                                <!-- <td><?= $a->pic_toko ?></td> -->
                                                <td><?= $a->alamat ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <!-- end visibility -->

                    <hr>

                    <!-- surveyor -->
                    <div class="row mt-5">
                        <?php echo form_open_multipart($url_market_survey); ?>
                        <div class="col-md-12">
                            <div class="row" id="market_survey">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Input Market Survey Yang Terlaksana</h4>
                                </div>
                            </div>
                            <div class="row mt-3">
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
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Tanggal</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col">
                                            <!-- <label for="from" class="form-label">Mulai</label> -->
                                            <input type="datetime-local" name="from" id="from" class="form-control"
                                                required>
                                        </div>
                                        <!-- <div class="col">
                                            <label for="to" class="form-label">Selesai</label>
                                            <input type="datetime-local" name="to" id="to" class="form-control"
                                                required>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Nama Toko</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_toko" id="nama_toko" required>
                                </div>
                            </div>
                            <!-- <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">PIC Toko</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="pic_toko" id="pic_toko" required>
                                </div>
                            </div> -->
                            <!-- <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Area</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="area" id="area" required>
                                </div>
                            </div> -->
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Alamat</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" rows="4" name="alamat" id="alamat"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Hasil Market Survey</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" rows="4" name="keterangan"
                                        id="keterangan" required></textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p class="mt-2"><strong>Attachment</strong></p>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="nama_program">Foto</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach1" name="attach1" required>
                                </div>
                            </div>

                            <!-- <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Foto Visibility (Display)</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach2" name="attach2" required>
                                </div>
                            </div> -->

                            <!-- <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Rutinitas Distributor Sales</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control mb-2" id="attach3" name="attach3" required>
                                </div>
                            </div> -->

                            <div class="row mt-4">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-submit-black" id="btnKirim"
                                        onclick="return button()">Submit Event</button>
                                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="table-market-survey" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Status</th>
                                                <th>No Market Survey</th>
                                                <th>Tanggal</th>
                                                <th>Pelaksana</th>
                                                <th>Nama Toko</th>
                                                <!-- <th>PIC Toko</th> -->
                                                <th>Alamat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($get_market_survey->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><a href="<?= base_url().'kpi/verifikasi_market_survey/'.$a->signature ?>"
                                                        class="btn btn-submit-black"><?= $a->nama_status ?></a></td>
                                                <td><?= $a->no_pelaporan; ?></td>
                                                <td><?= $a->survey_from ?></td>
                                                <td><?= $a->name ?></td>
                                                <td><?= $a->nama_toko ?></td>
                                                <!-- <td><?= $a->pic_toko ?></td> -->
                                                <td><?= $a->alamat ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <!-- end surveyor -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $(".btn-loading").hide();
        $('#table-event').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#table-pemerataan-product').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#table-visibility').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#table-market-survey').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("management_claim/master_user_mpm") ?>',
        data: '',
        success: function (result) {
            $("select[name = user_event]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("kpi/master_user_event") ?>',
        data: '',
        success: function (result) {
            $("select[name = user_event_terdaftar]").html(result);
            $("select[name = pic_approval]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("kpi/master_brand") ?>',
        data: '',
        success: function (result) {
            $("select[name = brand]").html(result);
        }
    });

    function keyupFunction() {
        var biaya = document.getElementById('biaya').value;
        var omzet = document.getElementById('omzet').value;
        var cost_ratio = document.getElementById('cost_ratio').value;
        var result = biaya / omzet;

        document.getElementById("cost_ratio").value = result;
    }
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>