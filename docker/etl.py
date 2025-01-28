import pymysql
pymysql.install_as_MySQLdb()

import os
import pandas as pd
from sqlalchemy import create_engine
from dotenv import load_dotenv
from datetime import datetime
import time
from sqlalchemy.exc import OperationalError

# Wait for the database to be ready
def wait_for_db(engine, max_retries=5, delay=5):
    for attempt in range(max_retries):
        try:
            with engine.connect() as connection:
                print(f"Successfully connected to database on attempt {attempt + 1}")
                return True
        except OperationalError as e:
            if attempt < max_retries - 1:
                print(f"Database connection attempt {attempt + 1} failed. Retrying in {delay} seconds...")
                time.sleep(delay)
            else:
                raise e
    return False

try:
    # Load environment variables
    load_dotenv()

    # Source database credentials (from your website)
    db_connection = os.getenv('mysql')
    db_host = os.getenv('127.0.0.1')
    db_port = int(os.getenv('3306'))
    db_database = os.getenv('u687103837_farm_db')
    db_username = os.getenv('u687103837_farm_db')
    db_password = os.getenv('Hallu123!')

    # Local MySQL database credentials (for storing the transformed data)
    local_db_connection = os.getenv('mysql')
    local_db_host = os.getenv('mysql_db')
    local_db_port = int(os.getenv('3306'))
    local_db_database = os.getenv('etl')
    local_db_username = os.getenv('root')
    local_db_password = os.getenv('none')

    # Source database URL
    db_url = f"{db_connection}+pymysql://{db_username}:{db_password}@{db_host}:{db_port}/{db_database}"
    print(f"Source DB URL: {db_url}")

    # Local database URL
    local_db_url = f"{local_db_connection}+pymysql://{local_db_username}:{local_db_password}@{local_db_host}:{local_db_port}/{local_db_database}"
    print(f"Local DB URL: {local_db_url}")

    # Create engines for source and local databases
    try:
        source_engine = create_engine(db_url)
        local_engine = create_engine(local_db_url)

        # Wait for databases to be ready
        print("Waiting for source database connection...")
        wait_for_db(source_engine)
        print("Waiting for local database connection...")
        wait_for_db(local_engine)
    except Exception as e:
        raise Exception(f"Error creating database engines: {e}")

    # Extract data from source database
    try:
        query = """
        SELECT 
            u.id AS user_id,
            u.username,
            u.email,
            f.folder_name,
            c.file_name
        FROM 
            user_login u
        LEFT JOIN 
            folder_name f ON u.id = f.user_id
        LEFT JOIN 
            courses_files c ON f.id = c.folder_id
        """
        
        data = pd.read_sql(query, source_engine)
        print("Data extracted successfully!")
    except Exception as e:
        raise Exception(f"Error extracting data from source database: {e}")

    # Transform data
    try:
        # You can transform the data as needed
        data['full_name'] = data['username']  # Assuming `username` is the name field here
        data['timestamp'] = datetime.now()  # Add a timestamp column for when the ETL runs

        # Filter necessary columns for loading
        data_filtered = data[['user_id', 'email', 'folder_name', 'file_name', 'full_name', 'timestamp']]
        print("Data transformed successfully!")
    except Exception as e:
        raise Exception(f"Error transforming data: {e}")

    # Load transformed data into local MySQL database
    try:
        data_filtered.to_sql('transformed_files_data', local_engine, if_exists='replace', index=False)
        print("Data loaded successfully into the local MySQL database!")
    except Exception as e:
        raise Exception(f"Error loading data into local MySQL database: {e}")

except Exception as main_error:
    print(f"An error occurred during the ETL process: {main_error}")
