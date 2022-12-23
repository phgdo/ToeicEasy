<?php
    include_once '../function.php';
    if(checkLogin());
    $flag = 0;
    if($_SESSION['level'] == 1){
        $flag = 1;
    }
    $topicId = $_GET['topic_id'];

    
    $questions = getCauHoi($topicId);
    var_dump($questions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm và sửa câu hỏi</title>
    <!-- Begin bootstrap cdn -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="	sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- End bootstrap cdn -->
</head>
<body>
<main style="min-height: 100vh; max-width: 100%;">
		<div class="d-flex justify-content-center">


		<form action="" method="POST" class="w-50">
        <h3>Thêm và sửa:</h3>
        <a href="test_select.php" class="btn btn-primary">Part 1</a>
        <a href="test_select.php" class="btn btn-primary">Part 2</a>
        <a href="test_select.php" class="btn btn-primary">Part 3</a>
        <a href="test_select.php" class="btn btn-primary">Part 4</a>
        <a href="test_select.php" class="btn btn-primary">Part 5</a>
        <a href="test_select.php" class="btn btn-primary">Part 6</a>
        <a href="test_select.php" class="btn btn-primary">Part 7</a>
        <a href="test_select.php" class="btn btn-primary">All question</a>

        <?php 
                for ($i=1; $i<=200; $i++){
                    if($i>=1 && $i<=6){
                        echo "";
                    
        ?>
            <h5>Câu <?php echo $i; ?></h5>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án A:</label>
			  <input type="text" class="form-control" id="ten_de" name="ten_de" placeholder="Nhập đáp án A">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án B:</label>
			  <input type="text" class="form-control" id="ten_de" name="ten_de" placeholder="Nhập đáp án B">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án C:</label>
			  <input type="text" class="form-control" id="ten_de" name="ten_de" placeholder="Nhập đáp án C">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án D:</label>
			  <input type="text" class="form-control" id="ten_de" name="ten_de" placeholder="Nhập đáp án D">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">File nghe:</label>
              <input type="file" name="" id="">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">File ảnh:</label>
              <input type="file" name="" id="">
			</div>

        <?php 
            
                }
            }
        ?>

			<div class="mb-3">
            <h5>Câu <?php echo $i; ?></h5>
			  <label for="ten_de" class="form-label">Câu hỏi:</label>
			  <input type="text" class="form-control" id="ten_de" name="ten_de" placeholder="Nhập câu hỏi">
			</div>
            <div class="mb-3">
			  <label for="nam_de" class="form-label">Năm:</label>
			  <input type="number" class="form-control" id="nam_de" name="nam_de" placeholder="Nhập tên chủ đề">
			</div>
            <div class="mb-3">
			  <label for="so_de" class="form-label">Số thứ tự:</label>
			  <input type="number" class="form-control" id="so_de" name="so_de" placeholder="Nhập tên chủ đề">
			</div>
            <?php 
                
            ?>		
            <input type="submit" class="btn btn-primary" name="saveChuDe" value="Lưu">
	
			<a href="test_select.php" class="btn btn-primary">Trở lại</a>
		</form>
		</div>
	</main>
</body>
</html>