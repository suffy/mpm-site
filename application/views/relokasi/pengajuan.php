<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url); ?>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Tanggal Pengajuan</label>
                                        <div class="col-sm-5">
                                            <input type="date" class="form-control" name="tanggal_pengajuan" value="<?= date("Y-m-d"); ?>" readonly required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">From</label>
                                        <div class="col-sm-5">
                                        <select name="from_site" id="site_code" class="form-control" required>
                                        </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">To</label>
                                        <div class="col-sm-5">
                                        <select name="to_site" id="site_code" class="form-control" required>
                                        </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2">PIC</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="nama" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Principal</label>
                                        <div class="col-sm-5">
                                            <select name="principal" class="form-control" id="principal" required>
                                                <option value=""> -- Pilih Principal -- </option>
                                                <option value="001-herbal">Deltomed Herbal Candy</option>
                                                <option value="001-herbana">Deltomed Herbana Herbamojo</option>
                                                <option value="005">Ultra Sakti</option>
                                                <option value="002">Marguna </option>
                                                <option value="004">Jaya Agung Makmur</option>
                                                <option value="012">Intrafood</option>
                                                <option value="013">Strive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Alasan</label>
                                        <div class="col-sm-5">
                                            <select name="alasan" class="form-control" required>
                                                <option value=""> -- Pilih Alasan -- </option>
                                                <option value="over stock">over stock</option>
                                                <option value="penggantian bonus principal">penggantian bonus principal</option>
                                                <option value="kebutuhan stock">kebutuhan stock</option>
                                                <option value="kesalahan proses po">kesalahan proses po</option>
                                            </select>
                                        </div>
                                    </div>

                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Signature Digital</label>
                                        <div class="col-sm-5">
                                            <img src="<?= base_url() . 'assets/uploads/signature/' . $this->session->userdata('username') .'-signature.png' ?>"   alt="">
                                            <br>
                                            <a href="<?= base_url() ?>relokasi/register_signature" class="btn btn-outline-warning btn-sm">Manage Signature</a>
                                        </div>
                                        
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">&nbsp;</label>
                                        <div class="col-sm-5">
                                            <?php echo form_submit('submit', 'Lanjutkan ke Penginputan Produk', 'class="btn btn-success" required'); ?>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>    
                                    


                                </div>                    
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>History Relokasi</h5> (menampilkan riwayat ajuan relokasi)
                                </div>

                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-auto">
                                        List status = draft -> need supplychain approval -> need finance approval -> approved
                                        <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <table width="100%" id="multi-colum-dt" class="table table-striped table-bordered nowrap" style="display: inline-block; overflow-y: scroll">
                                        <thead>
                                            <tr>
                                                <th class="col-auto"><font size="1px">NoRelokasi</th>
                                                <th class="col-auto"><font size="1px">Status</th>
                                                <th class="col-auto"><font size="1px">Nama</th>
                                                <th class="col-auto"><font size="1px">From -> To</th>
                                                <th class="col-auto"><font size="1px">TanggalRelokasi</th>
                                                <th class="col-auto"><font size="1px">Principal</th>
                                                <th class="col-auto"><font size="1px">Nota Retur</th>
                                                <th class="col-auto"><font size="1px">File</th>
                                                <th class="col-auto"><font size="1px">Surat Jalan</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                        
                                            <?php foreach ($history_relokasi->result() as $a) : ?>
                                            <tr>
                                                <td><a href="<?= base_url().'relokasi/produk_pengajuan/'.$a->signature.'/'.$a->principal ?>" target="blank"><font size="1px"><?= $a->no_relokasi; ?></a></td>
                                                <td><font size="2px">
                                                    <a href="<?= base_url().'relokasi/generate_pdf/'.$a->signature ?>" target="_blank">
                                                    <font size="1px"><?= $a->nama_status; ?>
                                                    </a>
                                                </td>
                                                <td><font size="1px"><?= $a->nama; ?></td>
                                                <td><font size="1px"><?= $a->from_nama_comp.' -> '.$a->to_nama_comp; ?></td>
                                                <td><font size="1px"><?= $a->tanggal_pengajuan; ?></td>
                                                <td><font size="1px"><?= $a->namasupp; ?></td>
                                                <!-- <td><font size="1px"><?= $a->created_at.' by '.$a->username; ?></td> -->
                                                <td>
                                                    <a href="<?= base_url().'relokasi/faktur_retur/'.$a->signature; ?>" target="_blank" class="btn btn-warning btn-sm">show
                                                    </a>
                                                </td>
                                                <td>
                                                    <font size="1px">
                                                    <?php 
                                                        if($a->file_surat_jalan){ ?>
                                                            <a href="<?= base_url().'assets/uploads/relokasi/surat_jalan/'.$a->file_surat_jalan ?>" target="_blank"><?= $a->file_surat_jalan ?></a>

                                                            
                                                        <?php } else{
                                                            echo "not found";
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" id="testOnclick" onclick="get_id_relokasi('<?= $a->id ?>')" data-toggle="modal" data-target="#vendor">upload</button>
                                                    <?php $this->load->view('relokasi/modal_surat_jalan') ?>
                                                </td>
                                                
                                            </tr>
                                            <?php endforeach; ?>    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/subbranch') ?>',
        data: {},
        success: function(hasil) {
            $("select[name = from_site]").html(hasil);
            $("select[name = to_site]").html(hasil);
        }
    });
</script>

                        
                        