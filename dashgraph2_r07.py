## dashgraph2_r07.py
import pymysql
import matplotlib.pyplot as plt
import pandas as pd
import numpy as np

# 폰트 설정
plt.rcParams['font.family'] = 'NanumGothic'

# 데이터베이스 연결
db = pymysql.connect(host='localhost', user='root', password='1234', db='processDB')

# 커서 생성 및 쿼리 실행
cur = db.cursor()
sql = 'SELECT * FROM monthlyTBL'
cur.execute(sql)
result = cur.fetchall()



# result 를 데이터프레임으로 변환
df = pd.DataFrame(result, columns=['month_year', 'good_quantity', 'detective_quantity', 'monthly_production', 'defect_rate', 'yeild_rate'])

month_year = df['month_year']
df_a = df['monthly_production']
df_b = df['good_quantity']
df_c = df['detective_quantity']
df_d = df['defect_rate']
df_e = df['yeild_rate']

bar_width = 0.25  # bar 의 두께
index = np.arange(len(month_year))

fig, (ax1, ax3) = plt.subplots(1, 2, figsize=(20, 4))

# 1 서브플롯에 생상량과 양품수 그리기
ax1.bar(month_year, df_a, bar_width, color='dodgerblue', label='생산량')
ax2 = ax1.twinx()  # ax1과 ax2의 x축 공유
ax2.bar(index + bar_width + 0.05, df_b, bar_width, color='limegreen', label='양품수')
ax1.set_xticks(index + bar_width / 2 + 0.025)
ax1.set_yticks([])  # y축 레이블 제거
ax1.set_ylim([0, 1100])
ax2.set_ylim([0, 1100])

# 생산량 값을 그래프에 수치 표시
for i in range(len(month_year)):
    t = ax1.patches[i]
    ax1.text(x=t.get_x() + t.get_width() - 0.25,
                y=t.get_height() + 30,
                s='{}'.format(t.get_height()))

# 양품수 값을 그래프에 수치 표시 
for i in range(len(month_year)):   
    t = ax2.patches[i]
    ax2.text(x=t.get_x() + t.get_width() - 0.21,
                y=t.get_height() + 20,
                s='{}'.format(t.get_height()))

# 2 서브플롯에 불량수, 불량률, 수율 그리기
ax3.bar(month_year, df_c, bar_width, color='dodgerblue', label='불량수')
ax4 = ax3.twinx()
ax4.bar(index + bar_width + 0.05, df_d, bar_width, color='red', label='불량률')

ax3.set_ylim(0, 420)
ax3.set_xticks(index + bar_width / 2 + 0.025, month_year)
ax3.set_yticks(np.arange(0, 120))
ax4.set_yticks(np.arange(0, 120))

ax5 = ax3.twinx()
ax5.plot(index + bar_width / 2 + 0.025, df_e, 'go-', label='수율')
ax5.set_yticks(np.arange(60, 75, 5))

ax3.set_yticks([])  # y축 레이블 제거
ax4.set_yticks([])  # y축 레이블 제거

# 불량수 값을 그래프에 수치 표시
for i in range(len(month_year)):
    t = ax3.patches[i]
    ax3.text(x=t.get_x() + t.get_width() - 0.21,
                y=t.get_height() + 3,
                s='{}'.format(t.get_height()))

# 불량률 값을 그래프에 수치 표시  
for i in range(len(month_year)):
    t = ax4.patches[i]       
    ax4.text(x = t.get_x() + t.get_width() - 0.21,
        y = t.get_height() +2,
        s = '{}%'.format(t.get_height()))    

# 수율 값을 그래프에 수치 표시
for i in range(len(month_year)):
    height = float(df_e[i])
    ax5.text(index[i] + bar_width / 2 + 0.025, height + 0.2 , '%.1f%%' %height, ha='center', va='bottom')

# 범례 표시 및 위치 조정
ax1.legend(loc='best')
ax2.legend(loc='best', bbox_to_anchor=(1,0.93))
ax3.legend(loc='best')
ax4.legend(loc='best', bbox_to_anchor=(1.0, 0.94))
ax5.legend(loc='best', bbox_to_anchor=(1.0, 0.87))

plt.tight_layout()
plt.savefig('./pdgraph.jpg')
plt.clf()  # 위 그래프 모든걸 다 지워줌


##모델별 월간 집계 그래프
sql2 = 'SELECT * FROM monthly_product_total_Tbl'
cur.execute(sql2)
result2 = cur.fetchall()
df2=pd.DataFrame(result2,columns=['month_year','model','weight_count','reflectivity_count','scratch_count','coating_count','e_current_count', 'good_quantity', 'detective_quantity', 'total_quantity','total_defect_count','defect_rate_weight','defect_rate_reflectivity','defect_rate_scratch','defect_rate_coating','defect_rate_e_current','defect_rate_product'])

month_year=df2['month_year']
model=df2['model']
total_defect_count=df2['total_defect_count']
index2 = np.arange(len(month_year))

fig, ax7 = plt.subplots(figsize=(10, 6))
unique_month_year=month_year.unique()
index2 = np.arange(len(unique_month_year))
models = model.unique()


for model_type in models:
    model_data = df2[df2['model'] == model_type]
    

for model_type in models:
    model_data = df2[df2['model'] == model_type]  # 특정 모델 데이터 추출
    ax7.plot(index2, model_data['total_defect_count'], 'o-', label=model_type)
    for i in range(len(model_data)):
        height = model_data['total_defect_count'].iloc[i]
        ax7.text(index2[i], height , str(height), ha='center', va='bottom')
    
ax7.set_xlabel('월 별')
ax7.set_ylabel('총 불량수')
ax7.legend()

# x축 라벨 설정
ax7.set_xticks(index2)  # x축 라벨의 위치 설정
ax7.set_xticklabels(unique_month_year)
plt.tight_layout()  # 그래프 간격 조절
plt.savefig('./month_def.jpg')

#모델별 불량 항목 도넛
sql3 = 'SELECT * FROM monthly_product_total_Tbl'
cur.execute(sql3)
result3 = cur.fetchall()
df3=pd.DataFrame(result2,columns=['month_year','model','weight_count','reflectivity_count','scratch_count','coating_count','e_current_count', 'good_quantity', 'detective_quantity', 'total_quantity','total_defect_count','defect_rate_weight','defect_rate_reflectivity','defect_rate_scratch','defect_rate_coating','defect_rate_e_current','defect_rate_product'])

model=df3['model']
weight = df3['weight_count']
reflectivity =df3['reflectivity_count']
scratch =df3['scratch_count']
coating =df3['coating_count']
e_current =df3['e_current_count']
model_types = model.unique()


fig, axes = plt.subplots(1,3,figsize=(12,4))
for model_idx in range(len(model_types)):
    model_type = model_types[model_idx]
    model_data = df3[df3['model'] == model_type]
    data = [model_data['weight_count'].sum(), model_data['reflectivity_count'].sum(),
            model_data['scratch_count'].sum(), model_data['coating_count'].sum(),
            model_data['e_current_count'].sum()]

    ax = axes[model_idx]
    ax.pie(data,startangle=90,counterclock=False,labels=data,
        textprops={'fontsize': 12},
        wedgeprops={'width': 0.2, 'edgecolor': 'w', 'linewidth': 3})
    ax.set_title(f'Model {model_type}', fontsize=15)

plt.legend(labels=['중량','반사율','흠집','도장','소비전류'],loc='best', bbox_to_anchor=(1,1))

plt.tight_layout()
plt.savefig('./donut.jpg')
plt.clf()  # 위 그래프 모든걸 다 지워줌

db.close()


