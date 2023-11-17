import pandas as pd
from tools import *
import os
import re
import sys

def openflat(namafile):
	dataTable = []
	newIndex = 0
	with open(namafile, 'r', errors="ignore") as f, open("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Amex/temp.txt", 'w') as temp:
		for i, line in enumerate(f):
			if line[0] == "1":
				newIndex = i
			line = line.rstrip()

			if newIndex+6 == i:
				data = re.split(r'\s{2,}', line)
				ftn = line[:6].strip()
				desc = line[6:].strip()
			
			if "ISS PROC:" not in line and "ISS INST:" not in line and "POS DATA:" not in line and "TRAN DT-TM:" not in line and "INTG FEE:" not in line and "MTCH KEY:" not in line and "COUNTRY:" not in line and "CITY   :" not in line and "POSTAL :" not in line : continue

			if "PAN:" in line:
				line = line.ljust(136)	
				line = line + "FTN:" + ftn.ljust(10) + "DESC:" + desc
				temp.write(line)
				temp.write("\n")
			else:
				temp.write(line)
				temp.write("\n")

	data1 = []
	data2 = []

	newIndex = 0
	newdata = None
	with open("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Amex/temp.txt", 'r', errors="ignore") as f:
		for i, line in enumerate(f):
			if line[3:7] == "PAN:":
				pan = line[3:32].replace("PAN:", "").strip()
				proc_cde = line[32:53].replace("PROC CDE:", "").strip()
				function = line[53:70].replace("FUNCTION:", "").strip()
				acq_proc = line[70:97].replace("ACQ PROC:", "").strip()
				iss_proc = line[97:120].replace("ISS PROC:", "").strip()
				msg = line[120:136].replace("MSG#:", "").strip()
				ftn = line[136:150].replace("FTN:", "").strip()
				desc = line[150:].replace("DESC:", "").strip()
				data1 = [ftn, desc, pan, proc_cde, function, acq_proc, iss_proc, msg]
				newdata = i
			if newdata is not None and i == newdata+1:
				arn = line[3:32].replace("ARN:", "").strip()
				approval = line[32:53].replace("APPROVAL:", "").strip()
				msg_rsn = line[53:70].replace("MSG RSN :", "").strip()
				acq_inst = line[70:97].replace("ACQ INST:", "").strip()
				iss_inst = line[97:].replace("ISS INST:", "").strip()
				data2 = [arn, approval, msg_rsn, acq_inst, iss_inst]
				data1.extend(data2)
			if newdata is not None and i == newdata+2:
				tid = line[3:32].replace("TID:", "").strip()
				terminal = line[32:53].replace("TERMINAL:", "").strip()
				formats = line[53:70].replace("FORMAT  :", "").strip()
				chip_pin = line[70:97].replace("CHIP/PIN:", "").strip()
				pos_data = line[97:].replace("POS DATA:", "").strip()
				data3 = [tid, terminal, formats, chip_pin, pos_data]
				data1.extend(data3)
			if newdata is not None and i == newdata+3:
				tran_dt_tm = line[3:32].replace("TRAN DT-TM:", "").strip()
				tran = line[32:66].split("-")
				tran_amt = tran[0].replace("TRAN AMT:", "").replace(",", "").replace(".00", "").strip()
				tran_amt = float(tran_amt)
				tran = tran[1].split("(")
				tran_ccy = tran[0].strip()
				tran_exp = tran[1].replace(")", "").strip()
				pres = line[66:100].split("-")
				pres_amt = pres[0].replace("PRES AMT:", "").replace(",", "").replace(".00", "").strip()
				pres_amt = float(pres_amt)
				pres = pres[1].split("(")
				pres_ccy = pres[0].strip()
				pres_exp = pres[1].replace(")", "").strip()
				setl = line[100:].split("-")
				setl_amt = setl[0].replace("SETL AMT:", "").replace(",", "").replace(".00", "").strip()
				setl_amt = float(setl_amt)
				setl = setl[1].split("(")
				setl_ccy = setl[0].strip()
				setl_exp = setl[1].replace(")", "").strip()
				data4 = [tran_dt_tm, tran_amt, tran_ccy, tran_exp, pres_amt, pres_ccy, pres_exp, setl_amt, setl_ccy, setl_exp]
				data1.extend(data4)
			if newdata is not None and i == newdata+4:
				proc_dt_tm = line[3:32].replace("PROC DT-TM:", "").strip()
				auth = line[32:66].split("-")
				auth_amt = auth[0].replace("AUTH AMT:", "").replace(",", "").replace(".00", "").strip()
				# auth_amt = float(auth_amt)
				auth = auth[1].split("(")
				auth_ccy = auth[0].strip()
				auth_exp = auth[1].replace(")", "").strip()
				conv_rte = line[66:100].replace("CONV RTE:", "").strip()
				intg_fee = line[100:].split("-")
				intg_fee_amt = intg_fee[0].replace("INTG FEE:", "").replace(",", "").replace(".00", "").strip()
				intg_fee_amt = float(intg_fee_amt)
				intg_fee = intg_fee[1].split("(")
				intg_fee_ccy = intg_fee[0].strip()
				intg_fee_exp = intg_fee[1].replace(")", "").strip()
				data5 = [proc_dt_tm, auth_amt, auth_ccy, auth_exp, conv_rte, intg_fee_amt, intg_fee_ccy, intg_fee_exp]
				data1.extend(data5)
			if newdata is not None and i == newdata+5:
				setl_dt_tm = line[3:32].replace("SETL DT-TM:", "").strip()
				spec_pgm = line[32:53].replace("SPEC PGM:", "").strip()
				mtch_typ = line[53:66].replace("MTCH TYP:", "").strip()
				mtch_key = line[66:96].replace("MTCH KEY:", "").strip()
				eci = line[96:102].replace("ECI:", "").strip()
				markup = line[102:].replace("MARKUP:", "").strip()
				data6 = [setl_dt_tm, spec_pgm, mtch_typ, mtch_key, eci, markup]
				data1.extend(data6)
			if newdata is not None and i == newdata+6:
				se_nbr = line[3:32].replace("S/E NBR :", "").strip()
				mcc = line[32:43].replace("MCC:", "").strip()
				name = line[43:89].replace("NAME:", "").strip()
				country = line[89:102].replace("COUNTRY:", "").strip()
				region = line[102:].replace("REGION:", "").strip()
				data7 = [se_nbr, mcc, name, country, region]
				data1.extend(data7)
			if newdata is not None and i == newdata+7:
				se_alt = line[3:32].replace("S/E ALT :", "").strip()
				mna = line[32:43].replace("MNA:", "").strip()
				addr = line[43:89].replace("ADDR:", "").strip()
				city = line[89:].replace("CITY   :", "").strip()
				data8 = [se_alt, mna, addr, city]
				data1.extend(data8)
			if newdata is not None and i == newdata+8:
				postal = line[89:].replace("POSTAL :", "").strip()
				data9 = [postal]
				data1.extend(data9)

			if line[3:7] == "PAN:":
				dataTable.append(data1)

	return pd.DataFrame(dataTable)

if __name__ == "__main__":
	df= GabungDataFolder("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Amex/Data",openflat)

	col = ["FTN", "DESC", "PAN", "PROC CDE", "FUNCTION", "ACQ PROC", "ISS PROC", "MSG#", "ARN", "APPROVAL", "MSG RSN", "ACQ INST", "ISS INST", "TID", "TERMINAL", "FORMAT", "CHIP/PIN", "POSDATA", "TRAN DT-TM", "TRAN AMT", "TRAN CCY", "TRAN EXP", "PRES AMT", "PRES CCY", "PRES EXP", "SETL AMT", "SETL CCY", "SETL EXP", "PROC DT-TM", "AUTH AMT", "AUTH CCY", "AUTH EXP", "CONV RTE", "INTG FEE AMT", "INTG FEE CCY", "INTG FEE EXP", "SETL DT-TM", "SPEC PGM", "MTCH TYP", "MTCH KEY", "ECI", "MARKUP", "S/E NBR", "MCC", "NAME", "COUNTRY", "REGION", "S/E ALT ", "MNA", "ADDR", "CITY", "POSTAL"]
	df.columns = col

	df.to_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Amex/HasilAmex.xlsx", index= False)