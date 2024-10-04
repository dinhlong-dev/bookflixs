<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}


if (isset($_POST['update_order'])) {

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $date = date("d.m.Y");
   mysqli_query($conn, "UPDATE `confirm_order` SET payment_status = '$update_payment',date='$date' WHERE order_id = '$order_update_id'") or die('query failed');
   $message[] = 'Trạng thái thanh toán đã được cập nhật!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `confirm_order` WHERE order_id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./css/hello.css">

   <style>
      .cart-btn1,
      .cart-btn2 {
         display: inline-block;
   margin-top: 0.4rem;
   padding:0.2rem 0.8rem;
   cursor: pointer;
   color:white;
   font-size: 15px;
   border-radius: .5rem;
   text-transform: capitalize;
      }

      .cart-btn1 {
         margin-left: 40%;
         background-color: red;
      }

      .cart-btn2 {
         background-color: #ffa41c;
         color: black;
      }

      .placed-orders .title {
         text-align: center;
         margin-bottom: 20px;
         text-transform: uppercase;
         color: black;
         font-size: 40px;
      }

      .placed-orders .box-container {
         max-width: 1200px;
         margin: 0 auto;
         display: flex;
         flex-wrap: wrap;
         align-items: center;
         gap: 20px;
      }

      .placed-orders .box-container .empty {
         flex: 1;
      }

      .placed-orders .box-container .box {
         flex: 1 1 400px;
         border-radius: .5rem;
         padding: 15px;
         border: 2px solid rgb(9, 218, 255);
         background-color: white;
         padding: 10px 20px;
      }

      .placed-orders .box-container .box p {
         padding: 10px 0 0 0;
         font-size: 20px;
         color: gray;
      }

      .placed-orders .box-container .box p span {
         color: black;
      }
      .message {
  position: sticky;
  top: 0;
  margin: 0 auto;
  width: 61%;
  background-color: #fff;
  padding: 6px 9px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  z-index: 100;
  gap: 0px;
  border: 2px solid rgb(68, 203, 236);
  border-top-right-radius: 8px;
  border-bottom-left-radius: 8px;
}
.message span {
  font-size: 22px;
  color: rgb(240, 18, 18);
  font-weight: 400;
}
.message i {
  cursor: pointer;
  color: rgb(3, 227, 235);
  font-size: 15px;
}
   </style>

</head>

<body>

   <?php include 'admin_header.php'; ?>
   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
        <div class="message" id= "messages"><span>' . $message . '</span>
        </div>
        ';
      }
   }
   ?>

   <section class="placed-orders">

      <h1 class="title">đơn đặt hàng</h1>

      <div class="box-container">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `confirm_order`") or die('query failed');
         if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_book = mysqli_fetch_assoc($select_orders)) {
         ?>
               <div class="box">
                  <p> Ngày đặt : <span><?php echo $fetch_book['order_date']; ?></span> </p>
                  <p> Mã đơn hàng : <span>#<?php echo $fetch_book['order_id']; ?> </p>
                  <p> Họ tên : <span><?php echo $fetch_book['name']; ?></span> </p>
                  <p> Số điện thoại : <span><?php echo $fetch_book['number']; ?></span> </p>
                  <p> Email : <span><?php echo $fetch_book['email']; ?></span> </p>
                  <p> Địa chỉ : <span><?php echo $fetch_book['address']; ?></span> </p>
                  <p> Phương thức thanh toán : <span><?php echo $fetch_book['payment_method']; ?></span> </p>
                  <p> Đơn hàng của bạn : <span><?php echo $fetch_book['total_books']; ?></span> </p>
                  <p> Tổng cộng : <span><?php echo $fetch_book['total_price']; ?>đ</span> </p>
                  <form action="" method="post">
                     <input type="hidden" name="order_id" value="<?php echo $fetch_book['order_id']; ?>">
                     Phương thức thanh toán :<select name="update_payment">
                        <option value="" selected disabled><?php echo $fetch_book['payment_status']; ?></option>
                        <option value="pending">Chờ xử lý</option>
                        <option value="completed">Hoàn thành</option>
                     </select>
                     <input type="submit" value="Cập nhật" name="update_order" class="cart-btn2">
                     <a class="cart-btn1" href="admin_orders.php?delete=<?php echo $fetch_book['order_id']; ?>" onclick="return confirm('Xóa đơn hàng này?');">Xóa</a>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Không có đơn hàng!</p>';
         }
         ?>
      </div>

   </section>


   <!-- custom admin js file link  -->
   <script src="js/admin_script.js"></script>
   <script>
      setTimeout(() => {
         const box = document.getElementById('messages');

         // 👇️ hides element (still takes up space on page)
         box.style.display = 'none';
      }, 8000);
   </script>

</body>

</html>