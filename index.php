<?php
    include ('simple_html_dom.php');


    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "stock_db";

    $conn = mysqli_connect($servername,$username,$password,$database);

    $html = new simple_html_dom();
    $html = file_get_html('http://www.nepalstock.com/todaysprice');
    $data = array(array());
    $i=0;
    
    foreach($html->find('tr') as $tr) 
      {
          foreach($tr->find('td') as $td)
            {
              $data[$i][] = $td->innertext ;
            // echo $data;
        
            } 
            $i++;

       }

   
   unset($data[0]);
   unset($data[count($data)]);
   unset($data[count($data)]);
   unset($data[count($data)]);
   unset($data[count($data)]);
/*   unset($data[count($data)-1]);
   unset($data[count($data)-1]);
   unset($data[count($data)-1]);*/

   $fields = "'" . implode ("','", array_shift($data)) . "'";

   $fields = array("sn","traded_companies","no_of_transaction","max_price","min_price","closing_price","traded_shares","amount","previous_closing","difference_rs");
   $fields = implode(',', $fields);
   $values = array();
    

   foreach($data as $rowValues){
      foreach($rowValues as $key => $rowValue){
            $rowValues[$key] = mysqli_real_escape_string($conn,$rowValues[$key]); 
      }

      $values[] = "('" . implode("','", $rowValues) . "')";
   }

   if(!$conn) {
        die("could not connect" . mysqli_connect_error());
   }

   $imp = implode(",", $values);

    $query = "INSERT INTO stock_tbl($fields) VALUES ($imp)";
    if (mysqli_query($conn, $query)) {
    echo "New record created successfully";
      }    else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);




?>