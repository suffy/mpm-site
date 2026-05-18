<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->
  
  <title>MPM Site - Olshop</title>

  <style type="text/css">
  
  
  table {
    border-collapse: collapse;
   }

  th {
    /* height: 70px; */
    padding: 10px;
  }

  td{
    padding: 10px;
  }  

  .footer{
    height: 5px;
    padding-top: 5px;
    border: 0px solid;
  }

  .footer_content{
    height: 5px;
    padding-top: 50px;
    border: 0px solid;
  }

  </style>
  
</head>
<body>

    <table class="table" border="1">
        <thead>
            <tr>
                <th>Kodeprod</th>
                <th>Namaprod</th>
                <th>QTY</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($get_summary->result() as $key) {
            ?>
            <tr>
                <td><?= $key->kodeprod_mpm; ?></td>
                <td><?= $key->namaprod; ?></td>
                <td><?= $key->total_qty; ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <!-- <tr>
                <td colspan="3">PIC</td>
            </tr> -->
            <tr>
                <td class="footer" align="center">diserahkan oleh</td>
                <td class="footer" align="center" colspan="2">diterima oleh</td>
            </tr>
            <tr>
                <td class="footer_content" align="center">(__________)</td>
                <td class="footer_content" align="center" colspan="2">(__________)</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>