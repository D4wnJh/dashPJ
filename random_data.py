import pymysql
import matplotlib.pyplot as plt
import pandas as pd
import numpy as np
import random
np.random.seed(0)

# 폰트 설정
plt.rcParams['font.family'] = 'NanumGothic'

# 데이터베이스 연결
conn = pymysql.connect(host='localhost', user='root', password='1234', db='processDB')

cur2 = conn.cursor()
sql2 = 'SELECT * FROM daily_product_data_tbl'
cur2.execute(sql2)
result = cur2.fetchall()

df_list = []
month = list(range(1, 8))
date = list(range(1, 29))

model_weights = {'A': (89, 111), 'B': (79, 101), 'C': (99, 121)}
model_e_current = {'A': (268, 332), 'B': (238, 302), 'C': (298, 362)}

for i in month:
    for j in date:
        dates = '2023-0{}-{}'.format(i, j)

        for model in model_weights:
            weight_range = model_weights[model]
            e_current_range = model_e_current[model]

            data = pd.DataFrame({
                'date_': [dates] * 10,
                'model': [model] * 10,
                'weight': [np.random.randint(*weight_range) for _ in range(10)],
                'reflectivity': [np.random.randint(0, 12) for _ in range(10)],
                'scratch': [np.random.randint(0,7) for _ in range(10)],
                'coating': [round((np.random.uniform(94.5, 101)),1) for _ in range(10)],
                'e_current': [np.random.randint(*e_current_range) for _ in range(10)]
            })
            df_list.append(data)

try:
    with conn.cursor() as cursor:
        # daily_product_data_tbl 테이블에 데이터 삽입
        sql = "INSERT INTO daily_product_data_tbl (date_, model, weight, reflectivity, scratch, coating, e_current) VALUES (%s, %s, %s, %s, %s, %s, %s)"
        for df in df_list:
            values = df.values.tolist()
            cursor.executemany(sql, values)

        conn.commit()
        
except Exception as e:
    print("데이터 삽입 중 오류 발생:", e)

# 연결 닫기
conn.close()