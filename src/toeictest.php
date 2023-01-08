
<?php 
    include_once '../function.php';
    // checkLogin();
    $topicId = $_GET['topicId'];
    $questions = getQuestions($topicId);
    shuffle($questions);
    $flag = 0;
    if(checkExamExists($topicId, $_SESSION['userId']) == true){
            //Nếu user chưa làm bài này thì tạo 1 quiz mới
        $examId = startQuiz($topicId, $_SESSION['userId']);
        $flag = 0;
    }
    else{
        $examId = getExamId($topicId, $_SESSION['userId']);
        // var_dump(getValueForQuizAns($examId));
        $flag = 1;
    }
    
    //Xử lý nút nộp bài
    if(isset($_POST['btnSubmit'])){
        //Nếu không thấy optradio có nghĩa là user không chọn bất cứ đáp án nào trong toàn bộ câu hỏi
        if(!isset($_POST['optradio'])){
            foreach($questions as $question){
                getQuizAnswer($examId, $question['question_id'], "0");
            }
        }
        else{
            //User đã chọn ít nhất 1 câu trả lời trong toàn bộ câu hỏi
            foreach($_POST['optradio'] as $option_num => $option_val){
                getQuizAnswer($examId, $option_num, $option_val);
            }
        }
        //Tính điểm
        calScore($examId);
        // kết thúc quiz
        finishQuiz($examId);
    }
    //Kiểm tra xem đã nộp bài chưa (true là làm rồi, false là chưa làm) để ẩn nút Submit và hiển thị đáp án
    $done = 0;
    if(CheckExamDone($examId) == true){
        $done = 1;
    }
    else{
        $done = 0;
    }

    //Xu ly nut reset
    if(isset($_POST['btnReset'])){
        ResetExam($examId);
        //Refesh lai trang 
        header("Refresh:0");
    }

    // Mảng các chữ cái đại diện cho các câu
    $letters = array("(A) ", "(B) ", "(C) ", "(D) ");
    $score = getScoreFromExams($topicId, $_SESSION['userId']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/test.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <title>Test</title>
    <style>
        .main-content{
            display: flex;
        }
    </style>
    
    <?php 
        if($done==0){
    ?>
    <script>
        // Set the date we're counting down to
        // 1. JavaScript
        // var countDownDate = new Date('Sep 5, 2018 15:37:25').getTime();
        // 2. PHP
        //1000 milisecond
        var countDownDate = <?php echo strtotime('now')+2*60*60 ?> * 1000; 
        var now = <?php echo time() ?> * 1000;

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get todays date and time
            // 1. JavaScript
            // var now = new Date().getTime();
            // 2. PHP
            now = now + 1000;

            // Find the distance between now an the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id='countdown-timer'
            document.getElementById('countdown-timer').innerHTML =  hours + 'h ' +
                minutes + 'm ' + seconds + 's ';

            // If the count down is over, write some text 
            if (distance < 0) {
                clearInterval(x);
                document.getElementById('countdown-timer').innerHTML = 'EXPIRED';
                document.getElementById('btnSubmit').click();
            }
        }, 1000);
        </script>
        <?php 
        }
        ?>
</head>
<body>
    <?php include 'navbar.php';?>
    <main style="min-height: 100vh; max-width: 100%;">

    <p id="countdown-timer"></p>
    <div class = "main-content">
        <!-- Câu hỏi -->
        
        <div class="question-list">
            <?php 
            //Kiểm tra đã làm bài chưa, nếu rồi và muốn làm lại thì hiển thị nút reset
            
                if($done==1){
                    echo $score . "pts";
                    echo '
                    <form action="" method="post">
                        <input type="submit" value="Reset Exam" name="btnReset">
                    </form>
                    ';
                }
            ?>

            <div class="question-list-row">
                <?php 
                foreach ($questions as $value){
                    if($value['open']==1){
                ?>
                <button  tabindex="0" type="button">
                <a href="#<?php echo $value['sentence_id'] ?>"  style="text-decoration:none"><?php echo $value['sentence_id'];?></a>

                    <span class="MuiTouchRipple-root css-w0pj6f">

                    </span>
                </button>

                <?php 
                    }
                }
                ?>
            </div>
        </div>

        <!-- Nội dung câu hỏi -->
        <form method="post">
        <div class="quiz-list">
        <?php 
            foreach ($questions as $value){
                if($value['open']==1){
                //Hiển thị part 1
                echo "<h4 id=".$value["sentence_id"].">". "Question " . $value["sentence_id"]. "</h4>";
                if($value['sentence_id']>='1' && $value['sentence_id']<='6'){
                    drawPart1($value);
                    ?>
                    <div class="quiz-item">
                        <div>
                                <?php 
                                    $quizs = getQuizOptions($value["question_id"]);
                                    //$i để in ra (A) , (B)
                                    $i = 0;
                                    foreach($quizs as $quiz){   
                                ?>
                                        <div class="radio">
                                            <label>
                                            <input type="radio" <?php if($done == 1) {if(getValueForQuizAns($value["question_id"], $examId) == $quiz["numOption"] && $flag ==1){echo "checked";} }?> name="optradio[<?php echo $quiz['question_id']; ?>]" id="optradio[<?php echo $quiz['question_id']; ?>]" value="<?php echo $quiz["numOption"]; ?>"><?php echo $letters[$i]; ?><?php if($done == 1){echo $quiz["opt"];}  ?></label>
                                        </div>
                                <?php 
                                    $i++;
                                }
                                ?>
        
                        </div> 
                    </div>
                <?php
                }
                // Hiển thị part 2
                else if($value['sentence_id']>='7' && $value['sentence_id']<='31'){
                    drawPart2($value);
                    ?>
                    <div class="quiz-item">
                        <div>
                                <?php 
                                    $quizs = getQuizOptions($value["question_id"]);
                                    $i = 0;
                                    foreach($quizs as $quiz){      
                                        if($i==3){continue;}       
    
                                ?>
                                        <div class="radio">
                                            <label>
                                            <input type="radio" <?php if($done == 1) {if(getValueForQuizAns($value["question_id"], $examId) == $quiz["numOption"] && $flag ==1){echo "checked";} }?> name="optradio[<?php echo $quiz['question_id']; ?>]" id="optradio[<?php echo $quiz['question_id']; ?>]" value="<?php echo $quiz["numOption"]; ?>"><?php echo $letters[$i] ?><?php if($done == 1){echo $quiz["opt"];}  ?></label>
                                        </div>
                                <?php 
                                $i++;
                                }
                                ?>
        
                        </div> 
                    </div>
                <?php
                }
                // Hiển thị part 3
                else if($value['sentence_id']>='32' && $value['sentence_id']<='70'){
                    // $p3 = array('32', '35', '38', '41', '44', '47', '50', '53', '56', '35')
                    for($i=32; $i<=70; $i+=3){
                        if((int)$value['sentence_id'] == $i){
                            drawPart3($value);
                        }
                    }
                    echo '<h6>'.$value['question'].'</h6>';
                    ?>
                    <div class="quiz-item">
                        <div>
                                <?php 
                                    $quizs = getQuizOptions($value["question_id"]);
                                    $i = 0;        
                                    foreach($quizs as $quiz){  
                                ?>
                                        <div class="radio">
                                            <label>
                                            <input type="radio" <?php if($done == 1) {if(getValueForQuizAns($value["question_id"], $examId) == $quiz["numOption"] && $flag ==1){echo "checked";} }?> name="optradio[<?php echo $quiz['question_id']; ?>]" id="optradio[<?php echo $quiz['question_id']; ?>]" value="<?php echo $quiz["numOption"]; ?>"><?php echo $letters[$i] ?><?php echo $quiz["opt"]; ?></label>
                                        </div>
                                <?php 
                                $i++;
                                }
                                ?>
        
                        </div> 
                    </div>
                <?php

                }

                // Hiển thị part 4
                else if($value['sentence_id']>='71' && $value['sentence_id']<='100'){
                    // $p3 = array('32', '35', '38', '41', '44', '47', '50', '53', '56', '35')
                    for($i=71; $i<=100; $i+=3){
                        if((int)$value['sentence_id'] == $i){
                            drawPart4($value);
                        }
                    }
                    echo '<h6>'.$value['question'].'</h6>';
                    ?>
                    <div class="quiz-item">
                        <div>
                                <?php 
                                    $quizs = getQuizOptions($value["question_id"]);
                                    $i = 0;        
                                    foreach($quizs as $quiz){  
                                ?>
                                        <div class="radio">
                                            <label>
                                            <input type="radio" <?php if($done == 1) {if(getValueForQuizAns($value["question_id"], $examId) == $quiz["numOption"] && $flag ==1){echo "checked";} }?> name="optradio[<?php echo $quiz['question_id']; ?>]" id="optradio[<?php echo $quiz['question_id']; ?>]" value="<?php echo $quiz["numOption"]; ?>"><?php echo $letters[$i] ?><?php echo $quiz["opt"]; ?></label>
                                        </div>
                                <?php 
                                $i++;
                                }
                                ?>
        
                        </div> 
                    </div>
                <?php

                }

                //Hiển thị part 5
                else if($value['sentence_id']>='101' && $value['sentence_id']<='130'){
                    drawPart5($value);
                    ?>
                    <div class="quiz-item">
                        <div>
                                <?php 
                                    $quizs = getQuizOptions($value["question_id"]);
        
                                    foreach($quizs as $quiz){  
                                        $i = 0;        
                                ?>
                                        <div class="radio">
                                            <label>
                                            <input type="radio" <?php if($done == 1) {if(getValueForQuizAns($value["question_id"], $examId) == $quiz["numOption"] && $flag ==1){echo "checked";} }?> name="optradio[<?php echo $quiz['question_id']; ?>]" id="optradio[<?php echo $quiz['question_id']; ?>]" value="<?php echo $quiz["numOption"]; ?>"><?php echo $letters[$i] ?><?php echo $quiz["opt"]; ?></label>
                                        </div>
                                <?php 
                                $i++;
                                }
                                ?>
        
                        </div> 
                    </div>
                <?php
                }
                // Hiển thị part 6 và 7
                else if($value['sentence_id']>='131' && $value['sentence_id']<='200'){
                    // $p3 = array('32', '35', '38', '41', '44', '47', '50', '53', '56', '35')
                    for($i=131; $i<=200; $i+=3){
                        if((int)$value['sentence_id'] == $i){
                            drawPart6($value);
                        }
                    }
                    echo '<h6>'.$value['question'].'</h6>';
                    ?>
                    <div class="quiz-item">
                        <div>
                                <?php 
                                    $quizs = getQuizOptions($value["question_id"]);
                                    $i = 0;        
                                    foreach($quizs as $quiz){  
                                ?>
                                        <div class="radio">
                                            <label>
                                            <input type="radio" <?php if($done == 1) {if(getValueForQuizAns($value["question_id"], $examId) == $quiz["numOption"] && $flag ==1){echo "checked";} }?> name="optradio[<?php echo $quiz['question_id']; ?>]" id="optradio[<?php echo $quiz['question_id']; ?>]" value="<?php echo $quiz["numOption"]; ?>"><?php echo $letters[$i] ?><?php echo $quiz["opt"]; ?></label>
                                        </div>
                                <?php 
                                $i++;
                                }
                                ?>
        
                        </div> 
                    </div>
                <?php

                }
            ?>
            <?php 
            }
        }
            ?>
        </div>
        <?php 
        if($done == 0){
            //Nếu đã 
        ?>
        <input type="submit" name="btnSubmit" value="Submit" id="btnSubmit">
        <?php
        }
        ?>
        </form>
        <?php 

        ?>

    </div>

    </main>
</body>
<?php include 'footer.php'; ?>
</html>