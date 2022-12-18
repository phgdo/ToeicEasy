// Lấy ra 7 phần của đề toeic, mỗi phần có các đề nhỏ.
<?php 
    include_once '../function.php';
    $part = getPartOfToeic();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <title>Practice</title>
    <style>
        .main-content{
            display: flex;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php';?>
    <div class = "main-content">
        <div>
            <div>
                <?php 
                    foreach ($part as $value){
                        echo $value['id'] . "   " . $value['name'];

                    }
                ?>
            </div>
        </div>
        <div>

        </div>
    </div>
</body>
</html>