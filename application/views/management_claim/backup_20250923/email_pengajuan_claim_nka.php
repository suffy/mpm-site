<!doctype html>
<html lang="en">
    <head>
        <style type="text/css">

        .button_accept {
            position: absolute;
            top:90%;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 8px;
            padding-right: 8px;
            background-color:darkslategray;
            color: #fff;
            border:1px solid darkslategray ;
            border-radius: 5px;
        }
        .button_reject {
            position: absolute;
            top:90%;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 8px;
            padding-right: 8px;
            background-color:orangered;
            color: #fff;
            border:none;
        }

        table
        {
            border-collapse: collapse;
        }

        th, td 
        {
            padding: 5px;
            text-align: left;
        }
        </style>
    </head>

    <body>

        <p>Dear Bapak/Ibu <?= $username; ?></p>
        <p>Berikut adalah Pengajuan Claim yang membutuhkan verifikasi anda :</p>
        
        <table border="0">
            <tr>
                <td width="20%"> - No. Pengajuan</td>
                <td width="50%">: <?= $get_data->row()->nomor_ajuan; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Branch | Site</td>
                <td width="50%">: <?= $get_data->row()->branch_name. ' - ' .$get_data->row()->nama_comp. ' ('.$get_data->row()->site_code.')'; ?></td>
            <tr>
                <td width="20%"> - No. Klaim</td>
                <td width="50%">: <?= $get_data->row()->nomor_klaim; ?></td>
            </tr>
            <tr>
                <td width="20%"> - No. Invoice/SKP/Trading Term</td>
                <td width="50%">: <?= $get_data->row()->nomor_invoice; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Kategori</td>
                <td width="50%">: <?= $get_data->row()->kategori; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Channel</td>
                <td width="50%" style="text-transform: uppercase;">: <?= $get_data->row()->channel; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Periode</td>
                <td width="50%">: 
                    <?php
                        if($get_data->row()->periode_end != null){
                            echo date( 'd F Y', strtotime($get_data->row()->periode_start)) . ' - ' . date( 'd F Y', strtotime($get_data->row()->periode_end));
                        } else {
                            echo date( 'F Y', strtotime($get_data->row()->periode_start));
                        }
                    ;?>
                </td>
            </tr>
            <tr>
                <td width="20%"> - Keterangan</td>
                <td width="50%">: <?= $get_data->row()->keterangan ?></td>
            </tr>
            <tr>
                <td width="20%"> - Nominal DPP</td>
                <td width="50%">: Rp. <?= number_format($get_data->row()->nominal_dpp); ?></td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
                <td width="20%"> - Verifikasi KAM at</td>
                <td width="50%">: <?= $get_data->row()->principal_area_at; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Verifikasi KAM by</td>
                <td width="50%" style="text-transform: capitalize;">: <?= $get_data->row()->username_kam; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Verifikasi <?= $get_data->row()->channel == 'NKA' ? 'MPM' : 'Principal' ; ?> at</td>
                <td width="50%">: <?= $get_data->row()->mpm_at; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Verifikasi <?= $get_data->row()->channel == 'NKA' ? 'MPM' : 'Principal' ; ?>  by</td>
                <td width="50%" style="text-transform: capitalize;">: <?= $get_data->row()->username_mpm; ?></td>
            </tr>

            <tr>
                <td width="20%"> - Verifikasi Admin MPM at</td>
                <td width="50%">: <?= $get_data->row()->admin_mpm_at; ?></td>
            </tr>
            <tr>
                <td width="20%"> - Verifikasi Admin MPM by</td>
                <td width="50%" style="text-transform: capitalize;">: <?= $get_data->row()->username_admin_mpm; ?></td>
            </tr>
            <tr>
                <td width="20%"><b> - Status</b></td>
                <td width="50%" style="text-transform: uppercase;"><b>: <?= $get_data->row()->nama_status; ?></b></td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <!-- <tr>
                <td width="50%">
                    <a href='<?= base_url()."management_claim/ajuan_claim_nka_detail/$signature" ?>'><button type="button" class="button_accept">Verifikasi</button></a>
                </td>
            </tr> -->
        </table>
        <br>
    
        <p>Log History Proses</p>
        <table border = 1>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">User -> On Duty</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Created At</th>
                <th class="text-center">Status</th>
            </tr>
            <?php 
                $no = 1;
                foreach ($get_data_log->result() as $key => $value) {?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center" style="text-transform: uppercase;">
                            <?= implode(' / ',$user[$key]); ?> -> 
                            <strong>
                                <?= implode(' / ',$pic[$key]); ?>
                            </strong>
                        </td>
                        <td><?= $value->keterangan ?></td>
                        <td class="text-center"><?= date('d M Y H:i:s', strtotime($value->created_at)); ?></td>
                        <td class="text-center" style="text-transform: uppercase;"><?= $value->nama_status ?></td>
                    </tr>
            <?php } ?>
        </table>  
    </body>
</html>