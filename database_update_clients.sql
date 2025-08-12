-- Database update script to add first_name and last_name columns to clients tables
-- This script works for both localhost and live server environments

-- Update broadmead database (clients table)
USE broadmead;

-- Add first_name and last_name columns to clients table if they don't exist
SET @sql = CONCAT('ALTER TABLE clients 
    ADD COLUMN IF NOT EXISTS first_name VARCHAR(100) NULL AFTER Name,
    ADD COLUMN IF NOT EXISTS last_name VARCHAR(100) NULL AFTER first_name');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update broadmead_v3 database (_clients table)
USE broadmead_v3;

-- Add first_name and last_name columns to _clients table if they don't exist
SET @sql = CONCAT('ALTER TABLE _clients 
    ADD COLUMN IF NOT EXISTS first_name VARCHAR(100) NULL AFTER Name,
    ADD COLUMN IF NOT EXISTS last_name VARCHAR(100) NULL AFTER first_name');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Populate first_name and last_name columns for existing records in clients table
USE broadmead;
UPDATE clients 
SET 
    first_name = CASE 
        WHEN TRIM(Name) = '' OR Name IS NULL THEN NULL
        ELSE TRIM(SUBSTRING_INDEX(Name, ' ', 1))
    END,
    last_name = CASE 
        WHEN TRIM(Name) = '' OR Name IS NULL THEN NULL
        WHEN TRIM(Name) = TRIM(SUBSTRING_INDEX(Name, ' ', 1)) THEN NULL  -- Single word name
        ELSE TRIM(SUBSTRING_INDEX(Name, ' ', -1))
    END
WHERE (first_name IS NULL OR last_name IS NULL) AND Name IS NOT NULL;

-- Populate first_name and last_name columns for existing records in _clients table
USE broadmead_v3;
UPDATE _clients 
SET 
    first_name = CASE 
        WHEN TRIM(Name) = '' OR Name IS NULL THEN NULL
        ELSE TRIM(SUBSTRING_INDEX(Name, ' ', 1))
    END,
    last_name = CASE 
        WHEN TRIM(Name) = '' OR Name IS NULL THEN NULL
        WHEN TRIM(Name) = TRIM(SUBSTRING_INDEX(Name, ' ', 1)) THEN NULL  -- Single word name
        ELSE TRIM(SUBSTRING_INDEX(Name, ' ', -1))
    END
WHERE (first_name IS NULL OR last_name IS NULL) AND Name IS NOT NULL;

-- Show summary of changes for clients
USE broadmead;
SELECT 
    'broadmead.clients' as table_name,
    COUNT(*) as total_records,
    COUNT(first_name) as records_with_first_name,
    COUNT(last_name) as records_with_last_name,
    COUNT(CASE WHEN first_name IS NOT NULL AND last_name IS NOT NULL THEN 1 END) as complete_name_records
FROM clients;

-- Show summary of changes for _clients  
USE broadmead_v3;
SELECT 
    'broadmead_v3._clients' as table_name,
    COUNT(*) as total_records,
    COUNT(first_name) as records_with_first_name,
    COUNT(last_name) as records_with_last_name,
    COUNT(CASE WHEN first_name IS NOT NULL AND last_name IS NOT NULL THEN 1 END) as complete_name_records
FROM _clients;
