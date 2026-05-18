<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">

        <?php
        if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul' || $this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'milla') { ?>
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

            <div class="row mt-1">
                <div class="col-md-12">

                    <?php echo form_open($url); ?>

                    <div class="row mt-3">
                        <div class="col-lg-2">
                            <label for="supp">Periode</label>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="date" name="from" id="from" min="2024-01-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                                <input type="date" name="to" id="to" min="2024-01-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-2">
                            <label for="supp">Principal</label>
                        </div>
                        <div class="col-md-4">
                            <select name="principal" id="principal" class="form-control select2" required>
                                <option value="">-- Select Principal --</option>
                                <?php foreach ($get_principal->result() as $row) : ?>
                                    <option value="<?= $row->supp ?>" <?= $this->input->get('supp') == $row->supp ? 'selected' : '' ?>><?= $row->namasupp ?></option>                                    
                                <?php endforeach; ?>
                                <?php if($supp == '000'){ ?>
                                    <option value="all" <?= $this->input->get('supp') == 'all' ? 'selected' : '' ?>>OTHERS</option>
                                <?php
                                }?>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-12">

                    <?php echo form_open($url); ?>

                    <div class="row mt-3">
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-4">
                            <button class="pastel-orange-btn" id="btnKirim">Export Data</button>
                        </div>
                    </div>
                </div>
            </div>