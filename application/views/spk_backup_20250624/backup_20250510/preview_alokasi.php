<style>
    table {
        border-collapse: collapse;
        border: 1px solid;
        width: 100%;    
    }

    th, td {
        text-align: left;
        padding: 8px;
        border: 1px solid;
    }
</style>

<?= $this->load->view('spk/component/title');?>
<hr class="mt-2 mb-5">
<?php echo form_open($url); ?>

<div class="card-block mb-1">
    <div class="row">
        <div class="col-md-12">
            
            <?php 
            foreach ($get_supp->result() as $a) : ?>

            <div class="row">
                <div class="col-md-12 mb-5">
                    <h4>Principal : <?= $a->namasupp ?></h4>
                </div>
            </div>

            <?php
                $no = 1;
                $get_data = $this->model_spk->get_temp_alokasi_join_temp_alokasi_detail_by_userid_supp_group_by_kode_alamat($userid, $a->supp);

                foreach ($get_data->result() as $b) : ?>

                    <input type="hidden" name="supp[]" value="<?= $a->supp ?>">
                    <input type="hidden" name="site_code[]" value="<?= $b->site_code ?>">
                    <input type="hidden" name="kode_alamat[]" value="<?= $b->kode_alamat ?>">
                    <input type="hidden" name="alamat[]" value="<?= ($this->model_spk->get_alamat_by_kode_alamat_username($b->kode_alamat, $b->username)->num_rows() > 0) ? $this->model_spk->get_alamat_by_kode_alamat_username($b->kode_alamat, $b->username)->row()->alamat : '' ?>">
                    <input type="hidden" name="userid_tujuan[]" value="<?= $b->userid_tujuan ?>">
                    <input type="hidden" name="npwp[]" value="<?= $b->npwp ?>">
                    <input type="hidden" name="company[]" value="<?= $b->company ?>">
                    <input type="hidden" name="email[]" value="<?= $b->email ?>">
                    <input type="hidden" name="id_header[]" value="<?= $b->id ?>">
                    <input type="hidden" name="id_detail[]" value="<?= $b->id_detail ?>">

                    <div class="row">
                        <div class="col-md-12">                        
                            <table class="display mt-1 mb-5">
                            <thead>
                                <tr>
                                    <th width="1%" colspan="7"><strong>#<?= $no++ ?>.</strong> Pemesan : <?= $b->nama_comp_header. ' ('.$b->site_code.') ' ?></th>
                                </tr>
                                <tr>
                                    <th width="1%" colspan="7">Tujuan : <?= $b->nama_comp_tujuan. ' ('.$b->kode_alamat.') ' ?></th>
                                </tr>
                                <tr>
                                    <th width="1%" colspan="7"><?= ($this->model_spk->get_alamat_by_kode_alamat_username($b->kode_alamat, $b->username)->num_rows()>0) ? $this->model_spk->get_alamat_by_kode_alamat_username($b->kode_alamat, $b->username)->row()->alamat : "Cek Kembali Data Alokasi Anda !!" ?></th>
                                </tr>
                                <tr>
                                    <th width="10%">Kodeprod</th>
                                    <th width="50%">Namaprod</th>                                
                                    <th width="10%">Karton</th>
                                    <th width="10%">Berat</th>
                                    <th width="10%">Volume</th>
                                    <th width="10%">Average</th>
                                    <th width="10%">Ratio</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php 
                                $get_temp_alokasi_join_temp_alokasi_detail_by_userid_supp_kode_alamat = $this->model_spk->get_temp_alokasi_join_temp_alokasi_detail_by_userid_supp_kode_alamat($this->session->userdata('id'), $a->supp, $b->kode_alamat);
                                
                                foreach ($get_temp_alokasi_join_temp_alokasi_detail_by_userid_supp_kode_alamat->result() as $c) : 
                            ?>    
                                <tr>
                                    <td><?= $c->kodeprod ?></td>
                                    <td><?= $c->namaprod ?></td>
                                    <td align="right"><?= $c->jml_karton ?></td>
                                    <td align="right"><?= $c->total_berat ?></td>
                                    <td align="right"><?= $c->total_volume ?></td>
                                    <td align="right"><?= $c->average_karton ?></td>
                                    <td align="right"><?= $c->ratio ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        

                        </table>


                        </div>
                    </div>                
                
                <?php endforeach; ?> 
            <?php endforeach; ?> 
        </div>
    </div>

    <div class="row mt-1 mb-3">
        <div class="col-lg-2">
            <label for="email" class="form-label">Tipe</label> 
        </div>
        <div class="col-lg-4">
            <select name="tipe" class="form-control" required>
                <option value="">Pilih Tipe</option>
                <option value="S">SPK</option>
                <option value="A">Alokasi</option>
            </select>
        </div>
    </div>

</div>



<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
         <a href="<?= base_url('spk/keranjang_alokasi') ?>" class="btn btn-submit-black" style="width: 50%">Kembali</a>
        <input type="submit" value="Submit Alokasi" class="btn btn-submit-orange" style="width: 50%">
    </div>
</div>