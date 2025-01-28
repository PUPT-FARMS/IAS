from sqlalchemy import create_engine
import os

# Connect to source database
source_db_url = f"mysql+pymysql://{os.getenv('u687103837_farm_db')}:{os.getenv('Hallu123!')}@{os.getenv('127.0.0.1')}:{os.getenv('3306')}/{os.getenv('u687103837_farm_db')}"
source_engine = create_engine(source_db_url)

# Connect to local database
local_db_url = f"mysql+pymysql://{os.getenv('root')}:{os.getenv('none')}@{os.getenv('mysql_db')}:{os.getenv('3306')}/{os.getenv('etl')}"
local_engine = create_engine(local_db_url)

# Test connection
try:
    with source_engine.connect() as connection:
        print("Successfully connected to the source database!")
    with local_engine.connect() as connection:
        print("Successfully connected to the local database!")
except Exception as e:
    print(f"Error: {e}")
