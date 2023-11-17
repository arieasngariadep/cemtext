import pandas as pd
import os

def getFileInFolder(Folder):
    arr = os.listdir(Folder)
    cwd = os.getcwd()
    return [os.path.join(cwd,Folder,x) for x in arr]

def xd(namafile):
    df = pd.read_csv(namafile, header=None)
    return df

def GabungDataFolder(Folder, bukaSatuFile):
    df = None
    listFile = getFileInFolder(Folder)
    for file in listFile:
        dfTemp = bukaSatuFile(file)
    
        if df is None:
            df = dfTemp
        else:
            df = pd.concat([df,dfTemp], ignore_index=True)
    
    return df


if __name__ == "__main__":
    df = GabungDataFolder("rji", xd)
    print(df.head(10))