-- Step 2: Fix schema for tables.table_number
-- Converts table_number from INT to VARCHAR to support values like 'T1-01' and 'OUT-03'.

ALTER TABLE tables
    MODIFY COLUMN table_number VARCHAR(50) NOT NULL;

-- Step 3: Enforce non-negative price for menu_items
-- Normalize existing invalid data first, then add DB-level non-negative constraint.

UPDATE menu_items
SET price = 0
WHERE price < 0;

ALTER TABLE menu_items
    MODIFY COLUMN price DECIMAL(10, 2) UNSIGNED NOT NULL COMMENT 'Item price (must be >= 0)';
