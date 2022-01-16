<?php
$servername = "mysql";
$username = "dbuser01";
$password = "6RoZqQzz0il5LtSVMJhi80wjMR6g8NBORPEForH4";


try {
    $conn = new PDO("mysql:host=$servername;port=3306;dbname=db01", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function find_code($code)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM students Where code = $code");
    $stmt->execute();
    $results = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if ($results) {
        return $stmt->fetch();
    }
}

function find_nessom_user($code){
    global $conn;
    $stmt = $conn->prepare("SELECT nessom_user,nessom_pass FROM students Where code = $code");
    $stmt->execute();
    $results = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if ($results) {
        return $stmt->fetch();
    }
}

function choose_mood($first_name, $last_name){
    return "
    سلام $first_name $last_name عزیز 
یکی از گزینه های زیر را انتخاب کنید
1.ارسال پیغام به مدیریت
2.درخواست رمز نسام
3.مشاهده کارنامه
    ";
}

function nessome_response($user,$pass){
    return "اطلاعات نسام شما

نام کاربری:  $user

رمز عبور:  $pass

آدرس نسام taleghani.nessom.com
    ";
}

function report_card($user,$pass){
    return "آدرس مشاهده کارنامه https://pada.medu.ir/#/login/karnameh

نام کاربری: $user
رمز عبور: $pass
    ";
}