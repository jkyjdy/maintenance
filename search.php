<?php  
  include 'model/koneksi_ajax.php';
  
  if(isset($_POST["query"])){
    $output = '';
    $key = "%".$_POST["query"]."%";
    $query = "SELECT * FROM input_barang WHERE nama LIKE ? LIMIT 10";

    if($dewan1 = $db1->prepare($query)) { // assuming $mysqli is the connection
        $dewan1->bind_param('s', $key);
        $dewan1->execute();
        // any additional code you need would go here.
    } else {
        $error = $db1->errno . ' ' . $db1->error;
        echo $error; // 1054 Unknown column 'foo' in 'field list'
    }

    $res1 = $dewan1->get_result();
 
    $output = '<ul class="list-unstyled">';
    if($res1->num_rows > 0){  
      while ($row = $res1->fetch_assoc()) {
        $output .= '<li>'.$row["nama"].'</li>';  
      }
    } else {
      $output .= '<li>Tidak ada yang cocok.</li>';  
    }  
    $output .= '</ul>';
    echo $output;
  }
?>