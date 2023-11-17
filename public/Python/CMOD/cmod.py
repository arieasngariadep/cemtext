import pandas as pd
import glob
from tools import *
import os
import re
import sys

def openFlat(namaFile):
    newDataTable = []
    dataTable = []
    newIndex = 0
    with open(namaFile, 'r', errors="ignore") as f, open("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/CMOD/temp.txt", 'w') as temp:
        for i, line in enumerate(f): #looping index dan value pada file txt perbaris
            if "BANK NEGARA INDONESIA" in line:
                newIndex = i
            line = line.rstrip()

            if i > newIndex+6:    
                if len(line)==0 or "---------------------" in line or "==================" in line or "CURRENCY" in line or "DEBITS" in line or "NUMBER" in line  or "GEN ACCEPTED" in line or "GEN REJECTED" in line or "TOT ACCEPTED" in line or "TOT REJECTED" in line: continue
                
                temp.write(line)
                temp.write("\n")     

    data = []
    newdata = None
    with open("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/CMOD/temp.txt", 'r', errors="ignore") as f:
        for i, line in enumerate(f):
            if line[62:63] == "/":
                data = re.split(r'\s+', line.strip())
                seq = data[0]
                noRek = data[1]
                noTran = data[2]
                jrnl = data[3]
                floatNominal = float(data[4].replace(',',''))
                nominal = int(floatNominal)
                proc_date = data[5]
                sysReport = data[6]
                cheque = data[7]
                err = data[8]                
                if len(data) <= 10:
                    message = data[9]
                else:
                    message = data[9]+" "+data[10]+" "+data[11]+" "+data[12]    
                dataLine = [seq, noRek, noTran, jrnl, nominal, proc_date, sysReport, cheque, err, message]
                newdata = i	

            if newdata is not None and i == newdata+1:
                ket1 = line[43:87].replace("\n", "").strip()
                data1 = [ket1]
                dataLine.extend(data1)
                ket2 = line[96:112].replace("\n", "").lstrip("0").strip()
                data2 = [ket2]
                dataLine.extend(data2)
                ket3 = line[113:].replace("\n", "").strip()
                data3 = [ket3]
                dataLine.extend(data3)
            if newdata is not None and i == newdata+2:
                ket4 = line[95:].replace("\n", "").strip()
                data4 = [ket4]
                dataLine.extend(data4)	   
            if newdata is not None and i == newdata+3:
                ket5 = line[95:].replace("\n", "").strip()
                data5 = [ket5]
                dataLine.extend(data5)	   
            if newdata is not None and i == newdata+4:
                ket6 = line[95:].replace("\n", "").strip()
                data6 = [ket6]
                dataLine.extend(data6)	   
                
                
            if line[62:63] == "/":
                dataTable.append(dataLine)
        
        for i in dataTable:
            if len(i) == 14:
                i += ["-","-"]
            elif len(i) == 15:
                i += ["-"] 
            newDataTable.append(i)

        return pd.DataFrame(dataTable)


if __name__ == "__main__":
    uploaded_file = glob.glob("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/CMOD/Data/*.txt")
    for file_path in uploaded_file:
        file_name = os.path.basename(file_path)

        file_name_without_extension = os.path.splitext(file_name)[0]
    
    df= GabungDataFolder("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/CMOD/Data",openFlat)

    df = df.applymap(lambda x: x.encode('unicode_escape').
					decode('utf-8') if isinstance(x, str) else x)
    
    col = ["SEQ", "NO REKENING", "TRAN", "JOURNAL", "NOMINAL", "PROC DATE", "SYS", "CHEQUE", "ERR", "MESSAGE", "KET1", "KET2", "KET3", "KET4", "KET5", "KET6"]
    df.columns = col

    new_cols = ["SEQ", "NO REKENING", "TRAN", "JOURNAL", "NOMINAL", "PROC DATE", "SYS", "CHEQUE", "ERR", "MESSAGE", "KET1", "KET2", "KET3", "KET4", "KET5", "KET6"]
    df = df.reindex(columns=new_cols)

    df.to_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/CMOD/HasilCMOD-"+ file_name_without_extension +".xlsx", index= False)