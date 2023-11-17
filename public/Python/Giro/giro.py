import pandas as pd
from tools import *
import os
import re
import sys

def openflat(namafile):
	dataTable = []
	newDataTable = []
	newIndex = 0
	noRek = ""		# 24
	namaRek = ""	# 54
	# Read and write in temporary file
	with open(namafile, 'r', errors="ignore") as f, open("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Giro/temp.txt", 'w') as temp:
		for i, line in enumerate(f): #looping index dan value pada file txt perbaris
			if "REKENING KORAN IA" in line:
				newIndex = i
			line = line.rstrip() # .rstrip() menghilangkan spasi sebelah kanan

			if newIndex+4 == i:
				noRek = line[15:45].strip() # .strip() menghilangkan spasi
				namaRek = line[62:116].strip()

			if i > newIndex+7:
				tanggal = line[:12].strip()
				if len(line)==0 or "..........................." in line or "TANDA TANGAN - VERIFIKATOR" in line  or "SALDO AWAL" in line or ("0000000" in line[45:54] and len(line)==54) : continue

				if len(tanggal) == 10:
					# .ljust() menentukan panjang string yang diperbolehkan
					line = line.ljust(136)	
					line =  line + noRek.ljust(30) + namaRek
					temp.write(line)
					temp.write("\n")
							
				else:
					temp.write(line)
					temp.write("\n")

	data = []
	newdata = None
	with open("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Giro/temp.txt", 'r', errors="ignore") as f:
		for i, line in enumerate(f):
			if line[2:3] == "/":
				data = re.split(r'\s{2,}', line) # setiap 2 spasi potong(split)
				trx_date = data[0]
				journal = data[1]
				cab = data[2]
				teller = data[3]
				kd_tran = data[4]
				uraian_mutasi = data[5]
				mutasis = data[6]
				if len(data[6]) == 1:
					mutasi = 0.00
					pos_mutasi = data[6]
				else:	 
					mutasis = mutasis.split(" ")
					mutasi = mutasis[0]
					mutasi = mutasi.replace(",", "").replace(".00", "").strip()
					mutasi = float(mutasi)
					pos_mutasi = mutasis[1]
				saldos = data[7]
				saldos = saldos.split(" ")
				saldo = saldos[0]
				saldo = saldo.replace(",", "").replace(".00", "").strip()
				saldo = float(saldo)
				#validate if pos_saldo is null
				if len(saldos) < 2:
					pos_saldo = "-"
				else:
					pos_saldo = saldos[1]
				rekening = data[8].strip()
				rekening = rekening.zfill(16)
				account_name = data[9].strip()
				data1 = [rekening, account_name, trx_date, journal, cab, teller, kd_tran, uraian_mutasi, mutasi, pos_mutasi, saldo, pos_saldo]
				newdata = i	
				
			if newdata is not None and i == newdata+1:
				if "00000000000"in line[46:62]:
					ket2 = line[63:75].replace("\n", "").strip()
					data2 = [ket2]
					data1.extend(data2)	
				else:
					ket2 = line[46:].replace("\n", "").strip()
					data2 = [ket2]
					data1.extend(data2)	
			if newdata is not None and i == newdata+2:
				ket3 = line[46:].replace("\n", "").strip()
				data3 = [ket3]
				data1.extend(data3)	
			if newdata is not None and i == newdata+3:
				ket4 = line[46:].replace("\n", "").strip()
				data4 = [ket4]
				data1.extend(data4)
			if newdata is not None and i == newdata+4:
				ket5 = line[46:].replace("\n", "").strip()
				data5 = [ket5]
				data1.extend(data5)

			if line[2:3] == "/":
				dataTable.append(data1)	
			
	
		for i in dataTable:
			if len(i) == 14:
				i += ["-","-"]
			elif len(i) == 15:
				i += ["-"] 
			newDataTable.append(i)				
	return pd.DataFrame(newDataTable)		

if __name__ == "__main__":
	df= GabungDataFolder("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Giro/Data",openflat)

	df = df.applymap(lambda x: x.encode('unicode_escape').
					decode('utf-8') if isinstance(x, str) else x)

	col = ["NO. ACCOUNT", "NAMA ACCOUNT", "TRX DATE", "JOURNAL", "CAB", "TELLER", "KD TRAN", "KET1", "MUTASI", "POS MUTASI", "SALDO", "POS SALDO", "KET2", "KET3","KET4","KET5"]
	df.columns = col

	new_cols = ["NO. ACCOUNT", "NAMA ACCOUNT", "TRX DATE", "JOURNAL", "CAB", "TELLER", "KD TRAN", "KET1", "KET2", "KET3", "KET4","KET5", "MUTASI", "POS MUTASI", "SALDO", "POS SALDO"]
	df = df.reindex(columns=new_cols)

	df.to_excel("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Giro/HasilGiro.xlsx", index= False)
