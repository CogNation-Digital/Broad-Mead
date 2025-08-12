# Database Schema Changes for First Name and Last Name Separation

## 📋 **Overview**
This guide will help you modify the database structure and code to:
- Split the `Name` column into `first_name` and `last_name` columns
- Update all code to use only the first name in emails for more professional communication
- Maintain backward compatibility during transition

---

## 🗄️ **Database Changes Required**

### **Step 1: Update Candidates Table Structure**

Run these SQL commands on both your localhost and live databases:

```sql
-- For _candidates table
-- Add new columns
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

-- Optional: Remove the old Name column after confirming data is correct
-- ALTER TABLE _candidates DROP COLUMN Name;
```

### **Step 2: Update Clients Table Structure (if exists)**

```sql
-- For clients table (if you have one)
-- Check if clients table exists and has similar structure
ALTER TABLE clients 
ADD COLUMN first_name VARCHAR(100) AFTER Name,
ADD COLUMN last_name VARCHAR(100) AFTER first_name;

UPDATE clients 
SET 
    first_name = TRIM(SUBSTRING_INDEX(Name, ' ', 1)),
    last_name = TRIM(CASE 
        WHEN LOCATE(' ', Name) > 0 
        THEN SUBSTRING(Name, LOCATE(' ', Name) + 1) 
        ELSE '' 
    END)
WHERE Name IS NOT NULL AND Name != '';
```

---

## 💻 **Code Changes Required**

I'll provide the updated code files below with all necessary changes:

### **Changes Summary:**
1. **Database Queries**: Update all SELECT statements to use `first_name` and `last_name`
2. **Email Personalization**: Use only `first_name` in emails for professional tone
3. **Display Logic**: Show full name in admin interfaces but use first name in communications
4. **Backward Compatibility**: Support both old and new column structures during transition

---

## 🚀 **Benefits of This Change**

✅ **More Professional Emails**: "Dear John" instead of "Dear John Smith"
✅ **Better Personalization**: First name creates more personal connection
✅ **Data Organization**: Better structured data for future enhancements
✅ **Flexibility**: Can easily switch between formal and informal addressing
✅ **Industry Standard**: Follows common CRM practices

---

## ⚠️ **Important Notes**

1. **Backup First**: Always backup your database before making structural changes
2. **Test Environment**: Test these changes on localhost before applying to live server
3. **Data Validation**: Verify that name splitting worked correctly for all records
4. **Gradual Transition**: Keep both old and new columns temporarily for safety

Would you like me to proceed with creating the updated code files?
