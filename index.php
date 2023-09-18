<?php
// Connect With DataBase
$conn = mysqli_connect("localhost", 'root', '', 'shop');
########  CRUD ########
$message = null;
$count  = 1;

// Create Method
if (isset($_POST['send'])) {
    $name = $_POST['productName'];
    $category = $_POST['productCategory'];
    $price = $_POST['productPrice'];
    $insert = "INSERT INTO `products` VALUES(NULL , '$name' ,'$category' , $price , Default) ";  //TExt
    $i =   mysqli_query($conn, $insert); // Run
    if ($i) {
        $message =  "Insert Successfully";
    }
}

// Read 
$select = "SELECT * FROM  `products`"; // Query
$allData  = mysqli_query($conn, $select); // Run

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = "DELETE FROM  `products` WHERE id = $id";
    $d  = mysqli_query($conn, $delete); // Run 
    header("location: index.php ");
}

// Update
$name = "";
$price = "";
$category = "";

$update = false;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $selectOneRow = "SELECT * FROM  `products` where id =$id"; // Query
    $oneRow  = mysqli_query($conn, $selectOneRow); // Run
    $rowData = mysqli_fetch_assoc($oneRow);
    $name = $rowData['name'];
    $price = $rowData['price'];
    $category = $rowData['category'];
    $update = true;

    if (isset($_POST['update'])) {
        $name = $_POST['productName'];
        $category = $_POST['productCategory'];
        $price = $_POST['productPrice'];

        $update = "UPDATE products SET name = '$name' , category='$category' , price=$price where id =$id ";
        mysqli_query($conn, $update);
        header("location: index.php ");
    }
}


if (isset($_GET['status'])) {
    $id = $_GET['status'];
    $update = "UPDATE products SET status = 'soldOut' where id =$id ";
    
    mysqli_query($conn, $update);
    header("location: index.php ");
}
if (isset($_GET['light'])){
    $s= 'light';
    $mode_col="UPDATE mode SET mode_type= ('$s')";
    $z= mysqli_query($conn,$mode_col);
   
}
if (isset($_GET['dark'])){
    $s='dark';
   $mode_col="UPDATE mode SET mode_type= ('$s')";
    $z= mysqli_query($conn,$mode_col);
}
$mod="SELECT `mode_type`FROM `mode`";
$Dmod=mysqli_query($conn,$mod);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./bootstrap.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/all.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="css.css">

</head>
<?php if (isset($_GET['dark'])):?>
<body class="dark">
    <?php else:?>
        <body class="light">
            <?php endif;?>

    <?php if ($update) :  ?>
        <h1 class="text-center text-danger mt-5">Update Product : <?= $name ?> </h1>
    <?php else : ?>
        <h1 class="text-center mt-5">Create New Product </h1>
    <?php endif; ?>
    
    <?php if (isset($_GET['dark'])):?>
<a class="btn btn-primary ss" href=?light name="light" value="light" >light</a>
<?php else : ?>

    <a class="btn btn-primary ss" href=?dark name="dark" value="dark" >dark</a>
    
    <?php endif; ?>
    


    
    

    <div class="container col-md-6">
        <?php if ($message != null) :  ?>
            <div class="alert alert-success text-center">
                <h6><?= $message ?> </h6>
            </div>
        <?php endif ?>
        <a href=?dawy>dawy</a>
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" value="<?= $name  ?>" name="productName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="productCategory" class="form-control">
                            <?php if ($update) :  ?>
                                <option value="<?= $category ?>"><?= $category ?></option>
                            <?php endif; ?>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Kids">Kids</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Price</label>
                        <input type="number" value="<?= $price  ?>" name="productPrice" class="form-control">
                    </div>

                    <div class="d-grid">
                        <?php if ($update) :  ?>
                            <button name="update" class="btn btn-warning">Update </button>

                        <?php else : ?>
                            <button name="send" class="btn btn-info">Create </button>
                        <?php endif; ?>

                    </div>

                </form>
            </div>
        </div>
    </div>





    <?php if (!$update) :  ?>
        <h1 class="text-center mt-4">List Products </h1>
        <div class="container col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-dark">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>
                        </tr>

                        <?php foreach ($allData as $items) : ?>
                            <tr>
                                <td> <?= $count++ ?> </td>
                                <td> <?= $items['id'] ?> </td>
                                <td> <?= $items['name'] ?> </td>
                                <td> <?php if ($items['category'] == 'Male') { ?>
                                        MALE
                                    <?php } else if ($items['category'] == 'Kids') { ?>
                                       KIDS
                                    <?php } else { ?>
                                      FEMALE
                                    <?php } ?>
                                </td>
                                <td> <?= $items['price'] ?> </td>
                                <td> 
                                </td>
                                <td> <a href="?edit=<?= $items['id'] ?>"> <button class="btn btn-warning">update</button> </a> </td>
                                <td> <a onclick="return confirm('Are Your Sure ?')" href="?delete=<?= $items['id'] ?>"> <button class="btn btn-danger">delete</button></a> </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>