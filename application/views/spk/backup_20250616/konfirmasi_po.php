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

                <form action="" method="post">
                    <div class="row mt-3">
                        <div class="col-lg-2">
                            <label for="from">Periode</label>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="date" name="from" id="from" class="form-control" value="<?= $from ?>" required>
                                <input type="date" name="to" id="to" class="form-control" value="<?= $to ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-2">
                            <label for="supp">Principal</label> 
                        </div>
                        <div class="col-md-4">
                            <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                                <option value="">Principal ?</option>
                                <?php 
                                    if ($this->session->userdata('supp') == '000') { ?>
                                        <option value="all">All</option>
                                    <?php
                                    }
                                ?>
                                <?php foreach ($get_principal->result() as $a) { ?>
                                    <option value="<?= $a->supp ?>" <?= $this->input->get('supp') == $a->supp ? 'selected' : '' ?>><?= $a->namasupp ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
    
                    <div class="row mt-1">
                        <div class="col-lg-2">
                            <label for="from">Company</label>
                        </div>
                        <div class="col-lg-4">
                            <select id="site_code" name="site_code" class="form-control" required>
                            </select>
                        </div>
                    </div>
    
                    
    
                    <div class="row mt-3">
                        <div class="col-lg-2">
                            <label for="supp"></label>
                        </div>
                        <div class="col-md-10">
                            <input type="submit" name = "submit" value="search_po" class="btn btn-submit-orange" style="height: 44px;">
                            <input type="submit" name="submit" class="btn btn-submit-black" value="export_multiple" style="height: 44px;">

                        </div>
                    </div>
                </form>

                
                <div class="row mt-3">
                    <div class="col-md-12 mt-4">
                        <h4>Tabel PO</h4>
                        <table id="tabel-data" class="table-striped dataTable no-footer">    
                            <thead>
                                <tr>                                     
                                    <th width="1"><font size="2px">Count DO</th>                            
                                    <th width="1"><font size="2px">Nomor PO</th>                        
                                    <th width="1"><font size="2px">Principal</th>                        
                                    <th width="1"><font size="2px">Company</th>                        
                                    <th width="1"><font size="2px">SubBranch</th>                        
                                    <th width="1"><font size="2px">Tipe</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_data->result() as $value): ?>
                                    <tr>
                                        <?php 
                                            if($value->count_do) { ?>                                                
                                                <td>
                                                    <a href="<?= base_url("spk/konfirmasi_po_detail/$value->id") ?>" >
                                                    <label class="btn btn-submit status pending-rilis-po" style="font-size:14px; width: 100%"><?= $value->count_do ?></label>
                                                </a>
                                                </td>                                                
                                            <?php
                                            }else{ ?>
                                                <td>belum tersedia</td>
                                            <?php
                                            }
                                        ?>
                                        <!-- <td>
                                            <label class="btn btn-submit status pending-rilis-po" style="font-size:14px">
                                                <a href="<?= base_url("spk/konfirmasi_po_detail/$value->id") ?>">
                                                    <?= $value->count_do ? "$value->count_do" : 'Belum Tersedia'; ?>
                                                </a>
                                        </td> -->

                                        <!-- <td>
                                            <label class="btn btn-submit status pending-rilis-po" style="font-size:14px">
                                                <a href="<?= base_url("spk/konfirmasi_po_detail/$value->id") ?>">
                                                    <?= $value->count_do ? "$value->count_do" : 'Belum Tersedia'; ?>
                                                </a>
                                        </td> -->

                                        <td><label class="btn btn-submit status pending-rilis-po" style="font-size:14px"><a href="<?= base_url("transaction/download_pdf/$value->po_id") ?>"><?= ($value->nopo != null) ? "$value->nopo" : 'Belum Tersedia'; ?></a></td>
                                        <td><?= $value->namasupp; ?></td>
                                        <td><?= $value->company; ?></td>
                                        <td><?= $value->nama_comp; ?></td>
                                        <td><?= $value->tipe; ?></td>
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



<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("spk/master_sitecode") ?>',
        data: '',
        success: function(result) {
            $("select[name = site_code]").html(result);
        }
    });
</script>