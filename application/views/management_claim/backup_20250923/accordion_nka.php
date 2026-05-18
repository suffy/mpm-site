<style>
    #divLog, #divDokumentasi {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.5s ease;
}

    #divLog.show, #divDokumentasi.show {
        max-height: 100%; /* cukup besar agar semua konten terlihat */
        opacity: 1;
        transition: all 0.15s ease-in-out;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
</style>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mb-4">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-12 az-content-label">
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

        <button onclick="toggleSection('divLog')" class="btn btn-submit" type="button" style="border: none; border-radius: 10px;">Lihat History</button>
        <button onclick="toggleSection('divDokumentasi')" class="btn btn-warning" type="button" style="border-radius: 10px;">Dokumentasi</button>

        <!-- Log Dokumentasi -->
        <div class="row mt-3" id="divDokumentasi">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <label><strong>Dokumentasi</strong></label>
                        <div class="table-responsive">
                            <table id="tabel-dokumentasi">
                                <thead>
                                    <tr>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr>
                                        <td>PENDING DP</td>
                                        <td>DP dimohon untuk segera mengajukan claim nya. Mungkin saja data yang pernah dikirimkan perlu diperbaiki dan diajukan kembali.</td>
                                    </tr>
                                    <tr>
                                        <td>PENDING KAM</td>
                                        <td>Menunggu PIC KAM Principal terkait mem-verifikasi data claim yang diajukan.</td>
                                    </tr>
                                    <tr>
                                        <td>REJECT KAM (CLOSED)</td>
                                        <td>Terjadi kesalahan pengajuan oleh DP sehingga pengajuan claim di reject oleh PIC KAM Principal dan status closed.</td>
                                    </tr>
                                    <tr>
                                        <td>PENDING MPM / PENDING PRINCIPAL</td>
                                        <td>Menunggu PIC MPM / Principal terkait mem-verifikasi data claim yang diajukan.</td>
                                    </tr>
                                    <tr>
                                        <td>REJECT MPM / REJECT PRINCIPAL (CLOSED)</td>
                                        <td>Terjadi kesalahan pengajuan oleh DP sehingga pengajuan claim di reject oleh PIC MPM / Principal dan status closed.</td>
                                    </tr>
                                    <tr>
                                        <td>PENDING ADMIN MPM</td>
                                        <td>Menunggu proses verifikasi PIC Admin MPM terkait</td>
                                    </tr>
                                    <tr>
                                        <td>REJECT ADMIN MPM (CLOSED)</td>
                                        <td>Terjadi kesalahan pengajuan oleh DP sehingga pengajuan claim di reject oleh PIC Admin MPM dan status closed.</td>
                                    </tr>
                                    <tr>
                                        <td>APPROVE ADMIN MPM</td>
                                        <td>Pengajuan claim sudah di approve oleh PIC Admin MPM.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log History -->
        <div class="row mt-3" id="divLog">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <label><strong>Log Status</strong></label>
                        <div class="table-responsive">
                            <table id="tabel-log-history">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">User -> On Duty</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Status</th>      
                                    </tr>
                                </thead>
                                <tbody> 
                                    <?php 
                                    $no = 1;
                                    foreach ($get_log->result() as $key => $value) {?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-center" style="text-transform: uppercase;">
                                                <?= implode(' / ',$user[$key]); ?> <i class="typcn typcn-arrow-right-outline"></i>
                                                <strong>
                                                    <?= implode(' / ',$pic[$key]); ?>
                                                </strong>
                                            </td>
                                            <td><?= $value->keterangan ?></td>
                                            <td class="text-center"><?= date('d M Y H:i:s', strtotime($value->created_at)); ?></td>
                                            <td class="text-center" style="text-transform: uppercase;"><?= $value->nama_status ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail pengajuan -->
        <div class="row mt-3" id="divDetail">
            <div class="col-md-6">
                <div class="card" style="text-transform: capitalize;">
                    <div class="card-body">
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label><strong>Data Pengajuan Claim</strong></label>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Status</label>
                            </div>
                            <div class="col-md-8">
                                <p style="text-transform: uppercase;"><?= $get_data->row()->nama_status?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Nomor Klaim</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->nomor_ajuan?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Nomor Invoice/ SKP/ Trading Term</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->nomor_invoice?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Channel</label>
                            </div>
                            <div class="col-md-8">
                                <p style="text-transform: uppercase;"><?= $get_data->row()->channel?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Kategori</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->kategori?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Key Account</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->channel == 'nka' ? $get_data->row()->key_account : '-';?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Periode</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->periode_start .' - '. $get_data->row()->periode_end ?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Keterangan</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->keterangan?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Nominal Claim</label>
                            </div>
                            <div class="col-md-8">
                                <p>Rp. <?= number_format($get_data->row()->nominal_dpp)?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Site Code</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->site_code?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>PIC</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->pic_nama?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Email PIC</label>
                            </div>
                            <div class="col-md-8">
                                <p style="text-transform: none;"><?= $get_data->row()->pic_email?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Attachment</label>
                            </div>
                            <div class="col-md-8"><?php  $no = 1;
                                $attachment = json_decode($get_data->row()->attachment);
                                foreach ($attachment as $key_attachment) {?>
                                    <?= $no++ .'.' ?>
                                    <a href="<?= base_url() . 'assets/uploads/management_claim/nka/' .$get_data->row()->kategori .'/'. $key_attachment ?>">
                                        <?= $key_attachment ?>
                                    </a>
                                    <br>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card" style="text-transform: capitalize;">
                    <div class="card-body">
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label><strong>Data Verifikasi Principal & MPM</strong></label>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Verifikasi KAM at</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->principal_area_at?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Nama</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->username_kam?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Keterangan</label>
                            </div>
                            <div class="col-md-8">
                                <p style="text-transform: none;"><?= $get_data->row()->keterangan_principal_area?></p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Verifikasi <?= $get_data->row()->channel == 'nka' ? "MPM" : "Principal"; ?> at</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->mpm_at?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Nama</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->username_mpm?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Keterangan</label>
                            </div>
                            <div class="col-md-8">
                                <p style="text-transform: none;"><?= $get_data->row()->keterangan_mpm?></p>
                            </div>
                        </div>
                        
                        <hr>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Verifikasi Admin MPM at</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->admin_mpm_at?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Nama</label>
                            </div>
                            <div class="col-md-8">
                                <p><?= $get_data->row()->username_admin_mpm?></p>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label>Keterangan</label>
                            </div>
                            <div class="col-md-8">
                                <p style="text-transform: none;"><?= $get_data->row()->keterangan_admin_mpm?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-log-history').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // scrollX: true,
        });

        $('#tabel-dokumentasi').DataTable({
            info: false,
            paging: false,     // menghilangkan pagination
            searching: false,   // menghilangkan search box
            ordering: false
        });
    });
</script>

<script>
    function toggleSection(sectionId) {
        const element = document.getElementById(sectionId);
        if (element) {
            element.classList.toggle('show');
        }
    }
</script>