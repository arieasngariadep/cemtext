import pandas as pd
import numpy as np
import re
import sys
import datetime
# from datetime import datetime, timedelta, date
from datetime import timedelta

def openflat(namafile):
	with open(namafile, "r") as f:
		datamentah = []
		datatable = []
		newIndex = 0
		noMerchant = 0
		for i, line in enumerate(f):
			line = line.rstrip()
			if len(line) > 0 and line[0] == "1": newIndex = i

			if i>=newIndex and i<=newIndex+9:
				data = re.split(r'\s{2,}', line)
				if i==newIndex+1: 
					noMerchant = data[0]
					x = noMerchant.split()
					noMerchant = x[0]
				if i==newIndex+4:
					accountNumber = data[-1]
				if i==newIndex+8:
					procDate = data[0]
					y = procDate.split()
					procDate = y[3].strip()
					# procDate = datetime.datetime.strptime(procDate,'%d/%m/%y').strftime('%Y-%m-%d')

					
			else:
				data = re.split(r'\s{1,}', line)
				if len(data)==11:
					data.insert(1, "")
					
			
			
			
			datamentah.append(data)
			if len(data)==12 and len(line)>81 and data[2]!="BATCH":

				if(data[1] == ""):
					oo_batch = data[2]
					batch = data[3]
					seqnum = data[4]
					types = data[5]
					txn_date = data[6].strip()
					txn_date = datetime.datetime.strptime(txn_date, '%d/%m/%y').strftime('%Y-%m-%d')
					auth = data[7]
					cardnum = data[8].replace("-", "")
					amount = data[9].replace(",", "")
					rate = data[10]
					disc_amount = data[11].replace(",", "")
				else:
					oo_batch = data[1]
					batch = data[2]
					seqnum = data[3]
					types = data[4]
					txn_date = data[5].strip()
					txn_date = datetime.datetime.strptime(txn_date, '%d/%m/%y').strftime('%Y-%m-%d')
					auth = data[6]
					cardnum = data[7].replace("-", "")
					amount = data[8].replace(",", "")
					rate = data[9]
					disc_amount = data[11].replace(",", "")

				if(amount[-1] == "-"):
					amount = amount.replace("-", "")
					amount = "-" + amount
				else:
					amount = amount

				if(disc_amount[-1] == "-"):
					disc_amount = disc_amount.replace("-", "")
					disc_amount = "-" + disc_amount
				else:
					disc_amount = disc_amount

				d = pd.Timestamp(procDate)
				current_date_temp = datetime.datetime.strptime(procDate, "%d/%m/%y")
				
				if d.dayofweek < 5 :
					credit_date = current_date_temp
				else:
					if d.dayofweek == 5 :
						credit_date = current_date_temp + datetime.timedelta(days=2)
					else:
						credit_date = current_date_temp + datetime.timedelta(days=1)

				credit_date = credit_date.strftime('%Y-%m-%d')
				proc_date = current_date_temp.strftime('%Y-%m-%d')
						
				array = [noMerchant.strip(), accountNumber.strip(), oo_batch.strip(), batch.strip(), seqnum.strip(), types.strip(), txn_date.strip(), auth.strip(), cardnum.strip(), amount.strip(), rate.strip(), disc_amount.strip(), proc_date, credit_date]
				datatable.append(array)
		return pd.DataFrame(datatable)

if __name__ == '__main__':
	df = openflat("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Npre/npre_" + sys.argv[1] + ".txt")
	df.columns = ["MID", "ACCOUNT NUMBER", "OO BATCH", "BATCH", "SEQ", "TYPE", "TXN-DATE", "AUTH", "CARD NUMBER", "AMOUNT", "RATE", "DISC.AMT", "PROC DATE", "CREDIT DATE"]

	df.to_excel("C:/xampp/htdocs/cemtext/public/Import/folder_" + sys.argv[1] + "/Npre/HasilNpre.xlsx", index=False)