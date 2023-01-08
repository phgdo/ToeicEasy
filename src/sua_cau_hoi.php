<?php
    include_once '../function.php';
    // if(checkLogin());
    ChanNguoiDung();
    $flag = 0;
    if($_SESSION['level'] == 1){
        $flag = 1;
    }
    $question_id = $_GET['question_id'];
    $topicId = getTopicFromQuestion($question_id);
    if(isset($_POST['btnXoaAnh'])){
        xoaAnh($question_id);
    }
    if(isset($_POST['btnXoaAudio'])){
        xoaAudio($question_id);
    }

    if(isset($_POST['saveCauHoi'])){
        if(!empty($_POST['phan']) && !empty($_POST['sothutu']) && !empty($_POST['a']) && !empty($_POST['b']) && !empty($_POST['c']) && !empty($_POST['optradio'])){
            $part_id = $_POST['phan'];
            $sentence_id = $_POST['sothutu'];
            $a = $_POST['a'];
            $b = $_POST['b'];
            $c = $_POST['c'];
            $question = "NULL";
            $text = "NULL";
            $d = "NULL";
            if(!empty($_POST['question'])){
                $question = $_POST['question'];
            }
            if(!empty($_POST['text'])){
                $text = $_POST['text'];
            }
            if((int)$part_id !=2){
                $d = $_POST['d'];
            }
            $isCorrect = $_POST['optradio'];
            updateQuestion($question_id, $part_id, $sentence_id, $question, $text);

            if(!empty($_FILES['image']['name'])){
                $image = $_FILES['image']['name'];
                $imageTemp = $_FILES['image']['tmp_name'];
                
                updateImage($question_id, $image, $imageTemp);
            }

            if(!empty($_FILES['audio']['name'])){
                $audio = $_FILES['audio']['name'];
                $audioTemp = $_FILES['audio']['tmp_name'];
                
                updateAudio($question_id, $audio, $audioTemp);
            }
            if((int)$part_id!=2){
                $qu = array($a, $b, $c, $d);
                for($i=1; $i<=4; $i++){
                    if($i == (int)$isCorrect){
                        updateQuizOption($question_id, $i, $qu[$i-1], '1');

                    }
                    else{
                        updateQuizOption($question_id, $i, $qu[$i-1], '0');

                    }
    
                }
            }
            else{
                $qu = array($a, $b, $c);
                for($i=1; $i<=3; $i++){
                    if($i == (int)$isCorrect){
                        updateQuizOption($question_id, $i, $qu[$i-1], '1');

    
                    }
                    else{
                        updateQuizOption($question_id, $i, $qu[$i-1], '0');

                    }
    
                }
            }
            
        }
    }

    $question = getCauHoi2($question_id);
    $quizs = getQuizOptions($question_id);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa câu hỏi</title>
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
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Phần số:</label>
			  <input type="number" min = "1" max="7" class="form-control" id="ten_de" name="phan" placeholder="Nhập số phần" value="<?php echo $question['part_id']; ?>" >
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Câu hỏi số:</label>
			  <input type="number"min = "1" max="200" class="form-control" id="ten_de" name="sothutu" placeholder="Nhập số thứ tự của câu" value="<?php echo $question['sentence_id']; ?>">
			</div>
            <?php 
                if($question['part_id'] == "1" || $question['part_id'] == "2" || $question['part_id'] == "3" || $question['part_id'] == "4"){
            ?>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">File nghe: <?php echo $question['audio_path']; ?></label>
              <input type="file" name="audio" id="" value="">
              <?php 
                if(!empty($question['audio_path'])){
                    echo '<input type="submit" name="btnXoaAudio" value="Xoa file nghe">';
                }
              ?>
			</div>
            <?php
                }
            ?>
            <?php 
                if($question['part_id'] == "1" || $question['part_id'] == "3" || $question['part_id'] == "4"){
            ?>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">File ảnh: <?php echo $question['image_path']; ?></label>
              <input type="file" name="image" id="" value="">
              <?php 
                if(!empty($question['image_path'])){
                    echo '<input type="submit" name="btnXoaAnh" value="Xoa anh">';
                }
              ?>
			</div>
            <?php 
            }
            ?>
            <?php 
                if($question['part_id'] == "6" || $question['part_id'] == "7"){
            ?>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Văn bản:</label>
			  <input type="area" class="form-control" id="ten_de" name="text" placeholder="Nhập văn bản"value="<?php echo $question['text']; ?>" >
			</div>
            <?php 
            }
            ?>
            <?php 
                if($question['part_id'] != "1" && $question['part_id'] != "2"){
            ?>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Câu hỏi:</label>
			  <input type="text" class="form-control" id="ten_de" name="question" placeholder="Nhập câu hỏi" value="<?php echo $question['question']; ?>">
			</div>
            <?php 
            }
            ?>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án A:</label>
			  <input type="text" class="form-control" id="ten_de" name="a" placeholder="Nhập đáp án A" value="<?php echo $quizs[0]['opt']; ?>">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án B:</label>
			  <input type="text" class="form-control" id="ten_de" name="b" placeholder="Nhập đáp án B" value="<?php echo $quizs[1]['opt']; ?>">
			</div>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án C:</label>
			  <input type="text" class="form-control" id="ten_de" name="c" placeholder="Nhập đáp án C" value="<?php echo $quizs[2]['opt']; ?>">
			</div>
            <?php 
                if($question['part_id'] != "2"){
            ?>
            <div class="mb-3">
			  <label for="ten_de" class="form-label">Đáp án D:</label>
			  <input type="text" class="form-control" id="ten_de" name="d" placeholder="Nhập đáp án D" value="<?php echo $quizs[3]['opt']; ?>">
			</div>
            <?php 
            }
            ?>
            <div class="mb-3">
                <h6>Câu trả lời đúng:</h6>
			  <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="1" <?php if(  $quizs[0]['is_correct']==1){echo "checked";} ?>>(A)</label>
              <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="2" <?php if(  $quizs[1]['is_correct']==1){echo "checked";} ?>>(B)</label>
              <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="3" <?php if(  $quizs[2]['is_correct']==1){echo "checked";} ?>>(C)</label>
              <?php 
                if($question['part_id'] != "2"){
                ?>
              <label for="ten_de" class="form-label"><input type="radio" name="optradio" value="4" <?php if(  $quizs[3]['is_correct']==1){echo "checked";} ?>>(D)</label>
			<?php 
            }
            ?>
            </div>
            <input type="submit" class="btn btn-primary" name="saveCauHoi" value="Lưu">
	
			<a href="quan_ly_quiz.php?topic_id=<?php echo $topicId; ?>" class="btn btn-primary">Trở lại</a>
		</form>
		</div>
	</main>
</body>
<?php include 'footer.php'; ?>
</html>