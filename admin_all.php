<?php

require("includes/config.php");

 if (!isset($_SESSION["admin"]))
   {
      header("location: user.php");
   }

if     ($_SESSION['id'] == '2'){
        $submit = query("UPDATE new_request SET new_c=? WHERE id=?", 0,1);
}elseif ($_SESSION['id'] == '434'){
        $submit = query("UPDATE new_request SET new_e=? WHERE id=?", 0,1);
}elseif ($_SESSION['id'] == '432'){
        $submit = query("UPDATE new_request SET new_d=? WHERE id=?", 0,1);
}

/*
$rows = query("SELECT * FROM flight_tbl WHERE (status = 1 or status = 2 or status = 4 or status = 3) && (archive_u = 0) && created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE() &&  created_by = ?

UNION ALL
SELECT * FROM  mco_tbl   WHERE (status = 1 or status = 2 or status = 4 or status = 3) && (archive_u = 0) && created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE() && created_by = ?

UNION ALL
SELECT * FROM exch  WHERE (status = 1 or status = 2 or status = 4 or status = 3) && (archive_u = 0) && created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE() && created_by = ? ORDER BY FIELD (status,3,2,1,4), created_at DESC", $_SESSION['id'], $_SESSION['id'], $_SESSION['id']); */

if(isset($_SESSION["admin"])){

$rows = query("SELECT * FROM flight_tbl 

UNION ALL
SELECT * FROM  mco_tbl   

UNION ALL
SELECT * FROM exch   ORDER BY status, created_at ASC"); }

else{


$rows = query("SELECT * FROM flight_tbl WHERE (status = 1 or status = 2 or status = 4 or status = 3)  &&  created_by = ?

UNION ALL
SELECT * FROM  mco_tbl   WHERE (status = 1 or status = 2 or status = 4 or status = 3)  && created_by = ?

UNION ALL
SELECT * FROM exch  WHERE (status = 1 or status = 2 or status = 4 or status = 3)  && created_by = ? ORDER BY FIELD (status,3,2,1,4), created_at ASC", $_SESSION['id'], $_SESSION['id'], $_SESSION['id']);

}





$orderlist = [];
foreach ($rows as $row)
  {
		  $orderlist[] = [
	              "created_at" => $row["created_at"],
			      "id" => $row["uniqueordertoken"],
				  "adults" => $row["adults"],
				  "infants" => $row["infants"],
			      "pnr" => $row["pnr"],
				  "airline" => $row["airline"],
				  "total" => $row["total"],
				  "base" => $row["base"],
			      "first_pax" => $row["first_pax"],
				  "status" => $row["status"],
			      "exe_note" => $row["exe_note"],
				  "last_day" => $row["last_day"],
				  "urgent" => $row["urgent"],
				  "issue_published" => $row["issue_published"],
				  "correct_amount" => $row["correct_amount"],
				  "created_by" => $row["created_by"],
				  "is_correct" => $row["is_correct"],
				  "int_note" => $row["int_note"],
				  "pax_incomplete" => $row["pax_incomplete"],
				  "seg_incomplete" => $row["seg_incomplete"],
				  "flights" => $row["flights"],
				  "pax_all" => $row["pax_all"],
				  "segments" => $row["segments"],
				  "mco" => $row["mco"],
				  "3" => $row["3"],
				  "archive" => $row["archive"],
				  "price" => $row["price"],
				  "email_flight" => $row["email_flight"],
				  "mco" => $row['mco'],
       			  "issued_israel" => $row['issued_israel'],
      		      "com_fee" => $row['com_fee'],
         		  "com_amount" => $row['com_amount'],
							"commission_claimed" => $row['commission_claimed'],
        		  "error" => $row['error'],
       		      "error_cor" => $row['error_cor'],
         	   	  "archive_u" => $row['archive_u'],
         	   	  "email" => $row['email'],
          		  "agent_code" => $row['agent_code'],
          		  "error_choice" => $row['error_choice'],
			        	"completed_by" => $row['completed_by'],
          		  "yq" => $row['yq'],
                "pos" => $row['pos']

			      ];
      
  }

render("admin_all_view.php",  ["title" => "Submit PNR", "orderlist" => $orderlist] ); 


?>

