<?php
    include_once '../function.php';
    // if(checkLogin());
    $flag = 0;
    if($_SESSION['level'] == 1){
        $flag = 1;
    }
    $topicId = $_GET['topic_id'];
    ChanNguoiDung();

    
    $questions = getCauHoi($topicId);
    // var_dump($questions);

    if(isset($_POST['saveCauHoi'])){
        // Ít nhất là các trường này ko empty
        if(!empty($_POST['phan']) && !empty($_POST['sothutu']) && !empty($_POST['a']) && !empty($_POST['b']) && !empty($_POST['c']) && !empty($_POST['optradio'])){
            $part_id = $_POST['phan'];
            $sentence_id = $_POST['sothutu'];
            $a = $_POST['a'];
            $b = $_POST['b'];
            $c = $_POST['c'];
            $isCorrect = $_POST['optradio'];
            if($part_id=="1" || $part_id=="2" || $part_id=="3" || $part_id=="4"){
                if(!empty($_FILES['audio']['name'])){
                    $audio = $_FILES['audio']['name'];
                    $audioTemp = $_FILES['audio']['tmp_name'];
                    $question = "NULL";
                    $text = "NULL";
                    $d = "NULL";
                    if(!empty($_POST['d'])){
                        $d = $_POST['d'];
                    }
                    if(!empty($_POST['question'])){
                        $question = $_POST['question'];
                    }
                    if(!empty($_FILES['image']['name'])){
                        $image = $_FILES['image']['name'];
                        $imageTemp = $_FILES['image']['tmp_name'];
                        
                        themCauHoiCoAnhVaAudio($topicId, $sentence_id, $part_id, $question , $text,$audio, $audioTemp, $image, $imageTemp,$a, $b, $c, $d, $isCorrect);
                        echo '
                            <script>alert("Thêm thành công");</script>
                        ';
                    }
                    else{
                        themCauHoiCoAudio($topicId, $sentence_id, $part_id, $question , $text,$audio, $audioTemp,$a, $b, $c, $d, $isCorrect);
                        echo '
                            <script>alert("Thêm thành công");</script>
                        ';
                    }
                }
                else{
                    echo "Hay chon file audio";
                }
            }
            else if($part_id=="5" || $part_id=="6" || $part_id=="7"){
                $question = "NULL";
                    $text = "NULL";
                    $d = "NULL";
                    if(!empty($_POST['d'])){
                        $d = $_POST['d'];
                    }
                    if(!empty($_POST['question'])){
                        $question = $_POST['question'];
                    }
                    if(!empty($_POST['text'])){
                        $text = $_POST['text'];
                    }
                    if(!empty($_FILES['image']['name'])){
                        $image = $_FILES['image']['name'];
                        $imageTemp = $_FILES['image']['tmp_name'];
                        
                        themCauHoiCoAnh($topicId, $sentence_id, $part_id, $question , $text, $image, $imageTemp,$a, $b, $c, $d, $isCorrect);
                        echo '
                            <script>alert("Thêm thành công");</script>
                        ';
                    }
                    else{
                        themCauHoi($topicId, $sentence_id, $part_id, $question , $text,$a, $b, $c, $d, $isCorrect);
                        echo '
                            <script>alert("Thêm thành công");</script>
                        ';
                    }
            }
        }
        else{
            echo "Hay nhap du thong tin";
        }

        

        
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm câu hỏi</title>
    <!-- Begin bootstrap cdn -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="	sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- End bootstrap cdn -->
</head>
<body>
<main style="min-height: 100vh; max-width: 100%;">
<?php include 'navbar.php';?>

		<div class="d-flex justify-content-center">


		<form action="" method="POST" class="w-50" enctype="multipart/form-data">
        <h3>Thêm:</h3>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Phần số:</label>
			  <input type="number" min = "1" max="7" class="form-control" id="ten_de" name="phan" placeholder="Nhập số phần" >
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Câu hỏi số:</label>
			  <input type="number"min = "1" max="200" class="form-control" id="ten_de" name="sothutu" placeholder="Nhập số thứ tự của câu" value="">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">File nghe: </label>
              <input type="file" name="audio" id="">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">File ảnh:</label>
              <input type="file" name="image" id="">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Văn bản:</label>
			  <input type="area" class="form-control" id="ten_de" name="text" placeholder="Nhập văn bản" >
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Câu hỏi:</label>
			  <input type="text" class="form-control" id="ten_de" name="question" placeholder="Nhập câu hỏi" value="">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án A:</label>
			  <input type="text" class="form-control" id="ten_de" name="a" placeholder="Nhập đáp án A" value="">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án B:</label>
			  <input type="text" class="form-control" id="ten_de" name="b" placeholder="Nhập đáp án B">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án C:</label>
			  <input type="text" class="form-control" id="ten_de" name="c" placeholder="Nhập đáp án C">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án D:</label>
			  <input type="text" class="form-control" id="ten_de" name="d" placeholder="Nhập đáp án D">
			</div>
            <div class="mb-3">
                <h6>Câu trả lời đúng:</h6>
			  <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="1">(A)</label>
              <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="2">(B)</label>
              <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="3">(C)</label>
              <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="4">(D)</label>
			</div>
            <input type="submit" class="btn btn-primary" name="saveCauHoi" value="Lưu">
	
            <a href="quan_ly_quiz.php?topic_id=<?php echo $topicId; ?>" class="btn btn-primary">Trở lại</a>
		</form>
		</div>
	</main>
</body>
<?php include 'footer.php'; ?>
</html>