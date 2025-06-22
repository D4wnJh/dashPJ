<!-- pjdash_r07.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Production Data</title>
    <style>
        table {
            border-collapse: collapse;
            width: 1800px;            
        }        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }        
        th {
            background-color: #f2f2f2;
        }   
        img {
            width: 1800px; height: 375px; 
            }    
        a {
            text-decoration: none;            
        }
        .box1 {
            width:1800px; height: 70px;
            text-align: center; align-items:center; justify-content: center;
            display: flex;
            margin-bottom: 5px; margin-top:5px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            background-color:#014099; 
            color: white;  
        }
        .box2{
            width:895px; height: 80px; background-color: #CEECF5;
            text-align: center; align-items:center; justify-content: center;
            display: flex;
            margin-bottom: 10px; margin-top:10px; margin-right:10px;
            float:left;
            font-size:25px;  font-weight: 500;          
        }
        #box3{            
            float: right;
            margin-left:20px;
            margin-bottom:20px;
            font-weight:bold;
            padding: 5px 8px 5px 8px;           
        }
        #box4{
            width:1800px;
            position: relative;
            top: 20px
            z-index: 2;            
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
    <div id='box4'>
    <span id='box3'><a href='http://localhost/pjdash_r07.php' class='underline-effect'>대시 보드</a></span>
    <span id='box3'><a href='http://localhost/show_monthly_sum.php' class='underline-effect'>월간 실적</a></span>
    <span id='box3'><a href='http://localhost/show_daily_sum.php' class='underline-effect'>일간 실적</a></span> 
    <span id='box3'><a href='http://localhost/show_all_data2.php' class='underline-effect'>생산 log</a></span>
    <span id='box3'><a href='http://localhost/production_insert_panel.php' class='underline-effect'>입력 패널</a></span> 
    </div>
    <div class='box1'>
        <h1 style='font-size=50px;'>생산 현황 대시보드</h1>
    </div>
    <?php
        // 데이터베이스 연결 설정
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "processDB";
        
        // 데이터베이스 연결
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // 연결 확인
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // 쿼리 실행
        $db_data = "SELECT * FROM monthlyTbl";    // monthlyTbl 에서 모든 열의 데이터를 가져오는 쿼리 
        $result = $conn->query($db_data);        // 데이터 베이스 연결 객체에서 query() 메서드로 쿼리 실행하고 변수에 할당
        
        // 테이블 출력        
        echo "<label><a href='http://localhost/pjdash_r07.php' >월간 생산 현황: </a></label>";    // 드롭 다운 버튼의 라벨 
        echo "<select id='month_selcect' onchange='selectMonth()'>";    // 드롭다운 버튼을 생성하고 id 지정, 드롭 다운 메뉴 변경 시 실행할 JS 함수 지정
            
        while ($row = $result->fetch_assoc()) {      // result에서 다음행의 데이터를 가져와서 row에 할당, 모든 행의 데이터를 처리하도록 loop
        echo "<option value='". $row["month_year"] ."'>" . $row["month_year"] . "</option>";}   // 드롭 다운 선택가능한 월을 옵션으로 지정하고 전송한다 

        echo "</select>";
        echo "<br><br>";
        echo "<table>";
        echo "<tr><th>년월</th><th>생산량</th><th>불량수</th><th>불량율</th><th>양품수</th><th>수율</th></tr>";
            
        mysqli_data_seek($result, 0);  // 결과셋 포인터를 처음으로 이동하여 테이블 데이터를 생성 할 수 있도록 한다
            
        while ($row = $result->fetch_assoc()) {
            echo "<tr class='month_data' data-select='" . $row["month_year"] . "'>";   // tr 에 class 지정, 각행의 월을 데이터 속성으로 설정
            echo "<td>" . $row["month_year"] . "</td>";
            echo "<td>" . ($row["good_quantity"] + $row['defective_quantity']) . "</td>";
            echo "<td>" . $row["defective_quantity"] . "</td>";
            echo "<td>" . round(($row["defective_quantity"] / ($row["good_quantity"] + $row['defective_quantity'])) * 100, 1) . "%</td>";
            echo "<td>" . $row["good_quantity"] . "</td>";
            echo "<td>" . (100 - round(($row["defective_quantity"] / ($row["good_quantity"] + $row['defective_quantity'])) * 100, 1)) . "%</td>";
            echo "</tr>";}
        echo "</table>";       

        // 연결 종료
        $conn->close();
    ?>          
    
<script>  
        var dropButton = document.getElementById("month_selcect");
        dropButton.value = ""; //드롭다운 선택을 초기화

        function selectMonth() {
            var dropButton = document.getElementById("month_selcect");  //  현재 문서의 드롭다운 버튼 id 요소를 찾아 변수에 지정
            var selectedMonth = dropButton.options[dropButton.selectedIndex].value;  // 드롭다운의 옵션에서 선택된 인덱스의 value를 변수에 지정 즉 2023-07 등의 값
            var rows = document.getElementsByClassName("month_data");  // 현재 문서의 class가 지정되어 있는 테이블 행 요소를 변수에 지정
            
            for (var i = 0; i < rows.length; i++) {            // row 개수 만큼 반복 실행
                if (rows[i].getAttribute("data-select") == selectedMonth) {  // 각행의 data-select 속성으로 지정된 월과 드롭다운에서 선택된 값이 일치하는지 확인
                    rows[i].style.display = "table-row";  // 일치하면 행을 표시
                } 
                else {
                    rows[i].style.display = "none";  // 일치하지 않으면 행을 숨김
                }
            }
        }
    </script>   
    <table>
        <tr>
            <td>
                <img src="pdgraph.jpg" alt="그래프">
            </td>            
        </tr>        
    </table>   
</body>
</html>


