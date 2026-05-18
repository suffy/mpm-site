 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('to_excel')) {
  function to_excel($query, $filename='exceloutput') {
    $headers = ''; // just creating the var for field headers to append to below
    $data = ''; // just creating the var for field data to append to below
    
    //$objt =& get_instance();

    //$fields = $query->field_data();
    $fields = $query->list_fields();
    if ($query->num_rows() == 0) {
      echo "<p>No data for that report, please use your browser's back button</p>";
      return FALSE;
    } else {
      foreach ($fields as $field) {
        $headers .= $field. "\t";
      }

      foreach ($query->result() as $row) {
        $line = '';
        foreach($row as $value) {                                            
          if ((!isset($value)) OR ($value == "")) {
            $value = "\t";
          } else {
            $value = str_replace("\n", " ", $value); // for stupid line breaks that mess up the spreadsheet
            $value = str_replace('"', '""', $value);
            $value = '"' . $value . '"' . "\t";
          }
          $line .= $value;
        }
        $data .= trim($line)."\n";
      }

      $data = str_replace("\r","",$data);

      header("Content-type: application/x-msdownload");
      header("Content-Disposition: attachment; filename=$filename.xls");
      echo "$headers\n$data";  
    }
  }
}
?>  