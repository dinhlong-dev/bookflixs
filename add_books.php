<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if (isset($_POST['add_books'])) {
  $bname = mysqli_real_escape_string($conn, $_POST['bname']);
  $btitle = mysqli_real_escape_string($conn, $_POST['btitle']);
  $category = mysqli_real_escape_string($conn, $_POST['Category']);
  $price = $_POST['price'];
  $desc = mysqli_real_escape_string($conn, ($_POST['bdesc']));
  $img = $_FILES["image"]["name"];
  $img_temp_name = $_FILES["image"]["tmp_name"];
  $img_file = "./added_books/" . $img;


  if (empty($bname)) {
    $message[] = 'Tên sách chưa được điền';
  } elseif (empty($btitle)) {
    $message[] = 'Tên sách chưa được điền';
  } elseif (empty($price)) {
    $message[] = 'Giá sách chưa được điền';
  } elseif (empty($category)) {
    $message[] = 'Chưa chọn thể loại sách';
  } elseif (empty($desc)) {
    $message[] = 'Vui lòng nhập mô tả của sách';
  } elseif (empty($img)) {
    $message[] = 'Bạn chưa chọn ảnh';
  } else {

    $add_book = mysqli_query($conn, "INSERT INTO book_info(`name`, `title`, `price`, `category`, `description`, `image`) VALUES('$bname','$btitle','$price','$category','$desc','$img')") or die('Query failed');

    if ($add_book) {

      move_uploaded_file($img_temp_name, $img_file);
      $message[] = 'Sách mới đã được thêm thành công';
    } else {
      $message = 'Thêm sách không thành công';
    }
  }
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `book_info` WHERE bid = '$delete_id'") or die('query failed');
  header('location:add_books.php');
}


if(isset($_POST['update_product'])){

  $update_p_id = $_POST['update_p_id'];
  $update_name = $_POST['update_name'];
  $update_title = $_POST['update_title'];
  $update_description = $_POST['update_description'];
  $update_price = $_POST['update_price'];

  mysqli_query($conn, "UPDATE `book_info` SET name = '$update_name', title='$update_title', description ='$update_description', price = '$update_price',category='$update_category' WHERE bid = '$update_p_id'") or die('query failed');

  $update_image = $_FILES['update_image']['name'];
  $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
  $update_image_size = $_FILES['update_image']['size'];
  $update_folder = './added_books/'.$update_image;
  $update_old_image = $_POST['update_old_image'];

  if(!empty($update_image)){
     if($update_image_size > 2000000){
        $message[] = 'image file size is too large';
     }else{
        mysqli_query($conn, "UPDATE `book_info` SET image = '$update_image' WHERE bid = '$update_p_id'") or die('query failed');
        move_uploaded_file($update_image_tmp_name, $update_folder);
        unlink('uploaded_img/'.$update_old_image);
     }
  }

  header('location:./add_books.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/register.css">
  <title>Add Books</title>
</head>

<body>
  <?php
  include './admin_header.php'
  ?>
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '
        <div class="message" id="messages"><span>' . $message . '</span>
        </div>
        ';
    }
  }
  ?>
  
<a class="update_btn" style="position: fixed ; z-index:100;" href="total_books.php">Xem tất cả sách</a>
  <div class="container_box">
    <form action="" method="POST" enctype="multipart/form-data">
      <h3>Add Books To <a href="index.php"><span>Bookflix & </span><span>Chill</span></a></h3>
      <input type="text" name="bname" placeholder="Nhập tên sách" class="text_field ">
      <input type="text" name="btitle" placeholder="Nhập tên tác giả" class="text_field">
      <input type="number" min="0" name="price" class="text_field" placeholder="Nhập giá sách">
      <select name="Category" id="" required class="text_field">
            <option value="Adventure">Phưu lưu</option>
            <option value="Magic">Phép thuật</option>
            <option value="knowledge">Kiến thức</option>
         </select>
      <textarea name="bdesc" placeholder="Nhập mô tả sách" id="" class="text_field" cols="18" rows="5"></textarea>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="text_field">
      <input type="submit" value="Thêm sách" name="add_books" class="btn text_field">
    </form>
  </div>

  <section class="edit-product-form">

<?php
   if(isset($_GET['update'])){
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `book_info` WHERE bid = '$update_id'") or die('query failed');
      if(mysqli_num_rows($update_query) > 0){
         while($fetch_update = mysqli_fetch_assoc($update_query)){
?>
<form action="" method="post" enctype="multipart/form-data">
   <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['bid']; ?>">
   <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
   <img src="./added_books/<?php echo $fetch_update['image']; ?>" alt="">
   <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Nhập tên sách">
   <input type="text" name="update_title" value="<?php echo $fetch_update['title']; ?>" class="box" required placeholder="Nhập tên tác giả">
   <select name="update_category" value="<?php echo $fetch_update['category']; ?> required class="text_field">
         <option value="Adventure">Phưu lưu</option>
         <option value="Magic">Phép thuật</option>
         <option value="knowledge">Kiến thức</option>
      </select>
   <input type="text" name="update_description" value="<?php echo $fetch_update['description']; ?>" class="box" required placeholder="Nhập mô tả sản phẩm">
   <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Nhập giá sản phẩm">
   <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
   <input type="submit" value="Cập nhật" name="update_product" class="delete_btn" >
   <input type="reset" value="Xóa" id="close-update" class="update_btn" >
</form>
<?php
      }
   }
   }else{
      echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
   }
?>

</section>
  <section class="show-products">

   <div class="box-container">

      <?php
         $select_book = mysqli_query($conn, "SELECT * FROM book_info ORDER BY date DESC LIMIT 2;") or die('query failed');
         if(mysqli_num_rows($select_book) > 0){
            while($fetch_book = mysqli_fetch_assoc($select_book)){
      ?>
      <div class="box">
         <img class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt="">
         <div class="name">Tác giả: <?php echo $fetch_book['title']; ?></div>
         <div class="name">Tên sách: <?php echo $fetch_book['name']; ?></div>
         <div class="price">Giá: <?php echo $fetch_book['price']; ?>đ</div>
         <a href="add_books.php?update=<?php echo $fetch_book['bid']; ?>" class="update_btn">Cập nhật</a>
         <a href="add_books.php?delete=<?php echo $fetch_book['bid']; ?>" class="delete_btn" onclick="return confirm('Xóa sản phẩm này?');">Xóa </a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Chưa có sách được thêm!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `book_info` WHERE bid = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['bid']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="./added_books/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter Book Name">
      <input type="text" name="update_title" value="<?php echo $fetch_update['title']; ?>" class="box" required placeholder="Enter Author Name">
      <select name="update_category" value="<?php echo $fetch_update['category']; ?> required class="text_field">
            <option value="Adventure">Phưu lưu</option>
            <option value="Magic">Phép thuật</option>
            <option value="knowledge">Kiến thức</option>
         </select>
      <input type="text" name="update_description" value="<?php echo $fetch_update['description']; ?>" class="box" required placeholder="enter product description">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Cập nhật" name="update_product" class="delete_btn" >
      <input type="reset" value="Hủy" id="close-update" class="update_btn" >
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<script src="./js/admin.js"></script>
<script>
setTimeout(() => {
  const box = document.getElementById('messages');

  // 👇️ hides element (still takes up space on page)
  box.style.display = 'none';
}, 8000);
</script>
</body>

</html>