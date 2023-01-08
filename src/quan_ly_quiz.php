<?php
    include_once '../function.php';
    // if(checkLogin());
    ChanNguoiDung();
    $flag = 0;
    if($_SESSION['level'] == 1){
        $flag = 1;
    }
    $topicId = $_GET['topic_id'];
    $questions = getQuestions($topicId);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý quiz</title>
    <!-- Begin bootstrap cdn -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="	sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- End bootstrap cdn -->
</head>
<body>
<?php include 'navbar.php';?>   
    <main style="min-height: 100vh; max-width: 100%;">
    <div class="d-flex flex-wrap flex-column align-items-center" style="padding: 1%">
        <h3>Danh sách câu hỏi:</h3>
        <a href="them_cau_hoi.php?topic_id=<?php echo $topicId; ?>" class=" btn btn-outline-warning">Thêm câu hỏi</a>
		<table class="table table-bordered w-75">
            <thead>
                <tr>
                    <td><b>STT:</b>  </td>
                    <td><b>Topic Id:</b> </td>
                    <td><b>Part Id:</b> </td>
                    <td><b>Sentence Id:</b> </td>
                    <td><b>Trạng thái:</b> </td>
                    <td><b>Audio Path:</b> </td>
                    <td><b>Image Path:</b> </td>
                    <td><b>Question:</b> </td>
                    <td><b>Text:</b> </td>
                    <td><b>A:</b> </td>
                    <td><b>B:</b> </td>
                    <td><b>C:</b> </td>
                    <td><b>D:</b> </td>
                    <td><b>Thao tác:</b> </td>

                </tr>
            </thead>
            <tbody>
                <?php 
                $i=1;
                    foreach($questions as $value){
                        if($value['open'] == "1"){
                            $trangthai = 'Mở';
                        }
                        else{
                            $trangthai = 'Đóng';
                        }

                        if(isset($_POST['btnMo'])){
							
							MoCauHoi($_POST["id_phan_hoi"]);
							$alert = 'Chấm bài thành công';
							echo "<meta http-equiv='refresh' content='0'>";
						}
						if(isset($_POST['btnDong'])){
							DongCauHoi($_POST["id_phan_hoi"]);
							$alert = 'Chấm bài thành công';
							echo "<meta http-equiv='refresh' content='0'>";
						}
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value['topic_id'] ?></td>
                    <td><?php echo $value['part_id'] ?></td>
                    <td><?php echo $value['sentence_id'] ?></td>
                    <td><span class="btn btn-info"><?php echo $trangthai ?></span></td>
                    <td><?php echo $value['audio_path'] ?></td>
                    <td><?php echo $value['image_path'] ?></td>
                    <td><?php echo $value['question'] ?></td>
                    <td><?php echo $value['text'] ?></td>
                    <?php 
                        $quizs = getQuizOptions($value["question_id"]);
                        foreach($quizs as $quiz){
                            ?>
                            <td><?php echo $quiz['opt'] ?></td>
                            
                            <?php
                        }
                    ?>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="userId" value="<?php echo $value['question_id']; ?>">
                            <a href="xoa_cau_hoi.php?question_id=<?php echo $value['question_id']; ?>" class="btn btn-outline-warning">Xóa</a>
                            <a href="sua_cau_hoi.php?question_id=<?php echo $value['question_id']; ?>" class="btn btn-outline-warning">Sửa</a>
                            <a href="chuyen_cau_hoi.php?question_id=<?php echo $value['question_id']; ?>" class="btn btn-outline-warning">Chuyển câu hỏi</a>
                            <form method="post" name="formThayDoiTrangThai">
								<input type="hidden" value="<?php echo $value['question_id'] ?>" name="id_phan_hoi">
								<input type="submit" name="btnMo" value="Mở" class="btn btn-outline-success">
								<input type="submit" name="btnDong" value="Đóng" class="btn btn-outline-warning">
							</form>
                        </form>
                    </td>
                </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        <a href="test_select.php" class="btn btn-primary">Trở lại</a>

        </div>
	</main>
</body>
<?php include 'footer.php'; ?>
</html>