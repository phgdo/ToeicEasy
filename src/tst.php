
<?php 
    include_once '../function.php';
    $part = getPartOfToeic();
    $topicId = $_GET['topicId'];
    $questions = getQuestions($topicId);
    $flag = 0;
    if(checkExamExists($topicId, $_SESSION['userId']) == true){
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
                echo $option_num." ".$option_val."<br>";
                getQuizAnswer($examId, $option_num, $option_val);
            }
        }
    }
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
</head>
<body>

    <div class = "main-content">
        <!-- Câu hỏi -->
        <div class="question-list">

            <div class="question-list-row">
                <?php 
                foreach ($questions as $value){
                ?>
                <button  tabindex="0" type="button">
                <?php 
                echo $value['sentence_id'];
                ?>
                    <span class="MuiTouchRipple-root css-w0pj6f">

                    </span>
                </button>

                <?php 
                    }
                ?>
            </div>
        </div>

        <!-- Nội dung câu hỏi -->
        <form method="post">
        <div class="quiz-list">
        <?php 
            foreach ($questions as $value){
                echo "<h4>". "Question " . $value["sentence_id"]. "</h4>";
            ?>
            <div class="quiz-item">
                <div>
                        
                        <?php 
                            $quizs = getQuizOptions($value["question_id"]);

                            foreach($quizs as $quiz){          
                        ?>
                                <div class="radio">
                                    <label>
                                    <input type="radio" <?php if(getValueForQuizAns($value["question_id"]) == $quiz["numOption"] && $flag ==1){echo "checked";} ?> name="optradio[<?php echo $quiz['question_id']; ?>]" id="optradio[<?php echo $quiz['question_id']; ?>]" value="<?php echo $quiz["numOption"]; ?>"><?php echo $quiz["opt"]; ?></label>
                                </div>
                        <?php 
                        }
                        ?>

                </div> 
            </div>
            <?php 
            }
            ?>
        </div>
        <input type="submit" name="btnSubmit" value="Submit">
        </form>
        <?php 

        ?>

    </div>



</body>
<?php include 'footer.php'; ?>
</html>