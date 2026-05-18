</div>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
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

    <!-- <form action="<?= $url ?>"> -->
    <?php echo form_open($url); ?>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="supp" class="form-label">Principal</label> 
            </div>
            <div class="col-lg-6">
                <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001" <?= $this->input->get('supp') == 001 ? 'selected' : '' ?>> Deltomed</option>
                    <option value="002" <?= $this->input->get('supp') == 002 ? 'selected' : '' ?>> Marguna </option>
                    <option value="005" <?= $this->input->get('supp') == 005 ? 'selected' : '' ?>> Ultra Sakti </option>
                    <option value="012" <?= $this->input->get('supp') == 012 ? 'selected' : '' ?>> Intrafood </option>
                    <option value="013" <?= $this->input->get('supp') == 013 ? 'selected' : '' ?>> Strive </option>
                    <option value="015" <?= $this->input->get('supp') == 015 ? 'selected' : '' ?>> MDJ </option>
                    <option value="025" <?= $this->input->get('supp') == 025 ? 'selected' : '' ?>> PT. GOOD PHARMA DERMATOLOGY </option>
                    <option value="026" <?= $this->input->get('supp') == 026 ? 'selected' : '' ?>> PT. GUNUNG SUBUR SEJAHTERA </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="kategori" class="form-label">Kategori</label>
            </div>
            <div class="col-lg-6">
                <select id="kategori" name="kategori" class="form-control" required>
                    <option value=""> -- pilih kategori -- </option>
                    <option value="all" <?= $this->input->get('kategori') == 'all' ? 'selected' : '' ?>> All </option>
                    <option value="loyalty" <?= $this->input->get('kategori') == 'loyalty' ? 'selected' : '' ?>> Loyalty </option>
                    <option value="bonus_barang" <?= $this->input->get('kategori') == 'bonus_barang' ? 'selected' : '' ?>> Bonus Barang</option>
                    <option value="diskon_herbal" <?= $this->input->get('kategori') == 'diskon_herbal' ? 'selected' : '' ?>> Diskon Herbal</option>
                    <option value="diskon_candy" <?= $this->input->get('kategori') == 'diskon_candy' ? 'selected' : '' ?>> Diskon Candy</option>
                    <option value="diskon" <?= $this->input->get('kategori') == 'diskon' ? 'selected' : '' ?>> Diskon</option>
                    <option value="insentif" <?= $this->input->get('kategori') == 'insentif' ? 'selected' : '' ?>> Insentif </option>
                    <option value="listing_fee" <?= $this->input->get('kategori') == 'listing_fee' ? 'selected' : '' ?>> Listing Fee </option>
                    <option value="rafaksi" <?= $this->input->get('kategori') == 'rafaksi' ? 'selected' : '' ?>> Rafaksi </option>
                    <option value="program MT" <?= $this->input->get('kategori') == 'program MT' ? 'selected' : '' ?>> Program MT </option>
                    <option value="sewa display" <?= $this->input->get('kategori') == 'sewa display' ? 'selected' : '' ?>> Sewa Display </option>
                    <option value="salesman herbana" <?= $this->input->get('kategori') == 'salesman herbana' ? 'selected' : '' ?>> Salesman Herbana </option>
                    <option value="sample promosi" <?= $this->input->get('kategori') == 'sample promosi' ? 'selected' : '' ?>> Sample Promosi </option>
                    <option value="delto_corner" <?= $this->input->get('kategori') == 'delto_corner' ? 'selected' : '' ?>> Delto Corner </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="from" class="form-label">Periode</label> 
            </div>
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="date" name="from" id="from" class="form-control" value="<?= $this->input->get('from') ?>" required>
                    <input type="date" name="to" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="pic" class="form-label">Nama PIC</label>
            </div>
            <div class="col-lg-6">
                <select id="pic" name="pic" class="form-control" required>
                    <option value="all"> All </option>
                    <option value="18" <?= $this->input->get('pic') == '18' ? 'selected' : '' ?>> Ismi </option>
                    <option value="444" <?= $this->input->get('pic') == '444' ? 'selected' : '' ?>> Ambar </option>
                    <option value="561" <?= $this->input->get('pic') == '561' ? 'selected' : '' ?>> Adi </option>
                    <option value="557" <?= $this->input->get('pic') == '557' ? 'selected' : '' ?>> Rani </option>
                    <option value="99" <?= $this->input->get('pic') == '99' ? 'selected' : '' ?>> Yuli </option>
                    <option value="812" <?= $this->input->get('pic') == '812' ? 'selected' : '' ?>> Dea </option>
                    <option value="297" <?= $this->input->get('pic') == '297' ? 'selected' : '' ?>> Suffy </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="supp" class="form-label"></label> 
            </div>
            <div class="col-md-8">
                <input type="submit" value="Submit Data" class="btn btn-submit-black">
            </div>
        </div>

    <?= form_close(); ?>
