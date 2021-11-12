<?php
  include '../partials/_dbconnect.php';
  session_start();
  $userId = $_SESSION['userId'];

  $Sql = "SELECT * FROM `orders` WHERE userId = $userId ORDER BY orderId DESC LIMIT 1;";
  $result = mysqli_query($conn, $Sql);
  $row = mysqli_fetch_assoc($result);
  $orderId = $row['orderId'];
  $amount = $row['amount'];
  $address1 = $row['address'];
  $address2 = $row['zipCode'];
  $address3 = $row['phoneNo'];
  $address = $address1." ".$address2." ".$address3;
  $Sql1 = "SELECT * FROM `orderitems` WHERE `orderId`='$orderId'";
  $result1 = mysqli_query($conn, $Sql1);
  $row1 = mysqli_fetch_assoc($result1);
  $pizzaId = $row1['pizzaId'];

  $Sql2 = "SELECT * FROM `pizza` WHERE `pizzaId`='$pizzaId'";
  $result2 = mysqli_query($conn, $Sql2);
  $row2 = mysqli_fetch_assoc($result2);
  $pinfo = $row2['pizzaName'];
  

  $Sql3 = "SELECT * FROM `users` WHERE `Id`='$userId'";
  $result3 = mysqli_query($conn, $Sql3);
  $row3 = mysqli_fetch_assoc($result3);
  $email = $row3['email'];
  $name1 = $row3['firstName'];
  $name2 = $row3['lastName'];
  $fname = $name1." ".$name2;
?>
<style type="text/css">
	.main {
		margin-left:30px;
		font-family:Verdana, Geneva, sans-serif, serif;
	}
	.text {
		float:left;
		width:180px;
	}
	.dv {
		margin-bottom:5px;
	}
</style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form>
    <div>
    	<h3>ORDER SUMMARY</h3>
    </div>
    <div class="dv">
    <span class="text"><label>Name:</label></span>    
    <span><input type="text" name="name" id="name" placeholder="Enter your name" value="<?php echo $fname?>" disabled/></span>
    <!-- <input type="textbox" name="amt" id="amt" placeholder="Enter amt"/><br/><br/> -->
    </div>
	<div class="dv">
    <span class="text"><label>Transaction/Order ID:</label></span>
    <span><input type="text" id="txnid" name="txnid" placeholder="Transaction ID" value="<?php echo $orderId?>" disabled/></span>
    </div>
    
    <div class="dv">
    <span class="text"><label>Amount:</label></span>
    <span><input type="text" id="amount" name="amount" placeholder="Amount"  value=<?php echo $amount ?> disabled/></span>    
    </div>
     
    <div class="dv">
    <span class="text"><label>Email ID:</label></span>
    <span><input type="text" id="email" name="email" placeholder="Email ID" value= <?php echo $email ?> disabled/></span>
    </div>
    
    <div class="dv">
    <span class="text"><label>Mobile/Cell Number:</label></span>
    <span><input type="text" id="mobile" name="mobile" placeholder="Mobile/Cell Number" value= <?php echo $address3 ?> disabled/></span>
    </div>
    <div><input type="button" name="btn" id="btn" value="Pay Now" onclick="pay_now()"/></div>
</form>
</div>

<script>
    function pay_now(){
        var name=jQuery('#name').val();
        var amt=jQuery('#amt').val();
        
         jQuery.ajax({
               type:'post',
               url:'payment_process.php',
               data:"amt="+amt+"&name="+name,
               success:function(result){
                   var options = {
                        "key": "rzp_test_coFOmB6TRvaala", 
                        "amount": 100, 
                        "currency": "INR",
                        "name": "Energy Kart",
                        "description": "Test Transaction",
                        "image": "logo_main.JPG",
                        "handler": function (response){
                           jQuery.ajax({
                               type:'post',
                               url:'payment_process.php',
                               data:"payment_id="+response.razorpay_payment_id,
                               success:function(result){
                                   window.location.href="thank_you.php";
                               }
                           });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
               }
           });
        
        
    }
</script>