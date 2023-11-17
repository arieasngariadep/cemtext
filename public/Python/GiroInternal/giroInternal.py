import pandas as pd
from tools import *
import os
import re
import sys

def openflat(namafile):
	dataTable = []
	newDataTable = []
	newIndex = 0
	kepada = ""
	noRek = ""	
	# Read and write in temporary file	
	with open(namafile, 'r', errors="ignore") as f,open("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/GiroInternal/temp.txt", 'w') as temp:
		for i, line in enumerate(f): #looping index dan value pada file txt perbaris
			if "CR00PR" in line:
				newIndex = i
			line = line.rstrip() # .rstrip() menghilangkan spasi sebelah kanan

			if newIndex+6 == i:
				cab = line[18:45].strip()
				period = line[98:127].strip() # .strip() menghilangkan spasi
			if newIndex+8 == i:
				tgl_cetak = line[98:110].strip()
			if newIndex+9 == i:
				giroPenampung = line[84:127].strip()
			if newIndex+10 == i:
				kepada = line[2:50].strip()
				noRek = line[98:109].strip()
			if newIndex+11 == i:
				alamat1 = line[2:50].strip()
				npwp = line[98:127].strip()
				if len(npwp) == 0:
					noNPWP = "-"
				else:
					noNPWP = npwp
			if newIndex+12 == i:
				currency = line[98:105].strip()
			if newIndex+13 == i:
				alamat2 = line[2:60].strip()
			if newIndex+14 == i:
				alamatFull = alamat1 + ", " + alamat2 + " " + line[2:60].strip()
		
			
			if i > newIndex+17:
				tanggal = line[:12].strip()
				if len(line)==0 or "=======================" in line or "JUMLAH TRANSAKSI" in line or "SALDO AWAL" in line or "SALDO TERTINGGI" in line or "SALDO TERENDAH" in line or "S.E & O" in line or "PT. BANK NEGARA INDONESIA (PERSERO) TBK" in line or "DIVISI OPERASIONAL" in line[102:122] : continue

				if len(tanggal) == 10:
					# .ljust() menentukan panjang string yang digunakan
					line = line.ljust(136).lstrip()	
					line = line + cab.ljust(30) + period.ljust(30) + tgl_cetak.ljust(15)+ giroPenampung.ljust(30) + kepada.ljust(40) + noRek.ljust(20) + noNPWP.ljust(30) + currency.ljust(8) + alamatFull
					temp.write(line)
					temp.write("\n")
				else:
					temp.write(line)
					temp.write("\n")

	data = []
	newdata = None
	with open("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/GiroInternal/temp.txt", 'r', errors="ignore") as f:
		for i, line in enumerate(f):
			if line[2:3] == "/":
				data = re.split(r'\s{2,}', line) # setiap 2 spasi potong(split)
				trx_date = data[0]
				valuta_date = data[1]
				noDok = data[2]
				uraian_mutasi = data[3]
				mutasis = data[4]
				mutasi = mutasis.replace(",", "").replace(".00", "").strip()
				mutasi = float(mutasi)
				pos_mutasi = data[5]
				saldo = data[6]
				saldo = saldo.replace(",", "").replace(".00", "").strip()
				saldo = float(saldo)
				divName = data[7].strip()
				prd = data[8].strip()
				tglCetak = data[9].strip()
				namaGiro = data[10].strip()
				tujuan = data[11].strip()
				rekening = data[12].strip()
				npwp_validated = data[13].strip()
				ccy = data[14].strip()
				alamat_penuh = data[15].strip()
				data1 = [rekening,divName, prd, tglCetak, namaGiro, tujuan,alamat_penuh, npwp_validated, ccy, trx_date, valuta_date, noDok, uraian_mutasi, mutasi, pos_mutasi, saldo]
				newdata = i	
				
			if newdata is not None and i == newdata+1:
				ket2 = line[44:].replace("\n", "").strip()
				data2 = [ket2]
				data1.extend(data2)
			if newdata is not None and i == newdata+2:
				ket3 = line[44:].replace("\n", "").strip()
				data3 = [ket3]
				data1.extend(data3)	
			if newdata is not None and i == newdata+3:
				ket4 = line[44:].replace("\n", "").strip()
				data4 = [ket4]
				data1.extend(data4)
			if newdata is not None and i == newdata+4:
				ket5 = line[44:].replace("\n", "").strip()
				data5 = [ket5]
				data1.extend(data5)
			# if newdata is not None and i == newdata+5:
			# 	ket6 = line[30:].replace("\n", "").strip()
			# 	data6 = [ket6]
			# 	data1.extend(data6)
			# if newdata is not None and i == newdata+6:
			# 	ket7 = line[30:].replace("\n", "").strip()
			# 	data7 = [ket7]
			# 	data1.extend(data7)

			if line[2:3] == "/":
				dataTable.append(data1)	
			
	
		for i in dataTable:
			if len(i) == 18:
				i += ["-","-"] 
			elif len(i) == 19:
				i += ["-"] 
			newDataTable.append(i)				
	return pd.DataFrame(newDataTable)		

if __name__ == "__main__":
	df= GabungDataFolder("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/GiroInternal/Data",openflat)

	df = df.applymap(lambda x: x.encode('unicode_escape').
					decode('utf-8') if isinstance(x, str) else x)

	col = ["NO. ACCOUNT", "NAMA DIVISI", "PERIODE", "TANGGAL CETAK", "NAMA GIRO", "TUJUAN", "ALAMAT", "NPWP", "CURRENCY", "TRX DATE", "VALUTA DATE", "NO DOKUMEN", "KET1", "MUTASI", "POS MUTASI", "SALDO", "KET2", "KET3", "KET4", "KET5"]
	df.columns = col

	new_cols = ["NO. ACCOUNT", "NAMA DIVISI", "PERIODE", "TANGGAL CETAK", "NAMA GIRO", "TUJUAN", "ALAMAT", "NPWP", "CURRENCY", "TRX DATE", "VALUTA DATE", "NO DOKUMEN", "KET1", "KET2", "KET3", "KET4", "KET5","MUTASI", "POS MUTASI", "SALDO"]
	df = df.reindex(columns=new_cols)

	df.to_excel("D:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/GiroInternal/HasilGiroInternal.xlsx", index= False)
