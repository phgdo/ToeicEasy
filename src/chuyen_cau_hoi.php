<?php
    include_once '../function.php';
    // if(checkLogin());
    ChanNguoiDung();
    $question_id = $_GET['question_id'];
    $currentTopic = getTopicFromQuestion($question_id);
    $topics = getTopic();
    if(isset($_POST['btnChuyen'])){
        if($currentTopic != $_POST['selectTopic']){
            echo '
            <script>
            alert("Chuyển câu hỏi sang chủ đề '.$_POST['selectTopic'].' thành công.");
            </script>
            ';
            chuyenCauHoi($_POST['selectTopic'], $question_id);
        }
        else if($currentTopic == $_POST['selectTopic']){
            echo '
            <script>
            alert("Hai chủ đề giống nhau.");
            </script>
            ';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyển câu hỏi</title>
    <!-- Begin bootstrap cdn -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="	sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- End bootstrap cdn -->
</head>
<body>
<?php include 'navbar.php';?>   
<main style="min-height: 100vh; max-width: 100%;">
    <div class="d-flex flex-wrap flex-column align-items-center" style="padding: 1%">
    <form action="" method="post">
        <select name="selectTopic" id="">
            <?php 
            foreach($topics as $topic){
            ?>
            <option <?php if($currentTopic == $topic['id']){echo 'selected="selected"'; } ?> value="<?php echo $topic['id'] ?>"><?php echo $topic['id']."-".$topic['name'] ?></option>
            <?php 
            }
            ?>
        </select>
        <input type="submit" value="Chuyển" name="btnChuyen">
    </form>
    <a href="quan_ly_quiz.php?topic_id=<?php echo $currentTopic; ?>" class="btn btn-primary">Trở lại</a>
    </div>
</main>
</body>
</html>