# MySQL Leads Query Optimization

## 1. Table Creation

The `table-creation.sql` file contains the `CREATE TABLE` statement for the `leads` table, preserving the original column definitions and data types.

## 2. Database Optimization

The `db-optimized.sql` file contains the optimized database query and indexing strategy for retrieving leads efficiently.

The optimization focuses on:
- Composite indexing on frequently filtered columns.
- Efficient filtering using `account_id` and `deleted_at`.
- Retrieving the latest records using `ORDER BY id DESC`.
- Maintaining the original selected columns and output structure.