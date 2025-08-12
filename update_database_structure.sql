-- ==============================================
-- Database Schema Update: Split Name into First Name and Last Name
-- ==============================================

-- Use this script to update your database structure
-- Run on both localhost and live server databases

-- ==============================================
-- STEP 1: BACKUP YOUR DATABASE FIRST!
-- ==============================================

-- ==============================================
-- STEP 2: Update _candidates table
-- ==============================================

-- Add new columns for first_name and last_name
ALTER TABLE _candidates 
ADD COLUMN first_name VARCHAR(100) AFTER Name,
ADD COLUMN last_name VARCHAR(100) AFTER first_name;

-- Split existing Name data into first_name and last_name
UPDATE _candidates 
SET 
    first_name = TRIM(SUBSTRING_INDEX(Name, ' ', 1)),
    last_name = TRIM(CASE 
        WHEN LOCATE(' ', Name) > 0 
        THEN SUBSTRING(Name, LOCATE(' ', Name) + 1) 
        ELSE '' 
    END)
WHERE Name IS NOT NULL AND Name != '';

-- ==============================================
-- STEP 3: Update clients table (if exists)
-- ==============================================

-- Check if clients table exists first
-- SELECT * FROM information_schema.tables WHERE table_name = 'clients';

-- If clients table exists, run these:
-- ALTER TABLE clients 
-- ADD COLUMN first_name VARCHAR(100) AFTER Name,
-- ADD COLUMN last_name VARCHAR(100) AFTER first_name;

-- UPDATE clients 
-- SET 
--     first_name = TRIM(SUBSTRING_INDEX(Name, ' ', 1)),
--     last_name = TRIM(CASE 
--         WHEN LOCATE(' ', Name) > 0 
--         THEN SUBSTRING(Name, LOCATE(' ', Name) + 1) 
--         ELSE '' 
--     END)
-- WHERE Name IS NOT NULL AND Name != '';

-- ==============================================
-- STEP 4: Verify the changes
-- ==============================================

-- Check the data to make sure splitting worked correctly
SELECT 
    id,
    Name as original_name,
    first_name,
    last_name,
    CONCAT(first_name, ' ', last_name) as reconstructed_name
FROM _candidates 
WHERE Name IS NOT NULL 
LIMIT 10;

-- ==============================================
-- STEP 5: Optional - Remove old Name column 
-- (Only after confirming everything works!)
-- ==============================================

-- IMPORTANT: Only run this after thoroughly testing the new structure
-- ALTER TABLE _candidates DROP COLUMN Name;

-- ==============================================
-- STEP 6: Add indexes for better performance
-- ==============================================

-- Add indexes on the new name columns for better search performance
ALTER TABLE _candidates ADD INDEX idx_first_name (first_name);
ALTER TABLE _candidates ADD INDEX idx_last_name (last_name);

-- ==============================================
-- Common Issues and Solutions
-- ==============================================

/*
1. If some names don't split correctly:
   - Check for names with multiple spaces
   - Look for names with prefixes (Mr., Mrs., Dr.)
   - Consider names with suffixes (Jr., Sr., III)

2. To handle special cases, you might need custom updates:
   
   -- For names with prefixes
   UPDATE _candidates 
   SET first_name = TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(Name, ' ', 2), ' ', -1))
   WHERE Name LIKE 'Mr.%' OR Name LIKE 'Mrs.%' OR Name LIKE 'Dr.%';

3. To fix empty last names:
   UPDATE _candidates 
   SET last_name = first_name, first_name = ''
   WHERE last_name = '' AND first_name != '';
*/

-- ==============================================
-- Final Verification Queries
-- ==============================================

-- Count records with empty first names
SELECT COUNT(*) as empty_first_names FROM _candidates WHERE first_name = '' OR first_name IS NULL;

-- Count records with empty last names  
SELECT COUNT(*) as empty_last_names FROM _candidates WHERE last_name = '' OR last_name IS NULL;

-- Show names that might need manual review
SELECT id, Name, first_name, last_name 
FROM _candidates 
WHERE (first_name = '' OR first_name IS NULL) 
   OR Name LIKE '%,%' 
   OR Name LIKE 'Dr.%' 
   OR Name LIKE 'Mr.%' 
   OR Name LIKE 'Mrs.%'
LIMIT 20;
