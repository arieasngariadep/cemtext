import pandas as pd
import sys

#Insert complete path to the excel file and index of the worksheet
df_incoming = pd.read_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Incoming/File_A.xlsx")
df_datab = pd.read_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Incoming/File_B.xlsx")

df_bin = pd.read_excel("C:/xampp/htdocs/cemtext/public/Python/Incoming/Bin.xlsx")

df_incoming["f1"] = df_incoming["f1"].astype(str)
df_datab["f1"] = df_datab["f1"].astype(str)
df_bin["bin"] = df_bin["bin"].astype(str)

df_incoming['Order'] = df_incoming.groupby(['f1','f2','f4']).cumcount()
df_datab['Order'] = df_datab.groupby(['f1','f2','f4']).cumcount()

df_incoming["f2"] = df_incoming["f2"].astype(str)
df_datab["f2"] = df_datab["f2"].astype(str)
df_incoming["f3"] = df_incoming["f3"].astype(str)
df_datab["f3"] = df_datab["f3"].astype(str)
df_incoming["f4"] = df_incoming["f4"].astype(str)
df_datab["f4"] = df_datab["f4"].astype(str)
df_incoming["Order"] = df_incoming["Order"].astype(str)
df_datab["Order"] = df_datab["Order"].astype(str)

left_join_df = pd.merge(df_incoming, df_datab, on=['f1','f2','f3','f4','Order'], how='left', indicator=True)
left_join_df.to_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Incoming/HasilJoin.xlsx", index=False)

summary = pd.DataFrame()

summary['CardNumber'] = left_join_df['f1']
summary['arn'] = left_join_df['f4']
summary['bin'] = left_join_df['f1'].str[:6]
summary['TransactionCode'] = left_join_df['f10_y']
summary['SettlementCurrency'] = left_join_df['f5_x'].str[-3:]
summary['SettlementAmount'] = left_join_df['f5_x'].str.replace('-360','')
summary['SettlementAmount'] = summary['SettlementAmount'].str.replace('-840','')
summary['BillingAmountCxrm'] = left_join_df['f6_x']
summary['BillingAmountCxrb'] = left_join_df['f17']
summary['FeeAmount'] = left_join_df['f7_x']

s_df = pd.merge(summary, df_bin, on=['bin'], how='left')
s_df.to_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Incoming/Summary.xlsx", index=False)