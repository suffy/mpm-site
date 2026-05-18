<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Pending Biop</title>
    <style>
        /* (Style yang sudah ada tetap dipertahankan) */
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa; display: flex; justify-content: center;
            align-items: center; min-height: 100vh; padding: 20px;
        }
        
        .popup-wrapper {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.6); display: flex;
            justify-content: center; align-items: center; z-index: 1000;
        }
        
        .popup-container {
            background-color: white; border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 95%; max-width: 800px; /* Diperlebar sedikit agar tabel lega */
            padding: 30px; position: relative;
            max-height: 90vh; overflow-y: auto;
        }

        .popup-header h1 { color: #e74c3c; font-size: 24px; font-weight: 600; }
        
        /* CSS Tambahan untuk Tabel */
        .table-responsive { width: 100%; overflow-x: auto; margin-top: 15px; }
        
        table {
            width: 100%; border-collapse: collapse; margin-bottom: 20px;
            font-size: 14px;
        }
        
        table th, table td {
            padding: 12px; border: 1px solid #eee; text-align: left;
        }
        
        table th { background-color: #f8f9fa; color: #333; font-weight: 600; }
        
        .row-highlight { background-color: #fff9db; font-weight: bold; }
        
        .badge {
            padding: 4px 8px; border-radius: 4px; font-size: 12px;
            background-color: #e74c3c; color: white;
        }

        .popup-close {
            position: absolute; top: 15px; right: 20px; width: 32px;
            height: 32px; background-color: #f1f3f6; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            text-decoration: none; color: #7f8c8d; cursor: pointer;
        }
        
        .popup-button {
            flex: 1; min-width: 200px; padding: 12px 20px; border-radius: 6px;
            font-weight: 600; cursor: pointer; text-align: center;
            text-decoration: none; display: inline-block;
        }
        
        .primary-button { background-color: #3498db; color: white; }
        .secondary-button { background-color: #f1f3f6; color: #34495e; }
    </style>
</head>
<body>
    <div class="popup-wrapper" id="popup">
        <div class="popup-container">
            <div class="popup-header">
                <h1>Perhatian: Pending Respons BIOP</h1>
            </div>
            
            <div class="popup-content">
                <div class="info-item">
                    <div class="info-title">Halo, <?php echo $this->session->userdata('username'); ?></div>
                    <div class="info-desc">
                        Berdasarkan pantauan sistem, terdapat beberapa status yang memerlukan respons segera. 
                        Mohon cek kembali list di bawah ini:
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama PIC</th>
                                <th>Status Pending</th>
                                <th>Count</th>
                                <th>Max Response Days</th>
                                <th>Avg Response Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($list_pending as $row): ?>
                                <?php 
                                    // Beri highlight jika ini milik user yang sedang login
                                    $is_mine = ($row->pic_on_duty == $this->session_id); 
                                ?>
                                <tr class="<?php echo $is_mine ? 'row-highlight' : ''; ?>">
                                    <td>
                                        <?php echo strtoupper($row->nama_user); ?>
                                        <?php if($is_mine): ?> <span class="badge">Milik Anda</span> <?php endif; ?>
                                    </td>
                                    <td><?php echo $row->nama_status; ?></td>
                                    <td align="center"><?php echo $row->count; ?></td>
                                    <td align="center"><?php echo $row->max_pending_days; ?> Hari</td>
                                    <td align="center"><?php echo $row->avg_pending_days; ?> Hari</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="popup-actions">
                <a href="<?php echo base_url() ?>management_biop" class="popup-button primary-button">
                    Menuju menu biop
                </a>
                <a href="<?php echo base_url() ?>management_office/dashboard_new" class="popup-button secondary-button">
                    Menuju dashboard
                </a>
            </div>
            
            <a class="popup-close" href="<?php echo base_url() ?>management_office/dashboard_new">×</a>
        </div>
    </div>
</body>
</html>