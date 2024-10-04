<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./css/hello.css">

<style>
    .placed-orders .title{
  text-align: center;
  margin-bottom: 20px;
  text-transform: uppercase;
  color:black;
  font-size: 40px;
}
   .placed-orders .box-container{
  max-width: 1200px;
  margin:0 auto;
  display:flex;
  flex-wrap: wrap;
  align-items: center;
  gap:20px;
}

.placed-orders .box-container .empty{
  flex:1;
}

.placed-orders .box-container .box{
  flex:1 1 400px;
  border-radius: .5rem;
  padding:15px;
  border:2px solid brown;
  background-color: white;
  padding:10px 20px;
}

.placed-orders .box-container .box p{
  padding:10px 0 0 0;
  font-size: 20px;
  color:gray;
}

.placed-orders .box-container .box p span{
  color:black;
}
</style>

</head>
<body>
   
<?php include 'index_header.php'; ?>
<section class="placed-orders">

   <h1 class="title">Đơn đặt hàng</h1>

   <div class="box-container">

      <?php
        $select_book = mysqli_query($conn, "SELECT * FROM `confirm_order`WHERE user_id = '$user_id' ORDER BY order_date DESC") or die('query failed');
        if(mysqli_num_rows($select_book) > 0){
            while($fetch_book = mysqli_fetch_assoc($select_book)){
      ?>
      <div class="box">
         <p> Ngày đặt hàng : <span><?php echo $fetch_book['order_date']; ?></span> </p>
         <p> Id đơn hàng : <span># <?php echo $fetch_book['order_id']; ?> </p>
         <p> Tên : <span><?php echo $fetch_book['name']; ?></span> </p>
         <p> Số điện thoại : <span><?php echo $fetch_book['number']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_book['email']; ?></span> </p>
         <p> Địa chỉ : <span><?php echo $fetch_book['address']; ?></span> </p>
         <p> Phương thức thanh toán : <span><?php echo $fetch_book['payment_method']; ?></span> </p>
         <p> Đơn hàng của bạn : <span><?php echo $fetch_book['total_books']; ?></span> </p>
         <p> Tổng tiền hàng : <span><?php echo $fetch_book['total_price']; ?>đ</span> </p>
         <p> Tình trạng thanh toán : <span style="color:<?php if($fetch_book['payment_status'] == 'pending'){ echo 'orange'; }else{ echo 'green'; } ?>;"><?php echo $fetch_book['payment_status']; ?></span> </p>
         <p><a href="invoice.php?order_id=<?php echo $fetch_book['order_id']; ?>" target="_blank">In biên lai</a></p>
         </div>
         <!-- <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?php echo $fetch_book['order_id']; ?>">
         </form> -->
      <?php
       }
      }else{
         echo '<p class="empty">Bạn chưa có đơn hàng nào!!!!</p>';
      }
      ?>
   </div>

</section>








<?php include 'index_footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>