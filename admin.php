<?php

require("includes/config.php");


if(isset($_POST['username'])) {

  //if(isset($_POST['password']) && isset($_POST['access']) && isset($_POST['confirm']) && $_POST['password'] == $_POST['confirm'] && $_POST['password'] != '' && $_POST['access'] != '' && $_POST['username'] != ''  && $_POST['active'] != ''){

/////////////////////////Create user////////////////////////////
  
  $sec_email = $_POST['username'] . $_POST['sec_email'];
			$_POST['agent_code'] = serialize($_POST['agent_code']);
      $submit = query("insert into basic (basic1, basic2, basic3, active, bcc, name, company_name, address, phone, 1_payment, 2_payment, pcc, sabre_id, sabre_liniata,notes, agent_code, agency_role, main_agent_code, gsheet) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $_POST['username'], crypt($_POST['password']), $_POST['access'], $_POST['active'], $sec_email,  $_POST['name'], $_POST['company_name'], $_POST['address'], $_POST['phone'], $_POST['1_payment'], $_POST['2_payment'], $_POST['pcc'], $_POST['sabre_id'], $_POST['sabre_liniata'],$_POST['notes1'],$_POST['agent_code'], $_POST['agency_role'], $_POST['main_agent_code'], $_POST['gsheet']);

$email= $_POST['username'];

 
// Create unique token
        $token = getToken(14);
 
        $created_date = date("Y-m-d H:i:s");
        $expire_date = date("Y-m-d H:i:s", time() + 86400); // 24 hours after created_date for set password link to expire

        $submit = query("INSERT INTO password_recovery (email, token, created_date, expire_date) VALUES(?, ?, ?, ?)", $email, $token, $created_date, $expire_date); // set password link

        if ($submit === false) {
          apologize("Unfortunately there was an error. Please try again or contact an administrator.");
        }
        $rows = query("SELECT LAST_INSERT_ID() AS id");
        $id = $rows[0]["id"];
 
       //send email to User with login details
        
        $emailbody = $_POST['emailbody'];
    

 $body .=  "<center> <h1><strong>Welcome to agents.chaikel.com</strong></h1></center>"  ;
        //$body .= "<br><br>" . $rerror;
        $body .= "<br>
<h3>	Hi " . $_POST['name'] .",</h3>
	Welcome to the Chaikel Travel agent's portal! <br>
		You can use this portal to request ticketing, and view status of ticketing requests. <br><br><center>
		<h3>Here is the info you will need to log in</h3>";
        $body .= "<p>Username: " . $_POST['username'] ;

         $body .= "</p><p><a href='" . $url . urlencode($email)."&token=".urlencode($token)."'>Please click this link to set your password.</a><br><font size= '-2'>Please note: This link will expire in 24 hours.</font></p>";
        $body .= "</html>";



        $fromEmail = "travel@chaikel.com"; 
        $fromName = "Chaikel Travel";
        $replyEmail = "travel@chaikel.com";
        $replyName = "travel@chaikel.com";
        $toEmail = $_POST['username'];
        $toName = $_POST['firstname'] . " " . $_POST['lastname'];
        
        $subject = "Welcome to Chaikel Travel Agents Portal" ;

        $action = "emailBooking";


        email($fromEmail, $fromName, $replyEmail, $replyName, $toEmail, $toName, $bccEmail, $subject, $body, $action);

      
      redirect("admin.php");

  //} else {

    //apologize("Passwords did not match, please try again");

 // }
////////////////////////////////update user info/////////////////
}elseif(isset($_POST['user'])){
	$_POST['agent_code'] = serialize($_POST['agent_code']);

$submit = query("update basic set basic1=?, name=?, address=?, phone=?, company_name=?, 1_payment=?, 2_payment=?, sabre_id=?, pcc=?, sabre_liniata=?, active=?, basic3=?, notes=?, agent_code=? , agency_role=? , main_agent_code=?, gsheet=? where id=?", $_POST['user'], $_POST['name'],$_POST['address'],$_POST['phone'],$_POST['company_name'],$_POST['1_payment'],$_POST['2_payment'],$_POST['sabre_id'],$_POST['pcc'], $_POST['sabre_liniata'], $_POST['active'], $_POST['basic3'], $_POST['notes'],$_POST['agent_code'], $_POST['agency_role'], $_POST['main_agent_code'], $_POST['gsheet'], $_POST['id']);

redirect("admin.php");

}
// If changing status to active/suspended:
elseif ( isset($_POST['off']) || isset($_POST['on']) ) {
        
        if (isset($_POST['off'])) {
           $submit = query("UPDATE basic SET active = 1 WHERE basic1 = ?", $_POST['user']);
           
        }else{
         $submit = query("UPDATE basic SET active = 0 WHERE basic1 = ?", $_POST['user']);
        
        }
    redirect("admin.php");
    

}

////delete user/////////
elseif(isset($_GET['deletethisorder'])){
$submit = query("delete from basic where basic1 = ?", $_GET['username']);
redirect("admin.php");

}


else 
{



// Display User info from database

$users = query("select * from basic ORDER BY `id` DESC");

foreach ($users as $row)
  {
            $count = query("SELECT COUNT(id) FROM order_tbl WHERE created_by = ? ", $row["id"]);
          $sent = query("SELECT SUM(sent) as sent_total FROM order_tbl WHERE created_by = ? ", $row["id"]);
            $userlist[] = [
                  "username" => $row["basic1"],
	              "phone" => $row["phone"],
	              "name" => $row["name"],
                "address" => $row["address"],
                  "company_name" => $row["company_name"],
                  "basic3" => $row["basic3"],
                  "notes" => $row["notes"],
				          "cp" => $row["cp"],
                  "id" => $row["id"],
                  "1_payment" => $row["1_payment"],
                  "2_payment" => $row["2_payment"],
                  "pcc" => $row["pcc"],
                  "sabre_id" => $row["sabre_id"],
                  "sabre_liniata" => $row["sabre_liniata"],
                  "company_name" => $row["company_name"],
				          "active" => $row["active"],
                  "agent_code" => $row["agent_code"] ,
									"agency_role" => $row['agency_role'],
									"main_agent_code" => $row['main_agent_code'],
                  "gsheet" => $row['gsheet']
                  ];

  }



// count booking stats per user

$rows = query("SELECT * FROM basic ORDER BY `id` DESC");
foreach ($rows as $row)
  {
      
            $bookingstats[] = [
                // "id" => $row["id"],
                  "username" => $row["basic1"],
                  "fname" => $row["fname"],
				  "lname" => $row["lname"],
                  "number" => $row["number"],
				  "replyname" => $row["replyname"],
				  "active" => $row["active"]
                  ];
            

  }

// collect IP addresses


$rows = query("SELECT * FROM iplog ORDER BY `lastvisit` DESC ");

$orderlist = [];
foreach ($rows as $row)
  {
            $iplist[] = [
                  "lastvisit" => $row["lastvisit"],
                  "ip" => $row["ip"],
                  "numvisits" => $row["numvisits"],

                  ];
      
  }


$tires = query(" SELECT * FROM agent_com_tbl GROUP BY com_plus_per_off , com_plus_role ");




  

render("adminpanel.php", ["title" => "Admin Panel", "iplist" => $iplist, "tires" => $tires, "bookingstats" => $bookingstats, "userlist" => $userlist]);
}
?>
