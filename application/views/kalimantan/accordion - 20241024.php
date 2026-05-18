</div>

<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <p>
                <button class="btn btn-submit-black" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Lihat Detail Program</button>
            </p>
            <div class="row">
                        
                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">     

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Pengajuan DP</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Status</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $nama_status ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">No Pengajuan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $no_pengajuan ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Tipe</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $tipe ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Principal</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $namasupp ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Branch - SubBranch</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $branch_name.' - '.$nama_comp.' - '.$site_code ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">PIC DP</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $nama ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Pengajuan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($tanggal_pengajuan) { ?>                                            
                                                <label class="form-control" readonly><?= $tanggal_pengajuan ?></label>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control" readonly><i>retur belum tuntas diajukan</i></label>
                                            <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Lampiran Pengajuan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($file) { ?>
                                                <a href="<?= base_url().'assets/file/retur/'.$file ?>">
                                                <label class="form-control" readonly><?= $file ?></label></a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control" readonly><i>user tidak melampirkan file</i></label>
                                            <?php
                                            }
                                        ?>   
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">     

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Verifikasi Principal & MPM</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Verifikasi Principal Area at</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $principal_area_at ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $principal_area_username ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Verifikasi MPM at</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $verifikasi_at ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $verifikasi_username ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Verifikasi Principal HO at</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $principal_ho_at ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $principal_ho_username ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Principal HO</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($file_principal_ho) { ?>
                                                <a href="<?= base_url().'assets/file/retur/'.$file_principal_ho ?>">
                                                <label class="form-control"><?= $file_principal_ho ?></label></a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control"><i>user tidak melampirkan file_principal_ho</i></label>
                                            <?php
                                            }
                                        ?> 
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Note Principal HO</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $catatan_principal_ho ?></label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>                

            </div>

            <div class="row mt-2">
                        
                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container"> 
                                
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Pengiriman Barang Retur</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Kirim Barang</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $tanggal_kirim_barang ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama Ekspedisi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $nama_ekspedisi ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Estimasi Tiba</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $est_tanggal_tiba ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Resi Pengiriman</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($file_pengiriman) { ?>
                                                <a href="<?= base_url().'assets/file/retur/'.$file_pengiriman ?>">
                                                <!-- <input type="text" value="<?= $file_pengiriman ?>" class="form-control"> -->
                                                <label class="form-control"><?= $file_pengiriman ?></label></a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control"><i>user tidak melampirkan file_pengiriman</i></label>
                                            <?php
                                            }
                                        ?>    
                                    </div>
                                </div>
                                
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Proses Kirim Barang By</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $username_kirim_barang ?></label>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Last Update</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $proses_kirim_barang_at ? $proses_kirim_barang_at : '' ?></label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Penerimaan Barang Retur</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Terima Barang</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $tanggal_terima_barang ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nama Penerima</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $nama_penerima ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Nomor Terima Barang (LPK)</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $no_terima_barang ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Terima Barang</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($file_terima_barang) { ?>
                                                <a href="<?= base_url().'assets/file/retur/'.$file_terima_barang ?>">
                                                <!-- <input type="text" value="<?= $file_terima_barang ?>" class="form-control"> -->
                                                <label class="form-control"><?= $file_terima_barang ?></label></a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control"><i>user tidak melampirkan file_terima_barang</i></label>
                                            <?php
                                            }
                                        ?>  
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Last Update</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $terima_barang_at ?></label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container">     

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="supp"><strong>Data Pemusnahan Barang Retur</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="supp">Tanggal Pemusnahan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $tanggal_pemusnahan ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">PIC Pemusnahan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $nama_pemusnahan ?></label>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">File Pemusnahan (Berita Acara)</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($file_pemusnahan) { ?>
                                                <a href="<?= base_url().'assets/file/retur/'.$file_pemusnahan ?>">
                                                <label class="form-control"><?= $file_pemusnahan ?></label></a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control"><i>user tidak melampirkan file_pemusnahan</i></label>
                                            <?php
                                            }
                                        ?> 
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Foto Pemusnahan 1</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($foto_pemusnahan_1) { ?>
                                                <a href="<?= base_url().'assets/file/retur/'.$foto_pemusnahan_1 ?>">
                                                <label class="form-control"><?= $foto_pemusnahan_1 ?></label></a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control"><i>user tidak melampirkan foto_pemusnahan_1</i></label>
                                            <?php
                                            }
                                        ?> 
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Foto Pemusnahan 2</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($foto_pemusnahan_2) { ?>
                                                <a href="<?= base_url().'assets/file/retur/'.$foto_pemusnahan_2 ?>">
                                                <label class="form-control"><?= $foto_pemusnahan_2 ?></label></a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control"><i>user tidak melampirkan foto pemusnahan 2</i></label>
                                            <?php
                                            }
                                        ?> 
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Video Pemusnahan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php 
                                            if ($video) { ?>
                                                <video width="320" height="240" controls>
                                                    <source src="<?= base_url().'assets/file/retur/'.$video ?>" type="video/mp4">
                                                    <source src="movie.ogg" type="video/ogg">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <a href="<?= base_url().'assets/file/retur/'.$video ?>" class="btn btn-secondary btn-sm rounded" target="_blank" download>download</a>
                                            <?php
                                            }else{ ?>
                                                <label class="form-control"><i>user tidak melampirkan video</i></label>
                                            <?php
                                            }
                                        ?> 
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label for="supp">Last Updated</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-control" readonly><?= $pemusnahan_at ?></label>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row mt-2">
                        
                <div class="col-md-12">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <div class="container-fluid">     

                                <div class="row mt-1">
                                    <div class="col-md-12 text-center">
                                        <a href="<?= base_url().'management_inventory/export_by_signature/'.$signature ?>" class="btn btn-submit-black">export raw csv</a>                                
                                        <a href="<?= base_url().'management_inventory/generate_pdf/'.$signature.'/'.$supp ?>" class="btn btn-submit-black" target="_blank">Export Pdf</a>
                                        <a href="<?= base_url().'management_inventory/export_sortir_by_signature/'.$signature ?>" class="btn btn-submit-black">export csv (data final untuk pabrik)</a>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <table id="example">
                                            <thead>
                                                <tr>
                                                    <th>Kodeprod</th>
                                                    <th>Namaprod</th>
                                                    <th>Batch</th>
                                                    <th>ED</th>
                                                    <th>Nama Outlet</th>
                                                    <th>Alasan</th>
                                                    <th>Ket</th>
                                                    <th>Qty Pengajuan</th>
                                                    <th>RBP</th>
                                                    <th>Qty Approval</th>
                                                    <th>Ket Princ Area</th>
                                                    <th>Status MPM</th>
                                                    <th>Ket MPM</th>
                                                </tr>
                                            </thead>
                                            <tbody>     
                                                <?php 
                                                foreach ($get_pengajuan_detail_accordion->result() as $a) : ?>
                                                <tr>                                          
                                                    <td><?= $a->kodeprod ?></td>
                                                    <td><?= $a->namaprod ?></td>
                                                    <td><?= $a->batch_number ?></td>
                                                    <td><?= $a->expired_date ?></td>
                                                    <td><?= $a->nama_outlet ?></td>
                                                    <td><?= $a->nama_alasan ?></td>
                                                    <td><?= $a->keterangan ?></td>
                                                    <td><?= $a->jumlah ?></td>
                                                    <td><?= number_format($a->rbp,2) ?></td>
                                                    <td><?= ($a->qty_approval <= 0 && $a->qty_approval != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->qty_approval</span>" : "$a->qty_approval" ?></td>
                                                    <td><?= ($a->qty_approval <= 0 && $a->qty_approval != null) ? "<span class='btn btn-danger btn-sm rounded'>$a->keterangan_principal_area</span>" : "$a->keterangan_principal_area" ?></td>
                                                    <td><?= ($a->status == 4) ? "<span class='btn btn-danger btn-sm rounded'>$a->nama_status</span>" : "$a->nama_status"  ?></td>
                                                    <td><?= ($a->status == 4) ? "<span class='btn btn-danger btn-sm rounded'>$a->deskripsi</span>" : "$a->deskripsi" ?></td>
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
</div>
