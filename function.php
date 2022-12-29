<?php
    session_start();
    include_once 'connectdb.php';
    

    //Navar

    ///////////////////////////////////////////////
    //Login page
    function login($username, $password){
        GLOBAL $conn;
        $filter_username = mysqli_real_escape_string($conn, $username);
        $filter_password = mysqli_real_escape_string($conn, $password);
        $sql = "select * from accounts where username = '$filter_username' and password = '$filter_password'";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) == 1){
            while ($row = mysqli_fetch_assoc($query)){
                $_SESSION['userId'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['level'] = $row['level'];
                break;
            } 
            return true;
        }
        return false;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////
// Register page
    function validateFormRegister($username, $password, $repassword){
        if(empty($username) || empty($password) || empty($repassword)){
            echo 'Vui lòng nhập đủ các trường thông tin';
            return false;
        }
        else if($password != $repassword){
            echo 'Hãy nhập lại đúng mật khẩu';
            return false;
        }
        else if(strpos(' ', $username) || strpos(' ', $password)){
            echo 'Tài khoản và mật khẩu không được có kí tự khoảng trắng';
            return false;
        }
        else if(strlen($username)>32 || strlen($password)>32){
            echo 'Tài khoản và mật khẩu không được quá 32 kí tự';
            return false;
        }
        else if(strlen($username)<6 || strlen($password)<6){
            echo 'Tài khoản và mật khẩu lớn hơn 6 kí tự';
            return false;
        }
        else{
            return true;
        }
    }

    function register($username, $password){
        GLOBAL $conn;
        $filter_username = mysqli_real_escape_string($conn, $username);
        $filter_password = mysqli_real_escape_string($conn, $password);
        $sql = "select * from accounts where username = '".$filter_username."'";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) < 1){
            $sql = "insert into accounts (username, password) values('$filter_username','$filter_password')";
            $query = mysqli_query($conn, $sql);
            return true;
        }
        else{
            return false;
        }
    }

    
    function setDefaultUserInfo($username){
        GLOBAL $conn;
        $sql = "select id from accounts where username = '".$username."'";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        $sql = "insert into user_information (username_id, fullname) values('".$row['id']."', '".$username."')";
        $query = mysqli_query($conn, $sql);
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////
    
// Thong tin ca nhan
function getThongTinCaNhan($userId){
    GLOBAL $conn;
    $sql = "select * from user_information where username_id = ".$userId."";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($query);
    return $row;
}

function SuaThongTinCaNhan($userId, $ten, $ngaysinh, $sdt, $email){
    GLOBAL $conn;
    $sql = "update user_information set fullname = '".$ten."', birthday = '".$ngaysinh."',
    phone_number = '".$sdt."', email = '".$email."' where username_id = '".$userId."'";
    $query = mysqli_query($conn, $sql);
}
////////////////////////////////////////////////////

// Them_de
    function ThemDe($ten, $nam, $so){
        GLOBAL $conn;
        $sql = "insert into topic (year, name, deso) values ('".$nam."', '".$ten."', '".$so."')";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        if (!file_exists('De/'.$ten.'')) {
            mkdir('De/'.$ten.'', 0777, true);
        }
        else{
            rename('De/'.$ten.'', "/home/user/login/docs/my_file.txt");
        }
    }
////////////////////////////////////

// Sua_de
function getDe($topicId){
    GLOBAL $conn;
    $sql = "select * from topic where id = '".$topicId."'";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($query);
    return $row;
}
function SuaDe($ten, $nam, $so, $topicId){
    GLOBAL $conn;
    $sql = "update topic set name='".$ten."', year='".$nam."', deso = '".$so."' where id = '".$topicId."'";
    $query = mysqli_query($conn, $sql);
    if (!file_exists('De/'.$row['name'].'')) {
        mkdir('De/'.$row['name'].'', 0777, true);
    }
    else{

    }
}
////////////////////////////////////


// Them sua cau hoi
function getCauHoi($topicId){
    GLOBAL $conn;
    $sql = "select * from question where topic_id = '".$topicId."'";
    $query = mysqli_query($conn, $sql);
    $questions = [];
    while($row = mysqli_fetch_assoc($query)){
        $questions[] = $row;
    }
    return $questions;
}

function themCauHoi($topicId, $partId, $sentence_id, $audioPath, $imagePath, $question, $text){
    GLOBAL $conn;
    $sql = "insert into question (topic_id, part_id, sentence_id, audio_path, image_path, question, text) 
    values('".$topicId."', '".$partId."', '".$sentence_id."', '".$audioPath."', '".$imagePath."', '".$question."', '".$text."')";
    $query = mysqli_query($conn, $sql);
}

function XuLyFileNghe($filebt, $filetmp, $partId, $topicId){
        $extension = array('mp3', 'wma', 'wav');
		$extension_file = strtolower( pathinfo($filebt, PATHINFO_EXTENSION));
        $file = pathinfo($filebt);
		$flag = 0;
		if(empty($filebt)){
			echo "Hãy chọn file audio.";
			$flag = 1;
            return false;
		}
		if(!in_array($extension_file, $extension)){
			echo "Hãy chọn đúng loại file audio.";
			$flag = 1;
            return false;
		}
		if($flag == 0){
            $path = '../fileNop/';
			if(file_exists($path.$filebt)){
                $newfilename = $file['filename'] . uniqid(rand(), true) . '.'. $extension_file;
            }
            else{
                $newfilename = $filebt;
            }
			$filePath = $path.$newfilename;
			move_uploaded_file($filetmp, $filePath);
			$sql = "update bainop set filename = '$newfilename' where id = '$id'";
			$do = mysqli_query($conn, $sql);
            unlink($path.$row['filename']);
			return true;
		}
}

/////////////////////////////

//dashboard page
    function checkLogin(){
        if(!isset($_SESSION['userId'])){
            header("location: login.php");
            return false;
        }
        return true;
    }

    function getUserInformation(){
        GLOBAL $conn;
        $sql = "select * from user_information where username_id = '".$_SESSION['userId']."'";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        return $row;
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////

    // Practice page
    function getPartOfToeic(){
        GLOBAL $conn;
        $sql = "select * from part";
        $query = mysqli_query($conn, $sql);
        $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
        return $arr;
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////
    //Toeictest page
    function getFullTest($idTest){
        GLOBAL $conn;
        $sql = "select * from question where topic_id = '$topicId'";
        $query = mysqli_query($conn, $sql);
        $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
        return $arr;
    }

    function getNumQuestions($topicId){
        GLOBAL $conn;
        $sql = "select sentence_id from question where topic_id = '$topicId'";
        $query = mysqli_query($conn, $sql);
        $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row['sentence_id'];
        }
        return $arr;
    }

    function getQuestions($topicId){
        GLOBAL $conn;
        $sql = "select * from question where topic_id = '$topicId'";
        $query = mysqli_query($conn, $sql);
        $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
        return $arr;
    }

    function getQuizOptions($questionId){
        GLOBAL $conn;
        $sql = "select * from quiz_options where question_id = '$questionId'";
        $query = mysqli_query($conn, $sql);
        $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
        return $arr;
    }


    function drawFormPart1($question){
        $path = '../';
        echo '    <audio class="audioquiz" src="'.$path.$question['audio_path'].'" controls>
                </audio>';
        echo "<br>";
        echo '<img src="'.$path.$question['image_path'].'" alt="'.$question['image_path'].'" class="imagequiz">';
        
        
    }

    function drawFormOption($quizs){
        foreach($quizs as $quiz){
            echo '
            <input type="radio" name="'.$quiz["question_id"].'" id="'.$quiz["id"].'" value="'.$quiz["numOption"].'" required>
            <label for="'.$quiz["question_id"].'">'.$quiz["opt"].'</label><br>
            ';
        }
    }

    function getQuizAnswer($examId, $questionId, $yourAns){
        GLOBAL $conn;
        $sql = "insert into quiz_answer(exam_id, question_id, your_ans) values(".$examId.", ".$questionId.", ".$yourAns.")";
        $query = mysqli_query($conn, $sql);
    }

    function CheckExamDone($examId){
        GLOBAL $conn;
        $sql = "select done from exams where exam_id = ".$examId."";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        if($row['done'] == "1"){
            return true;
        }
        return false;
    }

    function ResetExam($examId){
        GLOBAL $conn;
        //Xoa cau tra loi
        $sql = "delete from quiz_answer where exam_id = ".$examId."";
        $query = mysqli_query($conn, $sql);
        //Xoa bai kiem tra
        $sql = "delete from exams where exam_id = ".$examId."";
        $query = mysqli_query($conn, $sql);
    }

    // function ButtonStartQuiz(){
    //     echo '
    //     <div style="position: fixed;
    //     width: 100%;
    //     height: 100%;
    //     left: 0;
    //     top: 0;
    //     background: rgba(51,51,51,0.7);
    //     z-index: 10; ">
    //         <input type="button" value="Bắt đầu làm bài" name="btnStart">
    //     </div>
    //     ';

    // }



    function checkExamExists($topicId, $userId){
        GLOBAL $conn;
        $sql = "select exam_id from exams where topic_id=".$topicId." and user_id=".$userId."";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) == 0 ){
            return true;
        }
        return false;
    }

    function getExamId($topicId, $userId){
        GLOBAL $conn;
        $sql = "select exam_id from exams where topic_id=".$topicId." and user_id=".$userId."";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        return $row["exam_id"];
    }

    function getValueForQuizAns($questionId, $examId){
        GLOBAL $conn;
        $sql = "select your_ans from quiz_answer where question_id=".$questionId." and exam_id = ".$examId."";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        return $row['your_ans'];
    }

    // function getValueForQuizAns($examId){
    //     GLOBAL $conn;
    //     $sql = "select * from quiz_answer where exam_id=".$examId."";
    //     $query = mysqli_query($conn, $sql);
    //     $quizs = [];
    //     while ($row = mysqli_fetch_assoc($query)){
    //         $quizs[] = $row;
    //     }
    //     return $quizs;
    // }

    function startQuiz($topicId, $userId){
        GLOBAL $conn;
        $sql = "insert into exams(topic_id, user_id) values(".$topicId.", ".$userId.")";
        $query = mysqli_query($conn, $sql);
        $sql = "select exam_id from exams where topic_id=".$topicId." and user_id=".$userId."";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        return $row["exam_id"];
    }

    function calScore($examId){
        GLOBAL $conn;
        $sql = "select * from quiz_answer where exam_id = ".$examId."";
        $query = mysqli_query($conn, $sql);
        $listeningPoint = 0;
        $readingPoint = 0;
        while ($row = mysqli_fetch_assoc($query)){
            $sql2 = "select * from quiz_options where question_id = ".$row["question_id"]." and numOption = ".$row["your_ans"]."";
            $query2 = mysqli_query($conn, $sql2);
            while ($row2 = mysqli_fetch_assoc($query2)){
                if($row2["is_correct"] == "1"){
                    $sql3 = "select part_id from question where question_id = ".$row["question_id"]."";
                    $query3 = mysqli_query($conn, $sql3);
                    $row3 = mysqli_fetch_assoc($query3);
                    if($row3["part_id"] == "1" || $row3["part_id"] == "2" || $row3["part_id"] == "3" || $row3["part_id"] == "4"){
                        $listeningPoint++;
                    }
                    else{
                        $readingPoint++;
                    }
                }
            }
        }

        //Làm phần tính điểm 
        $listeningScore = 0;
        if($listeningPoint==0){
            $listeningScore = 5;
        }
        else if ($listeningPoint==1){
            $listeningScore = 15;
        }
        else if($listeningPoint>=2 && $listeningPoint <=75){
            $listeningScore = 15 + ($listeningPoint-1)*5;
        }
        else if($listeningPoint>=76){
            $listeningScore = 395 + ($listeningPoint-76)*5;
        }

        //Làm phần tính điểm 
        $readingScore = 0;
        if($readingPoint>=0 && $readingPoint<=2){
            $readingScore = 5;
        }
        else if ($readingPoint>=3){
            $readingScore = 5 + ($readingPoint-2)*5;
        }

        if($readingPoint+$listeningPoint==0){
            $totalScore = 0;
        }
        else{
            $totalScore = $readingScore + $readingScore;
        }


        $sql4 = "update exams set score=".$totalScore.", lisScore=".$listeningScore.", readScore=".$readingScore.", lisPoint=".$listeningPoint.", readPoint=".$readingPoint." where exam_id = ".$examId."";
        $query4 = mysqli_query($conn, $sql4);
    }

    function echoScore($examId){
        GLOBAL $conn;
        $sql = "select score, lisScore, readScore, lisPoint, readPoint from exams where exam_id=".$examId."";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        echo "
        <script>
          alert('
            Your score: ".$row['score'].",
            Listening score: ".$row['lisScore'].",
            Reading score: ".$row['readScore'].",
            Number of correct sentences Listening: ".$row['lisPoint'].",
            Number of correct sentences Reading: ".$row['readPoint'].",
          ');
        </script>
        ";
    }

    function getNumOptionCorrectAnswer($questionId){
        GLOBAL $conn;
        $sql = "select numOption from quiz_options where question_id = ".$questionId." and is_correct=1";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        return $row['numOption'];
    }

    function finishQuiz($examId){
        GLOBAL $conn;
        $sql = "update exams set time_end=current_timestamp, done = 1 where exam_id=".$examId."";
        $query = mysqli_query($conn, $sql);
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////
    //Test_select page
    function getTopic(){
        GLOBAL $conn;
        $sql = "select * from topic";
        $query = mysqli_query($conn, $sql);
        $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
        return $arr;
    }
    function getScoreFromExams ($topicId, $userId){
        GLOBAL $conn;
        $sql = "select score from exams where topic_id = '$topicId' and user_id = '$userId'";
        $query = mysqli_query($conn, $sql);
        $arr = [];
        $row = mysqli_fetch_assoc($query);
        //Nếu không có bảng nào trả ra thì return 0
        if($row == null|| $row == NULL){
            return "0";
        }
        else{
            return $row['score'];
        }
    }
// Grammar
function getTopicGramar(){
    GLOBAL $conn;
    $sql = "select * from topic_grammar";
	$query = mysqli_query($conn, $sql);
    $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
        return $arr;
}

function getNoiDungChuDe($topicId){
    GLOBAL $conn;
    $sql = "select * from noi_dung_chu_de where topic_id = ".$topicId."";
	$query = mysqli_query($conn, $sql);
    $arr = [];
        while ($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
        return $arr;
}

function ThemChuDe($ten){
    GLOBAL $conn;
    $sql = "insert into topic_grammar (name) value('".$ten."')";
	$query = mysqli_query($conn, $sql);
}

function ThemBaiTap($tenbt, $hannop, $tenfile, $filetmp, $topicId){
    GLOBAL $conn;
    $extension = array('pdf', 'docx', 'doc');
    $extension_file = strtolower( pathinfo($tenfile, PATHINFO_EXTENSION));
    $flag = 0;
    if(empty($tenbt)){
        echo "Hãy nhập tên bài tập.";
        $flag = 1;
    }
    if(empty($hannop)){
        echo "Hãy nhập hạn nộp bài tập.";
        $flag = 1;
    }
    if(empty($tenfile)){
        echo "Hãy chọn file bài tập.";
        $flag = 1;
    }
    if(!in_array($extension_file, $extension)){
        echo "Hãy chọn đúng loại file.";
        $flag = 1;
    }
    if($flag == 0){
        //file
        $path = '../fileBT/';
        $filename = $tenfile;
        $file = pathinfo($filename);
        if(file_exists($path.$filename)){
            $newfilename = $file['filename'] . uniqid(rand(), true) . '.'. $extension_file;
        }
        else{
            $newfilename = $filename;
        }
        $filePath = $path.$newfilename;
        move_uploaded_file($filetmp, $filePath);
        $sql = "insert into noi_dung_chu_de(name, hannop, filename, topic_id) values('$tenbt', '$hannop', '$newfilename', '$topicId')";
        $do = mysqli_query($conn, $sql);
        
    }
}

function SuaChuDe($tenchude, $topicId){
    GLOBAL $conn;
    if(!empty($tenchude)){
        $name = $tenchude;
        $sql = "update topic_grammar set name = '$name' where id = '$topicId'";
        $do = mysqli_query($conn, $sql);
        return true;
    }
    else{
        return false;
    }
}

function XoaChuDe($topicId){
    GLOBAL $conn;
        $sql = "delete from topic_grammar where id = ".$topicId."";
        $do = mysqli_query($conn, $sql);
}

function getChiTietBaiTap($idBaiTap){
    GLOBAL $conn;
    $sql = "select * from noi_dung_chu_de where id = '$idBaiTap'";
    $do = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($do);
    return $row;
}

function GetChiTietBaiNop($idBaiTap, $userId){
    GLOBAL $conn;
    $sql = "select * from bainop where id = '$idBaiTap' and user_id = '$userId'";
    $do = mysqli_query($conn, $sql);
    if(mysqli_num_rows($do)>0){
        $row = mysqli_fetch_assoc($do);
        return $row;
    }
}

function GetHanNop($idBaiTap){
    GLOBAL $conn;
    $sql = "select hannop from noi_dung_chu_de where id = '$idBaiTap'";
    $do = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($do);
    return $row['hannop'];
}

function KiemTraHanNop($idBaiTap){
    $hannop = GetHanNop($idBaiTap);
    $today = date("Y-m-d H:i:s");
    if(strtotime($today) > strtotime($hannop)){
        return false;
    }
    return true;
}


function KiemTraDaNopBaiNop($idBaiTap, $userId){
    GLOBAL $conn;
    $sql = "select filename from bainop where id = '$idBaiTap' and user_id = '$userId'";
    $do = mysqli_query($conn, $sql);

    if(mysqli_num_rows($do)>0){
        return true;
    }
    else{
        return false;
    }
}

function NopBaiTap($userId, $filebt, $filetmp, $idBaiTap){
    GLOBAL $conn;
    $extension = array('pdf', 'docx', 'doc');
    $extension_file = strtolower( pathinfo($filebt, PATHINFO_EXTENSION));
    $file = pathinfo($filebt);
    $flag = 0;
    if(empty($filebt)){
        echo "Hãy chọn file bài tập.";
        $flag = 1;
        return false;
    }
    if(!in_array($extension_file, $extension)){
        echo "Hãy chọn đúng loại file.";
        $flag = 1;
        return false;
    }
    if($flag == 0){
        $path = '../fileNop/';
        if(file_exists($path.$filebt)){
            $newfilename = $file['filename'] . uniqid(rand(), true). '.' . $extension_file;
        }
        else{
            $newfilename = $filebt;
        }
        $filePath = $path.$newfilename;
        move_uploaded_file($filetmp, $filePath);
        $t = time();
        $timestamp = date("Y-m-d H:i:s", $t);
        $sql = "insert into bainop(user_id, filename, id_bai_tap) values('$userId', '$newfilename', '$idBaiTap')";
        $do = mysqli_query($conn, $sql);
        return true;
    }

}

function SuaBaiNop( $userId, $filebt, $filetmp,$idBaiTap){
    GLOBAL $conn;

    $sql = "select id, filename from bainop where id_bai_tap = '$idBaiTap' and user_id = '$userId'";
    $do = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($do);
    $id = $row['id'];
    $extension = array('pdf', 'docx', 'doc');
    $extension_file = strtolower( pathinfo($filebt, PATHINFO_EXTENSION));
    $file = pathinfo($filebt);
    $flag = 0;
    if(empty($filebt)){
        echo "Hãy chọn file bài tập.";
        $flag = 1;
        return false;
    }
    if(!in_array($extension_file, $extension)){
        echo "Hãy chọn đúng loại file.";
        $flag = 1;
        return false;
    }
    if($flag == 0){
        $path = '../fileNop/';
        if(file_exists($path.$filebt)){
            $newfilename = $file['filename'] . uniqid(rand(), true) . '.'. $extension_file;
        }
        else{
            $newfilename = $filebt;
        }
        $filePath = $path.$newfilename;
        move_uploaded_file($filetmp, $filePath);
        $sql = "update bainop set filename = '$newfilename' where id = '$id'";
        $do = mysqli_query($conn, $sql);
        unlink($path.$row['filename']);
        return true;
    }

}

function HienThiIframe($filename){
    $iframe = "<iframe width='100%' height='100%' src='".$filename."'></iframe>";
    $btn = "<a class='btn btn-primary' href='".$filename."'>Tải xuống file đề bài</a>";
    if(pathinfo($filename, PATHINFO_EXTENSION) == 'pdf'){
        echo $iframe;
    } 
    else{
        echo $btn;
    }
}

function SuaBaiTap2($tenbt, $hannop, $idBaiTap){
    GLOBAL $conn;
    $flag = 0;
    if(empty($tenbt)){
        echo "Hãy nhập tên bài tập.";
        $flag = 1;
    }
    if(empty($hannop)){
        echo "Hãy nhập hạn nộp bài tập.";
        $flag = 1;
    }

    if($flag == 0){
        //file		
            $sql = "update noi_dung_chu_de set name = '$tenbt', hannop = '$hannop' where id = '$idBaiTap' ";
            $do = mysqli_query($conn, $sql);
            return true;
    
    }
}
function SuaBaiTap1($tenbt,  $hannop, $tenfile, $filetmp, $idBaiTap){
    GLOBAL $conn;
    $sql = "select filename from noi_dung_chu_de where id = '$idBaiTap'";
    $do = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($do);
    $oldfilename = $row['filename'];
    
    $flag = 0;
    if(empty($tenbt)){
        echo "Hãy nhập tên bài tập.";
        $flag = 1;
    }
    if(empty($hannop)){
        echo "Hãy nhập hạn nộp bài tập.";
        $flag = 1;
    }

    if($flag == 0){
        //file		
        $path = '../fileBT/';
        $filename = $tenfile;
        $file = pathinfo($filename);
        if(empty($tenfile) == false){
            $extension = array('pdf', 'docx', 'doc');
            $extension_file = strtolower( pathinfo($tenfile, PATHINFO_EXTENSION));
            if(in_array($extension_file, $extension)){
                unlink($path.$oldfilename);
                if(file_exists($path.$filename)){
                    $newfilename = $file['filename'] . uniqid(rand(), true) . '.'. $extension_file;
                }
                else{
                    $newfilename = $filename;
                }
                $filePath = $path.$newfilename;
                move_uploaded_file($filetmp, $filePath);				
                $sql = "update noi_dung_chu_de set name = '$tenbt', hannop = '$hannop', filename = '$newfilename' where id = '$idBaiTap' ";
                $do = mysqli_query($conn, $sql);
            }
            
            return true;
        }
        else{
            return false;
        }

    }
}

function XoaBT($idBaiTap){
    GLOBAL $conn;

    $sql = "select filename from noi_dung_chu_de where id = '$idBaiTap'";
    $do = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($do);
    $path = '../fileBT/';
    if(!empty($row['filename'])){
        unlink($path.$row['filename']);
    }

    $sql = "delete from noi_dung_chu_de where id = '$idBaiTap'";
    $do = mysqli_query($conn, $sql);
    if($do == true){
        return true;
    }
    return false;
}

function getChiTietBaiNop2($idBaiTap){
    GLOBAL $conn;
    $arr = [];
    $sql = "select bainop.id as id, user_information.fullname as name, bainop.pass as pass, bainop.filename as filename, bainop.time as time from bainop, user_information where bainop.id_bai_tap = '$idBaiTap'  and bainop.user_id = user_information.username_id";
    $do = mysqli_query($conn, $sql);
    if(mysqli_num_rows($do)>0){
        while($row = mysqli_fetch_assoc($do)){
            $arr[] = $row;
        }
    }
    return $arr;
}

function ChamBaiDat($idBaiNop){
    GLOBAL $conn;
    $sql = "update bainop set pass = 1 where id = '$idBaiNop'";
    $do = mysqli_query($conn, $sql);
}

function ChamBaiKhongDat($idBaiNop){
    GLOBAL $conn;
    $sql = "update bainop set pass = 0 where id = '$idBaiNop'";
    $do = mysqli_query($conn, $sql);
}
///////////////////////////////////////////////////////
    
// Quản lý tài khoản

function getTaiKhoan(){
    GLOBAL $conn;
    $sql = "select accounts.id, accounts.username, user_information.fullname, user_information.birthday, user_information.phone_number, user_information.email from accounts, user_information where accounts.id = user_information.username_id";
    $query = mysqli_query($conn, $sql);
    $arr = [];
    if(mysqli_num_rows($query)>0){
        while($row = mysqli_fetch_assoc($query)){
            $arr[] = $row;
        }
    }
    return $arr;
}

function xoaTK($userId){
    GLOBAL $conn;
    //Xóa thông tin từ bảng user_information
    $sql = "delete from user_information where username_id=".$userId."";
    $query = $do = mysqli_query($conn, $sql);
    //Xóa thông tin từ bảng accounts
    $sql = "delete from accounts where id=".$userId."";
    $query = mysqli_query($conn, $sql);

    //Xóa thông tin từ bảng exams
    $sql = "select exam_id from exams where user_id=".$userId."";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($query);
    if(mysqli_num_rows($query)>0){
        $examId = $row['exam_id'];
        $sql = "delete from exams where exam_id=".$examId."";
        $query = mysqli_query($conn, $sql);
        $sql = "delete from quiz_answer where exam_id=".$examId."";
        $query = mysqli_query($conn, $sql);
    }


    //Refesh page
    header("Refresh:0");
}
////////////////////////////////////////////////////////////////
?>