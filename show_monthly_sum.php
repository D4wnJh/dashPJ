<!-- show_monthly_sum.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        th {
            background-color: #f2f2f2;
        }
        h1{
            width: 1110px;
            text-align: center; align-items:center;
            justify-content: center; 
            display: flex;
            background-color:#014099; 
            padding: 10px 0 10px 0px;
            color: white; 
            margin: 0 auto;
            margin-bottom: 15px;
        }
        h2 {
            width:1100px;
            text-align: center; align-items:center;
            justify-content: center;
            margin: 0 auto; 
            margin-bottom: 15px;
        }
        input{
            width: 60px;            
        }
        td,th{
            text-align: center;
            border: 1px solid #ddd;
        }
        #insert{
            border:none;   
        }
        table{
            border-collapse: collapse;
            margin:5px;
            width: 715px;
        }
        #table2{
            width: 1100px;
        }
        a{
            text-decoration: none; 
        }
        #box1{            
            float: right;
            margin-left:20px;
            margin-bottom:20px;
            font-weight:bold;
            padding: 5px 8px 5px 8px;
        }
        #box2{
            width:1100px;
            border: none;
            margin: 0 auto;            
        }
        #box3 {
            width:1100px;
            border: none;
            margin: 0 auto; 
            margin-top: 15px;
        }
        #img2{
            width:990px;
            height:600px;            
        }
        #img3{
            width:990px;
            height:330px;            
        }
        .underline-effect {
        position: relative;
        text-decoration: none;
        color: #333;
        font-size: 18px;
        overflow: hidden;
    }
        .underline-effect::before {
            content: "";
            position: absolute;
            width: 0;
            height: 2px;
            background-color: #333;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease-in-out;
            color: blue;}
        .underline-effect:hover::before {
            width: 100%;}
    </style>
    
</head>
<body>
    <div id='box2'>
    <span id='box1'><a href='http://localhost/pjdash_r07.php' class='underline-effect'>대시 보드</a></span>
    <span id='box1'><a href='http://localhost/show_monthly_sum.php' class='underline-effect'>월간 실적</a></span>
    <span id='box1'><a href='http://localhost/show_daily_sum.php' class='underline-effect'>일간 실적</a></span> 
    <span id='box1'><a href='http://localhost/show_all_data2.php' class='underline-effect'>생산 log</a></span>
    <span id='box1'><a href='http://localhost/production_insert_panel.php' class='underline-effect'>입력 패널</a></span> 
    </div>
<h1> 월간 생산 리포트 </h1>
<div id='box3'>
<h2>모델 별 불량 누계</h2>  
<img id='img2' src="month_def.jpg" alt="그래프"><br>
<br>
<h2>모델 별 불량 현황</h2>
<img id='img3' src="donut.jpg" alt="그래프"><br>

<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "processDB";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname); 

// 쿼리 실행        
$db_data = "SELECT * FROM monthly_product_total_Tbl";  //daily_product_data_Tbl 에서 모든 열의 데이터를 가져오는 쿼리

$result = $conn->query($db_data);  // 데이터 베이스 연결 객체에서 query() 메서드로 쿼리 실행하고 변수에 할당

// 데이터 추출하여 중복 제거
$processes = array();
$dates = array();
while ($row = $result->fetch_assoc()) {
    $processes[] = $row["model"];
    $dates[] = $row["month_year"];
}
$processes = array_unique($processes); // 중복된 값 제거
$dates = array_unique($dates);
sort($processes); // 배열 오름차순으로 정렬
array_unshift($processes, "All Models");  // 옵션 메뉴 추가 
sort($dates);

// 테이블 출력
echo "<label><a href='http://localhost/show_monthly_sum.php' >기간: </a></label>";
echo "<select id='start_day' onchange='filter_data()'>";
foreach ($dates as $date) {
    echo "<option value='" . $date . "'>" . $date . "</option>";
}
echo "</select>";

echo "<label> ~ </label>";
echo "<select id='end_day' onchange='filter_data()'>";
foreach ($dates as $date) {
    echo "<option value='" . $date . "'>" . $date . "</option>";
}
echo "</select>";

echo "<label><a href='http://localhost/show_monthly_sum.php' >&nbsp&nbsp&nbsp&nbsp모델: </a></label>";   // 드롭 다운 버튼의 라벨
echo "<select id='model_select' onchange='filter_data()'>"; // 드롭다운 버튼을 생성하고 id 지정, 드롭 다운 메뉴 변경 시 실행할 JS 함수 지정 

foreach ($processes as $process) {// result에서 다음행의 데이터를 가져와서 row에 할당, 모든 행의 데이터를 처리하도록 loop
    echo "<option value='" . $process . "'>" . $process . "</option>";  // 드롭 다운 선택 가능한 공정을 옵션으로 지정하고 전송
}
echo "</select>";

echo "<br><br>";
echo "<table id='table2'>";
echo "<tr><th>날짜</th><th>모델</th><th>중량 불량 건수</th><th>반사율 불량 건수</th><th>흠집 불량 건수</th><th>도장 균일도 불량 건수</th><th>소비전류 불량 건수</th><th>양품수</th><th>불량수</th><th>총 생산량</th></tr>";


mysqli_data_seek($result, 0);  // 결과셋 포인터를 처음으로 이동하여 테이블 데이터를 생성 할 수 있도록 함

while ($row = $result->fetch_assoc()) {
    echo "<tr class='model date_' data-select='" . $row["model"] . "' data-date_='" . $row["month_year"] . "'>"; // tr 에 class 지정, 각행의 공정을 데이터 속성으로 설정
    
    
    echo "<td>" . $row["month_year"] . "</td>";
    echo "<td>" . $row["model"] . "</td>";                    
    echo "<td>" . $row["weight_count"] . "</td>";
    echo "<td>" . $row["reflectivity_count"] . "</td>";
    echo "<td>" . $row["scratch_count"] . "</td>";
    echo "<td>" . $row["coating_count"] . "</td>";
    echo "<td>" . $row["e_current_count"] . "</td>";
    echo "<td>" . $row["good_quantity"] . "</td>";
    echo "<td>" . $row["defective_quantity"] . "</td>";
    echo "<td>" . $row["total_quantity"] . "</td>";
}
echo "</table>";
//연결 종료
$conn->close();
?>

</div>

<script>
var dropButton = document.getElementById("model_select");
dropButton.value = ""; //드롭다운 선택을 초기화

var dropButton = document.getElementById("start_day");
dropButton.value = ""; //드롭다운 선택을 초기화

var dropButton = document.getElementById("end_day");
dropButton.value = ""; //드롭다운 선택을 초기화

function filter_data() {
    var modelDropButton = document.getElementById("model_select");
    var selectedModel = modelDropButton.value;

    var startDateInput = document.getElementById("start_day");
    var endDateInput = document.getElementById("end_day");
    var startDate = startDateInput.value;
    var endDate = endDateInput.value;

    var rows = document.getElementsByClassName("model date_");
    
    for (var i = 0; i < rows.length; i++) {
        var modelMatch = (selectedModel === "" || selectedModel === "All Models" || rows[i].getAttribute("data-select") === selectedModel);
        var date_ = rows[i].getAttribute("data-date_");
        var date_Match = (startDate === "" || endDate === "" || (date_ >= startDate && date_ <= endDate));         

        if (modelMatch && date_Match) {
            rows[i].style.display = "table-row";}
        else {
            rows[i].style.display = "none";
        }
    }
}
</script>
</body>
</html>