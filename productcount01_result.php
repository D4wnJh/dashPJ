<!-- productcount01_result.php -->

<?php
    $con = mysqli_connect("localhost", "root", "1234", "processDB") or die("MySQL 접속 실패 !!");
    $model = mysqli_real_escape_string($con, $_POST['model']);
    $weight = $_POST["weight"];
    $reflectivity = $_POST["reflectivity"];
    $scratch = $_POST["scratch"];
    $coating = $_POST["coating"];
    $e_current = $_POST["e_current"];
    $sql = "INSERT INTO daily_product_data_Tbl (model, weight, reflectivity, scratch, coating, e_current) VALUES ('$model', '$weight', '$reflectivity', '$scratch', '$coating', '$e_current')";
    $ret = mysqli_query($con, $sql);
    echo "<h1> 생산 실적 입력 결과 </h1>";
    if ($ret) {
        echo "데이터가 성공적으로 입력됨.";
    } else {
        echo "데이터 입력 실패!!!" . "<br>";
        echo "실패 원인: " . mysqli_error($con);
    }
    mysqli_close($con);
?>
<br>
<a href='http://localhost/production_insert_panel.php'> <--생산 실적 등록 화면</a>



