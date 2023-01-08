<?php 
    include_once '../function.php';
    // if(checkLogin());
    $topic = getTopic();
    $flag = 0;
    if($_SESSION['level'] == 1){
        $flag = 1;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn đề thi</title>

    <link href="../assets/css/test.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>
<body>
<main style="min-height: 100vh; max-width: 100%;">

    <?php include 'navbar.php'; ?>

    <?php 
    //Hiển thị nút thêm đề bài khi có quyền admin
        if($flag == 1){
            echo '<a href="them_de.php" class="btn btn-primary">Thêm đề</a>';
        } 
    ?>
    <div class="list-content">
    FULL TEST

        <div class="practice-list">
    <?php 
        foreach($topic as $value){
    ?>
            <a href="toeictest.php?topicId=<?php echo $value['id']; ?>">
            <div class="practice-list-item">
                <div class="practice-item-name"><?php echo $value['name']; ?></div>
                <div class="practice-item-progress">
                <?php 
                    if($flag == 1){
                        echo '<a href="sua_de.php?topic_id='.$value['id'].'" class="btn btn-primary">Sửa</a>';
                        echo '<a href="quan_ly_quiz.php?topic_id='.$value['id'].'" class="btn btn-primary">Quản lý quiz</a>';
                        echo '<a href="quan_ly_bai_lam.php?topic_id='.$value['id'].'" class="btn btn-primary">Quản lý bài làm</a>';
                    } 
                ?>
                    <div class="progress-box"><?php echo $score = getScoreFromExams($value['id'], $_SESSION['userId']) . " pts"; ?></div>
                </div>
            </div>
            </a>
    <?php 
        }
    ?>
        </div>
    </div>
    </main>
</body>
<?php include 'footer.php'; ?>
</html>