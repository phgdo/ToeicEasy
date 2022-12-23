
<?php 
    include_once '../function.php';
    $part = getPartOfToeic();
    $topicId = $_GET['topicId'];
    $questions = getQuestions($topicId);
    $examId = startQuiz($topicId, $_SESSION['userId']);

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

    <script>
        function clockStart() {
            Var counter=0

            Var timer=setInterval(function{

            Counter++;

            // Do your task here..This will execute after every second until 7200 seconds

            If(counter==7200)

            clearInterval(timer)

            },1000)
        }
    </script>
</head>
<body>
    <?php include 'navbar.php';?>
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
                <form action="" method="post">
                    <input type="submit" name="btnSubmit" value="Submit">
                </form>
            </div>
        </div>

        <!-- Nội dung câu hỏi -->
        <div class="quiz-list">
            <?php 
            foreach ($questions as $value){
                echo "<h4>". "Question " . $value["sentence_id"]. "</h4>";
            ?>
            <div class="quiz-item">
                <div>
                    <form method="post">
                    <?php drawFormPart1($value); 
                        $quizs = getQuizOptions($value["question_id"]);
                        echo "<br>";
                        foreach($quizs as $quiz){
                            echo '
                            <input type="radio" name="optradio['.$quiz["question_id"].']" value="'.$quiz["numOption"].'">
                            <label for="'.$quiz["question_id"].'">'.$quiz["opt"].'</label><br>
                            ';

                                // getQuizAnswer($examId, $quiz["question_id"], $selected);
                        }
                        // drawFormOption($quiz);

                    ?>
                    </form>
                </div> 
            </div>
            <?php 
            }
            ?>
        </div>
    </div>

    <?php 
    if(isset($_POST['btnSubmit'])){
        foreach($_POST['optradio'] as $option_num => $option_val)
        echo $option_num." ".$option_val."<br>";
    }

    ?>

</body>
<?php include 'footer.php'; ?>
</html>