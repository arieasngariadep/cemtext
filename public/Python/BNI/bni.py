import pandas as pd
import numpy as np
import re
import sys

def openflat(namafile):
	with open(namafile, "r", encoding="utf8") as f:
		data1 = []
		data2 = []
		data3 = []
		data4 = []

		newIndex = 0
		newdata = 0

		datatable = []
		newdata = None

		for i, line in enumerate(f):
			if len(line) > 0 and "REPORT" in line:
				newIndex = i
				
			if i >= newIndex and i <= newIndex+5:
				data = re.split(r'\s{2,}', line)
				
				if i == newIndex+1:
					proc_date = data[2]
					proc_date = re.sub("PROC DATE: ", "", proc_date)
					proc_date = re.sub("\n", "", proc_date)
				if i == newIndex+3:
					kode_file = data[1]
					kode_file = re.sub("\n", "", kode_file)
					
			else:
				data = re.split(r'\s{1,}', line)
				if len(data) == 11:
					data.insert(1, "")
			
			if len(data) >= 10 and len(line) > 90 and line[62:63] == "/":
				last1 = re.sub("\n", "", line[86:130])
				rekening = line[6:22].strip()
				rekening = rekening.zfill(16)
				nominal = line[37:56].replace(",", "")
				nominal = nominal.replace(".00", "").strip()
				data1 = [kode_file, proc_date, line[0:5].strip(), rekening, line[23:29].strip(), line[30:36].strip(), nominal, line[57:68].strip(), line[69:73].strip(), line[74:80].strip(), line[81:85].strip(), last1, '']
				newdata = i
			if newdata is not None and i == newdata+1:
				last2 = re.sub("\n", "", line[113:132])
				ket3 = line[88:95].strip()
				ket4 = line[96:112].strip()
				ket4 = ket4.zfill(16)
				data2 = [line[43:70], line[71:87], ket3, ket4, last2]
				data1.extend(data2)
			if newdata is not None and i == newdata+2:
				last3 = re.sub("\n", "", line[95:132])
				data3 = [line[95:132]]
				data1.extend(data3)
			if newdata is not None and i == newdata+3:
				last4 = re.sub("\n", "", line[95:132])
				data4 = [last4]
				data1.extend(data4)

			if line[62:63] == "/":
				datatable.append(data1)
		return pd.DataFrame(datatable)

if __name__ == '__main__':
	df = openflat("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/BNI/Data/bni_" + sys.argv[1] + ".txt")
	col = ["KODE_FILE", "TGL_TRX", "SEQ_NO", "REKENING", "TRAN", "JRNL", "NOMINAL", "TANGGAL", "SYS", "CHEQUE", "ERR", "MESSAGE", "SUSPENSE_TRF_DTL", "1", "2", "3", "4", "5", "6", "7"]
	df.columns = col

	df["SUSPENSE_TRF_DTL"] = df["SUSPENSE_TRF_DTL"].astype(str)
	df["SEQ_NO"] = df["SEQ_NO"].astype(str)
	df["SEQ_NO"] = df["SEQ_NO"].str.lstrip()
	df["3"] = df["3"].astype(str)
	df["3"] = df["3"].str.lstrip()

	df["REKENING"] = df["REKENING"].astype(str)
	df["4"] = df["4"].astype(str)
	df["CHEQUE"] = df["CHEQUE"].astype(str)
	df["ERR"] = df["ERR"].astype(str)

	df["TGL_TRX"].replace(re.compile(".*00/00/00.*"), "", inplace=True)
	df["6"].replace(re.compile(".*RUN DATE :.*"), "", inplace=True)
	df["6"].replace(re.compile(".*PROC DATE:.*"), "", inplace=True)
	df["7"].replace(re.compile(".*RUN DATE :.*"), "", inplace=True)
	df["7"].replace(re.compile(".*PROC DATE:.*"), "", inplace=True)

	df.to_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/BNI/HasilBNI.xlsx", index= False)