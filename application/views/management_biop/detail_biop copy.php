<?php 
    $file = base_url().'assets/uploads/signature/';
?>
<div class="container-fluid">
    <div class="col-md">
        <!-- Header dengan tombol export -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h3 class="page-title"><?= $title ?></h3>
            </div>
            <div class="col-md-4 text-end">
                <button id="exportExcel" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
                <button id="exportPdf" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </button>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if ($this->session->flashdata('pesan')): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <?= $this->session->flashdata('pesan'); ?>
            </div>
        <?php elseif ($this->session->flashdata('pesan_success')): ?>
            <div class="alert alert-success mb-4" role="alert">
                <?= $this->session->flashdata('pesan_success'); ?>
            </div>
        <?php endif; ?>

        <!-- Tabs Navigation -->
        <nav class="mb-4">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-biop-tab" data-toggle="tab" data-target="#nav-biop"
                    type="button" role="tab" aria-controls="nav-biop" aria-selected="true">Biop</button>
                <button class="nav-link" id="nav-jamuan-tab" data-toggle="tab" data-target="#nav-jamuan" type="button"
                    role="tab" aria-controls="nav-jamuan" aria-selected="false">Jamuan</button>
                <button class="nav-link" id="nav-pengeluaran-tab" data-toggle="tab" data-target="#nav-pengeluaran"
                    type="button" role="tab" aria-controls="nav-pengeluaran" aria-selected="false">Bukti Pengeluaran</button>

            </div>
        </nav>

        <!-- Tab Content -->
        <div class="tab-content" id="nav-tabContent">
            <!-- BIOP Tab -->
            <div class="tab-pane fade show active" id="nav-biop" role="tabpanel" aria-labelledby="nav-biop-tab">
                <div class="card" id="biop-content">
                    <div class="card-body">
                        <!-- Header Info -->
                        <div class="text-center mb-4">
                            <h4>PT Mulia Putra Mandiri</h4>
                            <h5 class="text-muted">Form Biaya Operasional</h5>
                        </div>

                        <!-- User Info -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p><strong>Nama:</strong> <?= $get_biop->pic_name; ?></p>
                                <p><strong>Jabatan:</strong> <?= $get_biop->jabatan; ?></p>
                                <p><strong>Periode:</strong> <?= date('d F Y', strtotime($get_biop->from)) .' - '. date('d F Y', strtotime($get_biop->to)); ?></p>
                            </div>
                        </div>

                        <!-- Expenses Table -->
                        <div class="table-responsive">
                            <table class="table table-sm table-striped" id="biop-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Tanggal</th>
                                        <th rowspan="2">Keterangan</th>
                                        <th rowspan="2">Tol</th>
                                        <th rowspan="2">Parkir</th>
                                        <th colspan="3" class="text-center">BBM</th>
                                        <th rowspan="2">Makan</th>
                                        <th rowspan="2">Jamuan</th>
                                        <th rowspan="2">Meeting</th>
                                        <th rowspan="2">Perjalanan Dinas</th>
                                        <th rowspan="2">Service</th>
                                        <th rowspan="2">Lain</th>
                                        <th rowspan="2">Total</th>
                                        <th rowspan="2">Tempat</th>
                                    </tr>
                                    <tr>
                                        <th>KM</th>
                                        <th>Liter</th>
                                        <th>Rp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_tol = 0; 
                                    $total_parkir = 0; 
                                    $total_bbm = 0; 
                                    $total_makan = 0;
                                    $total_jamuan = 0;
                                    $total_meeting = 0;
                                    $total_perjalanan_dinas = 0;
                                    $total_service_kendaraan = 0;
                                    $total_lain = 0;
                                    $grand_total = 0;

                                    foreach ($get_data_biop_grouped as $row):
                                        $total = $row->tol + $row->parkir + $row->bbm_rp + $row->makan + $row->jamuan + $row->meeting + $row->perjalanan_dinas + $row->service_kendaraan + $row->lain ;
                                        $total_tol += $row->tol;
                                        $total_parkir += $row->parkir;
                                        $total_bbm += $row->bbm_rp;
                                        $total_makan += $row->makan;
                                        $total_jamuan += $row->jamuan;
                                        $total_meeting += $row->meeting;
                                        $total_perjalanan_dinas += $row->perjalanan_dinas;
                                        $total_service_kendaraan += $row->service_kendaraan;
                                        $total_lain += $row->lain;
                                        $grand_total += $total;
                                    ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($row->tanggal)) ?></td>
                                        <td class="text-start"><?= $row->keterangan_biaya ?></td>
                                        <td><?= $row->tol ? number_format($row->tol) : '-' ?></td>
                                        <td><?= $row->parkir ? number_format($row->parkir) : '-' ?></td>
                                        <td><?= $row->bbm_km ?: '-' ?></td>
                                        <td><?= $row->bbm_liter ?: '-' ?></td>
                                        <td><?= $row->bbm_rp ? number_format($row->bbm_rp) : '-' ?></td>
                                        <td><?= $row->makan ? number_format($row->makan) : '-' ?></td>
                                        <td><?= $row->jamuan ? number_format($row->jamuan) : '-' ?></td>
                                        <td><?= $row->meeting ? number_format($row->meeting) : '-' ?></td>
                                        <td><?= $row->perjalanan_dinas ? number_format($row->perjalanan_dinas) : '-' ?></td>
                                        <td><?= $row->service_kendaraan ? number_format($row->service_kendaraan) : '-' ?></td>
                                        <td><?= $row->lain ? number_format($row->lain) : '-' ?></td>
                                        <td><strong><?= number_format($total) ?></strong></td>
                                        <td><?= ucfirst($row->keterangan_tempat) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td><strong><?= number_format($total_tol) ?></strong></td>
                                        <td><strong><?= number_format($total_parkir) ?></strong></td>
                                        <td></td>
                                        <td></td>
                                        <td><strong><?= number_format($total_bbm) ?></strong></td>
                                        <td><strong><?= number_format($total_makan) ?></strong></td>
                                        <td><strong><?= number_format($total_jamuan) ?></strong></td>
                                        <td><strong><?= number_format($total_meeting) ?></strong></td>
                                        <td><strong><?= number_format($total_perjalanan_dinas) ?></strong></td>
                                        <td><strong><?= number_format($total_service_kendaraan) ?></strong></td>
                                        <td><strong><?= number_format($total_lain) ?></strong></td>
                                        <td><strong><?= number_format($grand_total) ?></strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Signatures -->
                        <div class="row mt-5">
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>PIC</strong></p>
                                <img src="<?= $file.$get_biop->digital_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $pic ? $pic->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->created_at)); ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Admin BIOP</strong></p>
                                <?php 
                                    if($get_biop->admin_claim_signature){ ?>
                                        <img src="<?= $file.$get_biop->admin_claim_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        <p class="mt-2 small">
                                            Nama: <?= $admin_claim ? $admin_claim->username : '-' ?><br>
                                            Tanggal: <?= date('d M Y', strtotime($get_biop->admin_claim_at)); ?>
                                        </p>
                                    <?php
                                    }
                                ?>                                
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>ATASAN 1</strong></p>
                                <?php 
                                    if($get_biop->atasan1_signature){ ?>
                                        <img src="<?= $file.$get_biop->atasan1_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        <p class="mt-2 small">
                                            Nama: <?= $atasan1 ? $atasan1->username : '-' ?><br>
                                            Tanggal: <?= date('d M Y', strtotime($get_biop->atasan1_at)); ?>
                                        </p>
                                    <?php
                                    }
                                ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>ATASAN 2</strong></p>
                                <?php 
                                    if($get_biop->atasan2_signature){ ?>
                                        <img src="<?= $file.$get_biop->atasan2_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        <p class="mt-2 small">
                                            Nama: <?= $atasan2 ? $atasan2->username : '-' ?><br>
                                            Tanggal: <?= date('d M Y', strtotime($get_biop->atasan2_at)); ?>
                                        </p>
                                    <?php
                                    }
                                ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Finance</strong></p>

                                <?php 
                                if($get_biop->admin_finance_signature){ ?>                                        
                                    <img src="<?= $file.$get_biop->admin_finance_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                    <p class="mt-2 small">
                                        Nama: <?= $admin_finance ? $admin_finance->username : '-' ?><br>
                                        Tanggal: <?= date('d M Y', strtotime($get_biop->admin_finance_at)); ?>
                                    </p>
                                <?php
                                } ?>

                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Head Finance</strong></p>
                                <?php
                                if($get_biop->head_finance_signature){ ?>
                                    <img src="<?= $file.$get_biop->head_finance_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                    <p class="mt-2 small">
                                        Nama: <?= $head_finance ? $head_finance->username : '-' ?><br>
                                        Tanggal: <?= date('d M Y', strtotime($get_biop->head_finance_at)); ?>
                                    </p>
                                <?php
                                }
                                ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jamuan Tab -->
            <div class="tab-pane fade" id="nav-jamuan" role="tabpanel" aria-labelledby="nav-jamuan-tab">
                <div class="card" id="jamuan-content">
                    <div class="card-body">
                        <!-- Header Info -->
                        <div class="text-center mb-4">
                            <h4>PT Mulia Putra Mandiri</h4>
                            <p class="text-muted">Gedung Mahitala LT 7. Jl. Alam Utama no 6. Serpong. Tangerang Selatan</p>
                        </div>

                        <!-- User Info -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p><strong>Nama:</strong> <?= $get_biop->pic_name; ?></p>
                                <p><strong>Jabatan:</strong> <?= $get_biop->jabatan; ?></p>
                                <p><strong>Periode:</strong> <?= date('d F Y', strtotime($get_biop->from)) .' - '. date('d F Y', strtotime($get_biop->to)); ?></p>
                            </div>
                        </div>

                        <!-- Perjamuan Table -->
                        <div class="table-responsive mb-4">
                            <h5 class="text-center mb-3">Perjamuan</h5>
                            <table class="table table-sm table-striped" id="perjamuan-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Tempat</th>
                                        <th>Alamat</th>
                                        <th>Jenis Jamuan</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($get_data_biop as $biop) :
                                        if ($biop->nama_kategori == 'jamuan') { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $biop->tanggal; ?></td>
                                            <td><?= $biop->jamuan_tempat; ?></td>
                                            <td><?= $biop->jamuan_alamat; ?></td>
                                            <td><?= $biop->jamuan_jenis; ?></td>
                                            <td><?= $biop->biaya_head_finance; ?></td>
                                        </tr>
                                    <?php }
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Relasi Table -->
                        <div class="table-responsive">
                            <h5 class="text-center mb-3">Relasi Yang Dijamu</h5>
                            <table class="table table-sm table-striped" id="relasi-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Nama Yang Dijamu</th>
                                        <th>Jabatan</th>
                                        <th>Jenis Usaha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($get_data_biop as $biop) :
                                        if ($biop->nama_kategori == 'jamuan') { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $biop->jamuan_nama_perusahaan; ?></td>
                                            <td><?= $biop->jamuan_pic; ?></td>
                                            <td><?= $biop->jamuan_pic_jabatan; ?></td>
                                            <td><?= $biop->jamuan_jenis_perusahaan; ?></td>
                                        </tr>
                                    <?php }
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Signatures -->
                        <div class="row mt-5">
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>PIC</strong></p>
                                <img src="<?= $file.$get_biop->digital_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $pic ? $pic->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->created_at)); ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Admin BIOP</strong></p>
                                <img src="<?= $file.$get_biop->admin_claim_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $admin_claim ? $admin_claim->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->admin_claim_at)); ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>ATASAN 1</strong></p>
                                <img src="<?= $file.$get_biop->atasan1_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $atasan1 ? $atasan1->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->atasan1_at)); ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>ATASAN 2</strong></p>
                                <img src="<?= $file.$get_biop->atasan2_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $atasan2 ? $atasan2->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->atasan2_at)); ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Admin Finance</strong></p>
                                <img src="<?= $file.$get_biop->admin_finance_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $admin_finance ? $admin_finance->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->admin_finance_at)); ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Head Finance</strong></p>
                                <img src="<?= $file.$get_biop->head_finance_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $head_finance ? $head_finance->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->head_finance_at)); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengeluaran Tab -->
            <div class="tab-pane fade" id="nav-pengeluaran" role="tabpanel" aria-labelledby="nav-pengeluaran-tab">
                <div class="card" id="pengeluaran-content">
                    <div class="card-body">

                        <div class="text-center mb-4">
                            <h4>BUKTI PENGELUARAN</h4>
                            <p class="text-muted">PT Mulia Putra Mandiri</p>
                        </div>

                        <!-- INFORMASI HEADER -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Nama:</strong> <?= $get_biop->pic_name; ?></p>
                                <p><strong>Jabatan:</strong> <?= $get_biop->jabatan; ?></p>
                                <p><strong>Periode:</strong> <?= date('d F Y', strtotime($get_biop->from)) .' - '. date('d F Y', strtotime($get_biop->to)); ?></p>
                            </div>
                        </div>

                        <!-- TABEL PENGELUARAN -->
                        <div class="table-responsive tabel-pengeluaran-container">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Uraian</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_pengeluaran = 0;

                                    // ambil data kategori + keterangan dari query SUM + GROUP_CONCAT
                                    $pengeluaran = $get_pengeluran_biop; // variabel dari controller

                                    $list_kategori = [
                                        'Biaya BBM/Tol/Parkir' => ['jumlah' => $pengeluaran->biaya_bbm, 'keterangan' => $pengeluaran->ket_bbm],
                                        'Biaya Jamuan' => ['jumlah' => $pengeluaran->biaya_jamuan, 'keterangan' => $pengeluaran->ket_jamuan],
                                        'Biaya Meeting' => ['jumlah' => $pengeluaran->biaya_meeting, 'keterangan' => $pengeluaran->ket_meeting],
                                        'Biaya Perjalanan Dinas (Transportasi, Hotel, Uang Makan)' => ['jumlah' => $pengeluaran->biaya_perjalanan_dinas, 'keterangan' => $pengeluaran->ket_perjalanan_dinas],
                                        'Biaya Service Kendaraan' => ['jumlah' => $pengeluaran->biaya_service_kendaraan, 'keterangan' => $pengeluaran->ket_service_kendaraan],
                                        'Biaya Stationery' => ['jumlah' => $pengeluaran->biaya_stationery, 'keterangan' => $pengeluaran->ket_stationery],
                                        'Biaya Lain-lain' => ['jumlah' => $pengeluaran->biaya_lain_lain, 'keterangan' => $pengeluaran->ket_lain_lain],
                                    ];

                                    foreach ($list_kategori as $uraian => $data_kategori) :
                                        $total_pengeluaran += $data_kategori['jumlah'];
                                    ?>
                                    <tr>
                                        <td><?= $uraian ?></td>
                                        <td>
                                            <?php if (!empty($data_kategori['keterangan'])): ?>
                                                <?= nl2br($data_kategori['keterangan']) ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end"><?= number_format($data_kategori['jumlah']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <td><strong>Total</strong></td>
                                        <td></td>
                                        <td class="text-end"><strong><?= number_format($total_pengeluaran) ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Signatures -->
                        <div class="row mt-5">
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>PIC</strong></p>
                                <img src="<?= $file.$get_biop->digital_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                <p class="mt-2 small">
                                    Nama: <?= $pic ? $pic->username : '-' ?><br>
                                    Tanggal: <?= date('d M Y', strtotime($get_biop->created_at)); ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Admin BIOP</strong></p>
                                <?php 
                                    if($get_biop->admin_claim_signature){ ?>
                                        <img src="<?= $file.$get_biop->admin_claim_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        <p class="mt-2 small">
                                            Nama: <?= $admin_claim ? $admin_claim->username : '-' ?><br>
                                            Tanggal: <?= date('d M Y', strtotime($get_biop->admin_claim_at)); ?>
                                        </p>
                                    <?php
                                    }
                                ?>                                
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>ATASAN 1</strong></p>
                                <?php 
                                    if($get_biop->atasan1_signature){ ?>
                                        <img src="<?= $file.$get_biop->atasan1_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        <p class="mt-2 small">
                                            Nama: <?= $atasan1 ? $atasan1->username : '-' ?><br>
                                            Tanggal: <?= date('d M Y', strtotime($get_biop->atasan1_at)); ?>
                                        </p>
                                    <?php
                                    }
                                ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>ATASAN 2</strong></p>
                                <?php 
                                    if($get_biop->atasan2_signature){ ?>
                                        <img src="<?= $file.$get_biop->atasan2_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        <p class="mt-2 small">
                                            Nama: <?= $atasan2 ? $atasan2->username : '-' ?><br>
                                            Tanggal: <?= date('d M Y', strtotime($get_biop->atasan2_at)); ?>
                                        </p>
                                    <?php
                                    }
                                ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Finance</strong></p>

                                <?php 
                                if($get_biop->admin_finance_signature){ ?>                                        
                                    <img src="<?= $file.$get_biop->admin_finance_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                    <p class="mt-2 small">
                                        Nama: <?= $admin_finance ? $admin_finance->username : '-' ?><br>
                                        Tanggal: <?= date('d M Y', strtotime($get_biop->admin_finance_at)); ?>
                                    </p>
                                <?php
                                } ?>

                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Head Finance</strong></p>
                                <?php
                                if($get_biop->head_finance_signature){ ?>
                                    <img src="<?= $file.$get_biop->head_finance_signature ?>" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                    <p class="mt-2 small">
                                        Nama: <?= $head_finance ? $head_finance->username : '-' ?><br>
                                        Tanggal: <?= date('d M Y', strtotime($get_biop->head_finance_at)); ?>
                                    </p>
                                <?php
                                }
                                ?>
                                
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Membuat PDF...</p>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan library yang diperlukan -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Tambahkan library SheetJS untuk export Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
// Inisialisasi jsPDF
const { jsPDF } = window.jspdf;

// Fungsi untuk export Excel
document.getElementById('exportExcel').addEventListener('click', function() {
    // Tentukan konten mana yang akan di-export berdasarkan tab aktif
    const activeTab = document.querySelector('.tab-pane.active');
    // const fileName = activeTab.id === 'nav-biop' ? 'Laporan_BIOP' : 'Laporan_Jamuan';
    let fileName = '';

    if (activeTab.id === 'nav-biop') {
        fileName = 'Laporan_BIOP';
    } 
    else if (activeTab.id === 'nav-jamuan') {
        fileName = 'Laporan_Jamuan';
    } 
    else if (activeTab.id === 'nav-pengeluaran') {
        fileName = 'Laporan_Bukti_Pengeluaran';
    }
    
    if (activeTab.id === 'nav-biop') {
    exportBiopToExcel(fileName);
    } 
    else if (activeTab.id === 'nav-jamuan') {
        exportJamuanToExcel(fileName);
    }
    else if (activeTab.id === 'nav-pengeluaran') {
        exportPengeluaranToExcel(fileName);
    }

});

// Fungsi untuk export BIOP ke Excel
function exportBiopToExcel(fileName) {
    // Buat workbook baru
    const wb = XLSX.utils.book_new();
    
    // Data header perusahaan dan informasi
    const headerData = [
        ['PT Mulia Putra Mandiri'],
        ['Form Biaya Operasional'],
        [],
        ['Nama:', '<?= $get_biop->pic_name; ?>'],
        ['Jabatan:', '<?= $get_biop->jabatan; ?>'],
        ['Periode:', '<?= date('d F Y', strtotime($get_biop->from)) .' - '. date('d F Y', strtotime($get_biop->to)); ?>'],
        []
    ];
    
    // Buat data Excel secara manual untuk mendapatkan data mentah
    const excelData = [];
    
    // Header tabel
    excelData.push(['Tanggal', 'Keterangan', 'Tol', 'Parkir', 'BBM KM', 'BBM Liter', 'BBM Rp', 'Makan', 'Jamuan', 'Meeting', 'Perjalanan Dinas', 'Service', 'Lain', 'Total', 'Tempat']);
    
    // Data rows - menggunakan data mentah dari PHP
    <?php foreach ($get_data_biop_grouped as $row): ?>
        <?php
            $total = $row->tol + $row->parkir + $row->bbm_rp + $row->makan + $row->jamuan + $row->meeting + $row->perjalanan_dinas + $row->service_kendaraan + $row->lain;
        ?>
        excelData.push([
            '<?= date('Y-m-d', strtotime($row->tanggal)) ?>', // Format tanggal untuk Excel (YYYY-MM-DD)
            '<?= addslashes($row->keterangan_biaya) ?>',      // Keterangan dengan escape
            <?= $row->tol ?: 0 ?>,                            // Nilai numerik mentah
            <?= $row->parkir ?: 0 ?>,
            <?= $row->bbm_km ?: 0 ?>,
            <?= $row->bbm_liter ?: 0 ?>,
            <?= $row->bbm_rp ?: 0 ?>,
            <?= $row->makan ?: 0 ?>,
            <?= $row->jamuan ?: 0 ?>,
            <?= $row->meeting ?: 0 ?>,
            <?= $row->perjalanan_dinas ?: 0 ?>,
            <?= $row->service_kendaraan ?: 0 ?>,
            <?= $row->lain ?: 0 ?>,
            <?= $total ?>,                                    // Total numerik mentah
            '<?= addslashes(ucfirst($row->keterangan_tempat)) ?>'
        ]);
    <?php endforeach; ?>
    
    // Baris total
    excelData.push([
        'TOTAL', '', 
        <?= $total_tol ?>, <?= $total_parkir ?>, '', '', 
        <?= $total_bbm ?>, <?= $total_makan ?>, <?= $total_jamuan ?>, 
        <?= $total_meeting ?>, <?= $total_perjalanan_dinas ?>, 
        <?= $total_service_kendaraan ?>, <?= $total_lain ?>, 
        <?= $grand_total ?>, ''
    ]);
    
    // Buat worksheet dari data array
    const ws = XLSX.utils.aoa_to_sheet([...headerData, ...excelData]);
    
    // Atur lebar kolom
    const colWidths = [
        { wch: 12 }, { wch: 25 }, { wch: 10 }, { wch: 10 },
        { wch: 8 }, { wch: 8 }, { wch: 10 }, { wch: 10 },
        { wch: 10 }, { wch: 10 }, { wch: 15 }, { wch: 10 },
        { wch: 10 }, { wch: 12 }, { wch: 15 }
    ];
    ws['!cols'] = colWidths;
    
    // Format header menjadi bold
    const range = XLSX.utils.decode_range(ws['!ref']);
    for (let R = 0; R <= range.e.r; R++) {
        for (let C = range.s.c; C <= range.e.c; C++) {
            const cell_address = {c:C, r:R};
            const cell_ref = XLSX.utils.encode_cell(cell_address);
            if (!ws[cell_ref]) continue;
            
            // Header tabel (baris setelah header perusahaan)
            if (R === headerData.length) {
                ws[cell_ref].s = { font: { bold: true } };
            }
            
            // Baris total
            if (R === headerData.length + excelData.length - 1) {
                ws[cell_ref].s = { font: { bold: true } };
            }
        }
    }
    
    // Tambahkan worksheet ke workbook
    XLSX.utils.book_append_sheet(wb, ws, 'BIOP');
    
    // Export ke file Excel
    XLSX.writeFile(wb, `${fileName}_<?= $get_biop->pic_name ?>_<?= date('Y-m-d') ?>.xlsx`);
}

// Fungsi untuk export Jamuan ke Excel
function exportJamuanToExcel(fileName) {
    // Buat workbook baru
    const wb = XLSX.utils.book_new();
    
    // Data header perusahaan dan informasi
    const headerData = [
        ['PT Mulia Putra Mandiri'],
        ['Gedung Mahitala LT 7. Jl. Alam Utama no 6. Serpong. Tangerang Selatan'],
        [],
        ['Nama:', '<?= $get_biop->pic_name; ?>'],
        ['Jabatan:', '<?= $get_biop->jabatan; ?>'],
        ['Periode:', '<?= date('d F Y', strtotime($get_biop->from)) .' - '. date('d F Y', strtotime($get_biop->to)); ?>'],
        []
    ];
    
    // Worksheet untuk Perjamuan
    const perjamuanData = [...headerData];
    perjamuanData.push(['PERJAMUAN']);
    perjamuanData.push(['No', 'Tanggal', 'Tempat', 'Alamat', 'Jenis Jamuan', 'Jumlah']);
    
    <?php 
    $no = 1;
    foreach ($get_data_biop as $biop) :
        if ($biop->nama_kategori == 'jamuan') { ?>
        perjamuanData.push([
            <?= $no++ ?>,
            '<?= date('Y-m-d', strtotime($biop->tanggal)) ?>',
            '<?= addslashes($biop->jamuan_tempat) ?>',
            '<?= addslashes($biop->jamuan_alamat) ?>',
            '<?= addslashes($biop->jamuan_jenis) ?>',
            <?= $biop->biaya_head_finance ?: 0 ?>
        ]);
    <?php }
    endforeach; ?>
    
    const wsPerjamuan = XLSX.utils.aoa_to_sheet(perjamuanData);
    
    // Atur lebar kolom untuk Perjamuan
    const perjamuanColWidths = [
        { wch: 5 }, { wch: 12 }, { wch: 20 }, 
        { wch: 25 }, { wch: 15 }, { wch: 12 }
    ];
    wsPerjamuan['!cols'] = perjamuanColWidths;
    
    // Worksheet untuk Relasi
    const relasiData = [...headerData];
    relasiData.push(['RELASI YANG DIJAMU']);
    relasiData.push(['No', 'Nama Perusahaan', 'Nama Yang Dijamu', 'Jabatan', 'Jenis Usaha']);
    
    <?php 
    $no = 1;
    foreach ($get_data_biop as $biop) :
        if ($biop->nama_kategori == 'jamuan') { ?>
        relasiData.push([
            <?= $no++ ?>,
            '<?= addslashes($biop->jamuan_nama_perusahaan) ?>',
            '<?= addslashes($biop->jamuan_pic) ?>',
            '<?= addslashes($biop->jamuan_pic_jabatan) ?>',
            '<?= addslashes($biop->jamuan_jenis_perusahaan) ?>'
        ]);
    <?php }
    endforeach; ?>
    
    const wsRelasi = XLSX.utils.aoa_to_sheet(relasiData);
    
    // Atur lebar kolom untuk Relasi
    const relasiColWidths = [
        { wch: 5 }, { wch: 25 }, { wch: 20 }, 
        { wch: 15 }, { wch: 20 }
    ];
    wsRelasi['!cols'] = relasiColWidths;
    
    // Format header menjadi bold untuk kedua worksheet
    [wsPerjamuan, wsRelasi].forEach(ws => {
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let R = 0; R <= 7; R++) { // Format header sampai baris 7
            for (let C = range.s.c; C <= range.e.c; C++) {
                const cell_address = {c:C, r:R};
                const cell_ref = XLSX.utils.encode_cell(cell_address);
                if (ws[cell_ref]) {
                    ws[cell_ref].s = { font: { bold: true } };
                }
            }
        }
    });
    
    // Tambahkan worksheet ke workbook
    XLSX.utils.book_append_sheet(wb, wsPerjamuan, 'Perjamuan');
    XLSX.utils.book_append_sheet(wb, wsRelasi, 'Relasi');
    
    // Export ke file Excel
    XLSX.writeFile(wb, `${fileName}_<?= $get_biop->pic_name ?>_<?= date('Y-m-d') ?>.xlsx`);
}

function exportPengeluaranToExcel(fileName) {

    const wb = XLSX.utils.book_new();

    // ==========================
    // 1. BIKIN HEADER MANUAL
    // ==========================
    const headerData = [
        ['PT Mulia Putra Mandiri'],
        ['BUKTI PENGELUARAN'],
        [],
        ['Nama', '<?= $get_biop->pic_name ?>'],
        ['Jabatan', '<?= $get_biop->jabatan ?>'],
        ['Periode', '<?= date("d F Y", strtotime($get_biop->from)) . " - " . date("d F Y", strtotime($get_biop->to)) ?>'],
        [],
        ['Uraian', 'Keterangan', 'Jumlah (Rp)'] // HEADER TABEL
    ];

    // ==========================
    // 2. AMBIL DATA DARI TABEL
    // ==========================
    const tableRows = [];
    const rows = document.querySelectorAll("#nav-pengeluaran table tbody tr");

    rows.forEach(tr => {
        const tds = tr.querySelectorAll("td");
        const rowData = [];
        tds.forEach(td => rowData.push(td.innerText.trim()));
        tableRows.push(rowData);
    });

    // ==========================
    // 3. GABUNGKAN HEADER + DATA
    // ==========================
    const finalSheetData = [...headerData, ...tableRows];

    // ==========================
    // 4. BUAT SHEET
    // ==========================
    const sheet = XLSX.utils.aoa_to_sheet(finalSheetData);

    // ==========================
    // 5. MERGE UNTUK JUDUL BIAR BAGUS
    // ==========================
    sheet['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 2 } }, // PT Mulia Putra Mandiri
        { s: { r: 1, c: 0 }, e: { r: 1, c: 2 } }  // BUKTI PENGELUARAN
    ];

    XLSX.utils.book_append_sheet(wb, sheet, "Pengeluaran");
    XLSX.writeFile(wb, fileName + ".xlsx");
}


// Event listener untuk PDF (existing code)
document.getElementById('exportPdf').addEventListener('click', function() {
    // Tampilkan modal loading
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    // Tentukan konten mana yang akan di-export berdasarkan tab aktif
    // const activeTab = document.querySelector('.tab-pane.active');
    // const contentId = activeTab.id === 'nav-biop' ? 'biop-content' : 'jamuan-content';
    // const content = document.getElementById(contentId);
    // const fileName = activeTab.id === 'nav-biop' ? 'Laporan_BIOP' : 'Laporan_Jamuan';

    const activeTab = document.querySelector('.tab-pane.active');
    let contentId, fileName;

    if (activeTab.id === 'nav-biop') {
        contentId = 'biop-content';
        fileName = 'Laporan_BIOP';
    } 
    else if (activeTab.id === 'nav-jamuan') {
        contentId = 'jamuan-content';
        fileName = 'Laporan_Jamuan';
    } 
    else if (activeTab.id === 'nav-pengeluaran') {
        contentId = 'pengeluaran-content';
        fileName = 'Laporan_Bukti_Pengeluaran';
    }

    const content = document.getElementById(contentId);

    
    // Konfigurasi PDF
    const pdf = new jsPDF('p', 'mm', 'a4');
    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = pdf.internal.pageSize.getHeight();
    
    // Capture HTML content sebagai gambar
    html2canvas(content, {
        scale: 2, // Meningkatkan kualitas
        useCORS: true,
        logging: false,
        allowTaint: true
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 1.0);
        const imgWidth = pageWidth - 20; // Margin 10mm kiri-kanan
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        
        let heightLeft = imgHeight;
        let position = 10; // Start position
        
        // Halaman pertama
        pdf.addImage(imgData, 'JPEG', 10, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
        
        // Tambahkan halaman tambahan jika konten terlalu panjang
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight + 10;
            pdf.addPage();
            pdf.addImage(imgData, 'JPEG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }
        
        // Simpan PDF
        pdf.save(`${fileName}_<?= $get_biop->pic_name ?>_<?= date('Y-m-d') ?>.pdf`);
        
        // Sembunyikan modal loading
        loadingModal.hide();
    }).catch(error => {
        console.error('Error generating PDF:', error);
        loadingModal.hide();
        alert('Terjadi kesalahan saat membuat PDF. Silakan coba lagi.');
    });
});

// Optional: Tambahkan event listener untuk tab change
document.querySelectorAll('[data-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (e) {
        // Update teks tombol export berdasarkan tab aktif
        const exportPdfBtn = document.getElementById('exportPdf');
        const exportExcelBtn = document.getElementById('exportExcel');
        const targetTab = e.target.getAttribute('aria-controls');
        
        if (targetTab === 'nav-biop') {
            exportPdfBtn.innerHTML = '<i class="fas fa-file-pdf"></i> Export BIOP to PDF';
            exportExcelBtn.innerHTML = '<i class="fas fa-file-excel"></i> Export BIOP to Excel';
        } 
        else if (targetTab === 'nav-jamuan') {
            exportPdfBtn.innerHTML = '<i class="fas fa-file-pdf"></i> Export Jamuan to PDF';
            exportExcelBtn.innerHTML = '<i class="fas fa-file-excel"></i> Export Jamuan to Excel';
        } 
        else if (targetTab === 'nav-pengeluaran') {
            exportPdfBtn.innerHTML = '<i class="fas fa-file-pdf"></i> Export Bukti Pengeluaran to PDF';
            exportExcelBtn.innerHTML = '<i class="fas fa-file-excel"></i> Export Bukti Pengeluaran to Excel';
        }

    });
});
</script>

<style>
/* Style tambahan untuk PDF export */
.card {
    background-color: white;
}

.table {
    font-size: 12px;
}

/* Sembunyikan elemen yang tidak perlu di PDF */
@media print {
    .container-fluid {
        padding: 0;
    }
    
    .nav-tabs,
    #exportPdf,
    #exportExcel,
    .alert {
        display: none !important;
    }
    
    .tab-pane {
        display: block !important;
        opacity: 1 !important;
    }
}

/* Loading spinner */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Style untuk tombol Excel */
.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}

.tabel-pengeluaran-container {
    /* max-width: 900px;         batasi lebar tabel */
    margin: 0 auto;           /* center */
}

.tabel-pengeluaran-container table {
    width: 100%;              /* tetap full di dalam container */
    font-size: 14px;
}

.tabel-pengeluaran-container th {
    background-color: #f1f3f5;
    text-align: center;
}

.tabel-pengeluaran-container td {
    vertical-align: middle;
}
</style>