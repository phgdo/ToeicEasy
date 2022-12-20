<?php
    session_start();
    include_once 'connectdb.php';
    
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
        }
        else if($password != $repassword){
            echo 'Hãy nhập lại đúng mật khẩu';
        }
        else if(strpos(' ', $username) || strpos(' ', $password)){
            echo 'Tài khoản và mật khẩu không được có kí tự khoảng trắng';
        }
        else if(strlen($username)>32 || strlen($password)>32){
            echo 'Tài khoản và mật khẩu không được quá 32 kí tự';
        }
        else{
            if(register($username, $password)){
                echo 'register success';
                
            }
            else{
                echo 'register failed';
            }
        }
    }

    function register($username, $password){
        GLOBAL $conn;
        $filter_username = mysqli_real_escape_string($conn, $username);
        $filter_password = mysqli_real_escape_string($conn, $password);
        $sql = "select * from accounts where username = '$filter_username' and password = '$filter_password'";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) == 0){
            $sql = "insert into accounts values('$filter_username','$filter_password')";
            $query = mysqli_query($conn, $sql);
            return true;
        }
        return false;
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////
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
        $userInfor = [];
        if(mysqli_num_rows($query) > 0){
            while ($row = mysqli_fetch_assoc($query)){
                $userInfor = $row;
            }
        }
        else{
            $userInfor['fullname'] = $_SESSION['username'];
            $userInfor['birthday'] = "";
            $userInfor['phone_number'] = "";
            $userInfor['ava_img_path'] = "";
            $userInfor['email'] = "";
        }
        return $userInfor;
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
        $sql = "select * from quiz_option where question_id = '$questionId'";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        return $row;
    }

    function drawFormPart1($question){
        $path = '../';
        echo '<img src="'.$path.$question['image_path'].'" alt="'.$question['image_path'].'">';
        echo '    <audio src="'.$path.$question['audio_path'].'" controls>
                </audio>';
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

    
?>