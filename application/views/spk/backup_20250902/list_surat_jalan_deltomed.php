</div>
<div class="container-fluid">

    <div class="az-content">
        <div class="container-fluid">

            <div class="col-md-12">
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

                <div class="row">
                    <div class="col-md-12">
                        <a href="<?= base_url().'spk/generate_surat_jalan_deltomed/'.$tgldo ?>" class="btn btn-primary">Generate Surat Jalan</a>
                        <a href="<?= base_url().'spk/delete_surat_jalan_deltomed/'.$tgldo ?>" class="btn btn-danger">Delete Surat Jalan</a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 mt-4">
                        <table id="tabel" width="100%">
                            <thead>
                                <tr>
                                    <th style="width: 10%; text-align: center">Nodo</th>
                                    <th style="width: 10%; text-align: center">Nopo</th>
                                    <th style="width: 10%; text-align: center">Tglpo</th>
                                    <th style="width: 18%; text-align: center">SuratJalanMPM</th>
                                    <th style="width: 10%; text-align: center">KodeAlamat</th>
                                    <th style="text-align: center">AlamatPO</th>
                                    <th style="text-align: center">AlamatGudang</th>
                                    <th style="text-align: center">Status</th>
                                    <th style="text-align: center">Tanda Terima</th>
                                    <th style="text-align: center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($get_surat_jalan->result() as $a):?>
                                <tr>
                                    <td><?= $a->nodo ?></td>
                                    <td><?= $a->nopo ?></td>
                                    <td><?= date('Y-m-d', strtotime($a->tglpo)) ?></td>
                                    <td>
                                        <a href="<?= base_url().'spk/export_surat_jalan_deltomed/'.str_replace('/', '_', $a->kode_surat_jalan) ?>" class="btn btn-danger"><?= $a->kode_surat_jalan ?>
                                    </td>
                                    <td><?= $a->kode_alamat." - ".$a->nama_comp ?></td>
                                    <td><?= $a->alamat_po ?></td>
                                    <td><?= $a->alamat_gudang ?></td>
                                    <td><?= $a->nama_status ?></td>
                                    <td style="text-align: center">
                                        <?php if (empty($a->image)) { ?>
                                            No Image
                                        <?php } else { ?>
                                            <img src="<?= $a->image ?>" alt="<?= $a->terima_by ?>" style="width: 100px; height: 100px; object-fit: cover;border-radius: 10px;">
                                            <?= $a->terima_at.' - '.$a->terima_by ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url().'spk/edit_surat_jalan_us/'.str_replace('/', '_', $a->kode_surat_jalan) ?>" class="btn btn-danger">Edit</a>
                                    </td>
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
    $(document).ready(function () {
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [3, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });
</script>