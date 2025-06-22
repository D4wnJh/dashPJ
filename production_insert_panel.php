<!-- production_insert_panel.php -->
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
        input{
            width: 60px; 
            margin: 0 5px;           
        }
        #box3{
            border: solid 2px;
            width: 1100px;
            height: 210px;
            margin: 0 auto;
        }
        td,th{
            text-align: center;
            border: 1px solid #ddd;   
        }
        #insert{
            border:none;  
            margin: 0 5px; 
        }
        table{
            border-collapse: collapse;
            margin:5px;
            width: 1090px;
        }
        #table2{
            width: 1090px;
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
            margin: 0 auto;           
        }
        input{
            width:90px;
        }
        #lib {
            float: right;
            margin-right: 10px;
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
        color: blue;
    }
    .underline-effect:hover::before {
        width: 100%;
    }
        
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
<h1> 생산 입력 패널 </h1>

<form  METHOD="post"  ACTION="productcount01_result.php">
<div id='box3'>  
    <span>&nbsp;일자&nbsp;&nbsp;</span><?php echo date("Y-m-d");?>
    &nbsp;&nbsp;
    <span id='pr'>모델 :</span>&nbsp;&nbsp;
    
    <select name="model" id="model_select"  onchange="filter_data()">
        <option value=None></option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>        
    </select>   

    <table>        
        <tr>    
            <td id='insert'>무게<input type='text' name='weight'></td>            
            <td id='insert'>반사율<input type='text'  name='reflectivity'></td>            
            <td id='insert'>스크레치<input type='text'  name='scratch'></td>            
            <td id='insert'>도장 균일도<input type='text' name='coating'></td>            
            <td id='insert'>소비 전류<input type='text' name='e_current'></td>  
        </tr>
        <tr>
        <?php

// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "processDB";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname); 

// 쿼리 실행        
$db_data = "SELECT * FROM quality_standards_Tbl";  //daily_product_data_Tbl 에서 모든 열의 데이터를 가져오는 쿼리

$result = $conn->query($db_data);  // 데이터 베이스 연결 객체에서 query() 메서드로 쿼리 실행하고 변수에 할당

// 데이터 추출하여 중복 제거
$processes = array();
while ($row = $result->fetch_assoc()) {
    $processes[] = $row["model"];    
}
$processes = array_unique($processes); // 중복된 값 제거
sort($processes); // 배열 오름차순으로 정렬


echo "<br><br>";
echo "<table id='table2'>";
echo "<tr><th>무게 표준</th><th>반사율 표준</th><th>스크레치 표준</th><th>도장 균일도 표준</th><th>소비전류 표준</th></tr>";

mysqli_data_seek($result, 0);  // 결과셋 포인터를 처음으로 이동하여 테이블 데이터를 생성 할 수 있도록 함

while ($row = $result->fetch_assoc()) {
    echo "<tr class='model' data-select='"  . $row["model"] . "'>"; // tr 에 class 지정, 각행의 공정을 데이터 속성으로 설정              
    echo "<td>" . $row["weight_minimum"] ."~" . $row["weight_maximum"] . "</td>";
    echo "<td>" . $row["reflectivity_minimum"] ."~" . $row["reflectivity_maximum"] . "</td>";
    echo "<td>" . $row["scratch_minimum"] ."~" . $row["scratch_maximum"] . "</td>";
    echo "<td>" . $row["coating_minimum"] ."~" . $row["coating_maximum"] . "</td>";
    echo "<td>" . $row["e_current_minimum"] ."~" . $row["e_current_maximum"] . "</td>";
    echo '</tr>';
}
    
echo "</table>";

//연결 종료
$conn->close();
?>
    <span id='lib' ><input type="submit"  value="입력"></span>
</div>
</form>

<script>
var dropButton = document.getElementById("model_select");
    dropButton.value = ""; //드롭다운 선택을 초기화

function filter_data() {
        var dropButton = document.getElementById("model_select");  //  현재 문서의 드롭다운 버튼 id 요소를 찾아 변수에 지정
        var selectedModel = dropButton.options[dropButton.selectedIndex].value;  // 드롭다운의 옵션에서 선택된 인덱스의 value를 변수에 지정 
        var rows = document.getElementsByClassName("model");  // 현재 문서의 class가 지정되어 있는 테이블 행 요소를 변수에 지정
        
        for (var i = 0; i < rows.length; i++) {   // row 개수 만큼 반복 실행
            if (rows[i].getAttribute("data-select") == selectedModel) {  // 각행의 data-select 속성으로 지정된 공정과 드롭다운에서 선택된 값이 일치하는지 확인
                rows[i].style.display = "table-row";  // 일치하면 행을 표시
            } 
            else {
                rows[i].style.display = "none";  // 일치하지 않으면 행을 숨김
            }
        }
    }
</script>  
</body>
</html>
