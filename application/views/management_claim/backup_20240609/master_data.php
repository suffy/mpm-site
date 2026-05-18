</div>

<div class="container-fluid">

<div class="az-content">
    <div class="container-fluid">
        <div class="az-content-left az-content-left-components">
          <div class="component-item">
            <label>Master Data MPI</label>
            <nav class="nav flex-column">
              <a href="#master_kam" class="nav-link-new">PIC Kam</a>
              <a href="#master_account" class="nav-link-new">Master Account</a>
              <a href="#mapping_account" class="nav-link-new">Mapping Account</a>
              <a href="#master_brand" class="nav-link-new">Master Brand</a>
              <a href="#master_finance" class="nav-link-new">PIC Finance</a>
              <hr>
              <label>Master Data DP</label>
              <a href="#mapping_approval" class="nav-link-new">Mapping Approval</a>
              <a href="#master_region" class="nav-link-new">Master Region</a>
            </nav>
          </div>
        </div>

        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Claim Monitoring</span>
            <span>Master Data</span>
          </div>

          <h2 class="az-content-title" id="master_kam">PIC KAM</h2>

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

            <p class="mg-b-20">Masukkan data PIC pada form di bawah ini.</p>

            <?php echo form_open_multipart($url_kam); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">Principal</label> 
                </div>
                <div class="col-md-4">
                    <select id="supp" name="supp" class="form-control" required>
                        <option value=""> -- pilih principal -- </option>
                        <option value="001"> Deltomed </option>
                        <option value="001-herbana"> Herbana </option>
                        <option value="002"> Marguna </option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">User Web</label> 
                </div>
                <div class="col-md-4">
                    <select id="userid_kam" name="userid_kam" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Save PIC KAM</button>
                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <?php echo form_open($url_kam_delete); ?>
            <div class="row mt-3">
                <div class="col-md-12 mt-4">  
                    <table id="kam">
                        <thead>
                            <tr>    
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th style="width:10px" class="text-center">No</th>     
                                <th style="width:20px" class="text-center">#</th>              
                                <th style="width:200px">Principal</th>    
                                <th style="width:10px">Userid</th>                    
                                <th style="width:100px">Username</th>                    
                                <th style="width:100px">Name</th>                    
                                <th style="width:100px">Email</th>                       
                                <th>CreatedBy</th>                  
                                <th>CreatedAt</th>             
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_master_user_kam->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center">  
                                    <a href="<?= base_url('management_claim/delete_master_kam/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                                </td>
                            <td><?= $a->namasupp ?></td>
                            <td><?= $a->userid_kam ?></td>
                            <td><?= $a->username ?></td>
                            <td><?= $a->name ?></td>
                            <td><?= $a->email ?></td>
                            <td><?= $a->name ?></td>
                            <td><?= $a->created_at ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-2 mb-3">
                <div class="col">
                    <input type="submit" class="btn btn-submit-black" id="btnDelete" value="Delete Row yang dipilih" onclick="return confirm('Are you sure?')">
                </div>
            </div>
            <?php echo form_close(); ?>

            <hr class="mg-y-30">

            <h2 class="az-content-title" id="master_account">Master Account</h2>

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

            <p class="mg-b-20">Masukkan data Account pada form di bawah ini.</p>

            <?php echo form_open($url_account); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">Account</label> 
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="account" name="account" placeholder="Masukkan Account" required>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirimAccount" onclick="return button()">Save Account</button>
                    <button class="btn btn-loading" id="btnLoadingAccount" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row mt-3 mb-3">
                <div class="col-md-12 mt-4">  
                    <table id="table-account">
                        <thead>
                            <tr>    
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th style="width:1%" class="text-center">No</th>     
                                <th style="width:1%" class="text-center">#</th>              
                                <th>Account</th>                  
                                <th>CreatedBy</th>                  
                                <th>CreatedAt</th>                  
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_master_account->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center">  
                                <a href="<?= base_url('management_claim/delete_master_account/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                            </td>
                            <td><?= $a->account ?></td>
                            <td><?= $a->name ?></td>
                            <td><?= $a->created_at ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="mg-y-30">

            <h2 class="az-content-title" id="mapping_account">Mapping Account By PIC KAM</h2>

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

            <p class="mg-b-20">Masukkan data Mapping Account pada form di bawah ini.</p>

            <?php echo form_open($url_mapping_account); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">User Kam</label> 
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="kam" name="kam" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">Account</label> 
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="account" name="account" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirimMappingAccount" onclick="return button()">Save Mapping Account</button>
                    <button class="btn btn-loading" id="btnLoadingMappingAccount" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row mt-3 mb-5">
                <div class="col-md-12 mt-4">  
                    <table id="table-mapping-account">
                        <thead>
                            <tr>    
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th style="width:1%" class="text-center">No</th>     
                                <th style="width:5%" class="text-center">#</th>              
                                <th style="width:200px">Account</th>                  
                                <th style="width:200px">Kam</th>                  
                                <th style="width:100px">CreatedAt</th>                  
                                <th style="width:100px">CreatedBy</th>                  
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_master_mapping_account->result() as $a) : ?>
                        <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>  
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center">  
                                <a href="<?= base_url('management_claim/delete_master_mapping_account/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                            </td>
                        <td><?= $a->account ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->created_by ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="mg-y-30">

            <h2 class="az-content-title" id="master_brand">Master Brand</h2>

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

            <p class="mg-b-20">Masukkan data Master Brand pada form di bawah ini.</p>

             <?php echo form_open($url_master_brand); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">Brand</label> 
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="brand" placeholder = "Masukkan brand ... " required>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirimMasterBrand" onclick="return button()">Save Brand</button>
                    <button class="btn btn-loading" id="btnLoadingMasterBrand" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row mt-3">
                <div class="col-md-12 mt-4">  
                    <table id="table-master-brand">
                        <thead>
                            <tr>    
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th style="width:1%" class="text-center">No</th>     
                                <th style="width:5%" class="text-center">#</th>              
                                <th style="width:200px">Brand</th>               
                                <th style="width:100px">CreatedAt</th>                  
                                <th style="width:100px">CreatedBy</th>                  
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_master_brand->result() as $a) : ?>
                        <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>  
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center">  
                                <a href="<?= base_url('management_claim/delete_master_brand/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                            </td>
                        <td><?= $a->brand ?></td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->name ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="mg-y-30">

            <h2 class="az-content-title" id="master_finance">PIC FINANCE</h2>

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

            <p class="mg-b-20">Masukkan data PIC pada form di bawah ini.</p>

            <?php echo form_open_multipart($url_finance); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">User Web</label> 
                </div>
                <div class="col-md-4">
                    <select id="userid_finance" name="userid_finance" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Save PIC Finance</button>
                    <button class="btn btn-loading" id="btnLoadingFinance" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <?php echo form_open($url_finance_delete); ?>
            <div class="row mt-3">
                <div class="col-md-12 mt-4">  
                    <table id="table-master-finance">
                        <thead>
                            <tr>    
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th style="width:10px" class="text-center">No</th>     
                                <th style="width:20px" class="text-center">#</th>
                                <th style="width:10px">Userid</th>                    
                                <th style="width:100px">Username</th>                    
                                <th style="width:100px">Name</th>                    
                                <th style="width:100px">Email</th>                       
                                <th>CreatedBy</th>                  
                                <th>CreatedAt</th>             
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_master_user_finance->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center">  
                                    <a href="<?= base_url('management_claim/delete_master_finance/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                                </td>
                            <td><?= $a->userid_finance ?></td>
                            <td><?= $a->username ?></td>
                            <td><?= $a->name ?></td>
                            <td><?= $a->email ?></td>
                            <td><?= $a->name ?></td>
                            <td><?= $a->created_at ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-2 mb-3">
                <div class="col">
                    <input type="submit" class="btn btn-submit-black" id="btnDelete" value="Delete Row yang dipilih" onclick="return confirm('Are you sure?')">
                </div>
            </div>
            <?php echo form_close(); ?>

            <!-- mapping approval -->
            <hr class="mg-y-30">

            <h2 class="az-content-title" id="mapping_approval">Mapping Approval</h2>

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

            <p class="mg-b-20">Masukkan data Mapping Approval pada form di bawah ini.</p>

            <?php echo form_open_multipart($url_mapping_approval); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="userid_approval" class="form-label">User</label> 
                </div>
                <div class="col-md-4">
                    <select id="userid_approval" name="userid_approval" class="form-control" required>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="atasan_approval" class="form-label">Head</label> 
                </div>
                <div class="col-md-4">
                    <select id="userid_head" name="userid_head" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Save Mapping Approval</button>
                    <button class="btn btn-loading" id="btnLoadingApproval" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row mt-3">
                <div class="col-md-12 mt-4">  
                    <table id="table-mapping-approval">
                        <thead>
                            <tr>    
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th style="width:10px" class="text-center">No</th>     
                                <th style="width:20px" class="text-center">#</th>
                                <th style="width:10px">Pic approval</th>                    
                                <th style="width:100px">Head</th>                
                                <th>CreatedBy</th>                  
                                <th>CreatedAt</th>             
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_mapping_struktur_approval->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center">  
                                    <a href="<?= base_url('management_claim/delete_mapping_approval/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                                </td>
                            <td><?= $a->name_approval ?></td>
                            <td><?= $a->name_head ?></td>
                            <td><?= $a->created_by ?></td>
                            <td><?= $a->created_at ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- master region -->
            <hr class="mg-y-30">

            <h2 class="az-content-title" id="master_region">Master Region</h2>

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

            <p class="mg-b-20">Masukkan data Master Region pada form di bawah ini.</p>

            <?php echo form_open_multipart($url_master_region); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="site_code" class="form-label">Site</label> 
                </div>
                <div class="col-md-4">
                    <select id="site_code" name="site_code" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="pic_mpm" class="form-label">PIC MPM</label> 
                </div>
                <div class="col-md-4">
                    <select id="pic_mpm" name="pic_mpm" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="pic_principal" class="form-label">PIC Principal</label> 
                </div>
                <div class="col-md-4">
                    <select id="pic_principal" name="pic_principal" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="pic_finance" class="form-label">PIC Finance</label> 
                </div>
                <div class="col-md-4">
                    <select id="pic_finance" name="pic_finance" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Save Master Region</button>
                    <button class="btn btn-loading" id="btnLoadingRegion" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row mt-3">
                <div class="col-md-12 mt-4">  
                    <table id="table-master-region">
                        <thead>
                            <tr>    
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                                </th>
                                <th style="width:10px" class="text-center">No</th>     
                                <th style="width:20px" class="text-center">#</th>
                                <th style="width:10px">Site Code</th>                    
                                <th style="width:10px">Branch</th>                    
                                <th style="width:10px">Subbranch</th>                    
                                <th style="width:100px">PIC MPM</th>                
                                <th style="width:100px">PIC Principal</th>                
                                <th style="width:100px">PIC Finance</th>                
                                <th>CreatedBy</th>                  
                                <th>CreatedAt</th>             
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_master_region->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center">  
                                    <a href="<?= base_url('management_claim/delete_master_region/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                                </td>
                            <td><?= $a->site_code ?></td>
                            <td><?= $a->branch_name ?></td>
                            <td><?= $a->nama_comp ?></td>
                            <td><?= $a->name_mpm ?></td>
                            <td><?= $a->name_principal ?></td>
                            <td><?= $a->name_finance ?></td>
                            <td><?= $a->created_by ?></td>
                            <td><?= $a->created_at ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#kam').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackAccount").show();
        $("#btnLoadingAccount").hide();
        $('#table-account').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackMappingAccount").show();
        $("#btnLoadingMappingAccount").hide();
        $('#table-mapping-account').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackMasterBrand").show();
        $("#btnLoadingMasterBrand").hide();
        $('#table-master-brand').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackFinance").show();
        $("#btnLoadingFinance").hide();
        $('#table-master-finance').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackApproval").show();
        $("#btnLoadingApproval").hide();
        $('#table-mapping-approval').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackRegion").show();
        $("#btnLoadingRegion").hide();
        $('#table-master-region').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_kam_single') ?>',
        data: '',
        success: function(result) {
            $("select[name = kam]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_account_mti') ?>',
        data: '',
        success: function(result) {
            $("select[name = account]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_mpm') ?>',
        data: '',
        success: function(result) {
            $("select[name = userid_kam]").html(result);
            $("select[name = userid_approval]").html(result);
            $("select[name = userid_head]").html(result);
            $("select[name = userid_finance]").html(result);
            $("select[name = pic_mpm]").html(result);
            $("select[name = pic_principal]").html(result);
            $("select[name = pic_finance]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_site') ?>',
        data: '',
        success: function(result) {
            $("select[name = site_code]").html(result);
        }
    });

</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>