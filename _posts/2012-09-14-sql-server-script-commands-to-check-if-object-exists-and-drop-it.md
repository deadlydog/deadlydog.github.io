---
title: SQL Server script commands to check if object exists and drop it
date: 2012-09-14T14:03:00-06:00
last_modified_at: 2019-06-01T00:00:00-00:00
permalink: /sql-server-script-commands-to-check-if-object-exists-and-drop-it/
categories:
  - SQL
tags:
  - Check
  - Drop
  - If Exists
  - Schema
  - SQL
  - SQL Server
---

Over the past couple years I’ve been keeping track of common SQL Server script commands that I use so I don’t have to constantly Google them. Most of them are how to check if a SQL Server object exists before dropping it. I thought others might find these useful to have them all in one place, so here you go:

```sql
--===============================
-- Create a new table and add keys and constraints
--===============================
IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'TableName' AND TABLE_SCHEMA='dbo')
BEGIN
	CREATE TABLE [dbo].[TableName]
	(
		[ColumnName1] INT NOT NULL, -- To have a field auto-increment add IDENTITY(1,1)
		[ColumnName2] INT NULL,
		[ColumnName3] VARCHAR(30) NOT NULL DEFAULT('')
	)

	-- Add the table's primary key
	ALTER TABLE [dbo].[TableName] ADD CONSTRAINT [PK_TableName] PRIMARY KEY NONCLUSTERED
	(
		[ColumnName1],
		[ColumnName2]
	)

	-- Add a foreign key constraint
	ALTER TABLE [dbo].[TableName] WITH CHECK ADD CONSTRAINT [FK_Name] FOREIGN KEY
	(
		[ColumnName1],
		[ColumnName2]
	)
	REFERENCES [dbo].[Table2Name]
	(
		[OtherColumnName1],
		[OtherColumnName2]
	)

	-- Add indexes on columns that are often used for retrieval
	CREATE INDEX IN_ColumnNames ON [dbo].[TableName]
	(
		[ColumnName2],
		[ColumnName3]
	)

	-- Add a check constraint
	ALTER TABLE [dbo].[TableName] WITH CHECK ADD CONSTRAINT [CH_Name] CHECK (([ColumnName] >= 0.0000))
END

--===============================
-- Add a new column to an existing table
--===============================
IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='dbo'
	AND TABLE_NAME = 'TableName' AND COLUMN_NAME = 'ColumnName')
BEGIN
	ALTER TABLE [dbo].[TableName] ADD [ColumnName] INT NOT NULL DEFAULT(0)

	-- Add a description extended property to the column to specify what its purpose is.
	EXEC sys.sp_addextendedproperty @name=N'MS_Description',
		@value = N'Add column comments here, describing what this column is for.' ,
		@level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',
		@level1name = N'TableName', @level2type=N'COLUMN',
		@level2name = N'ColumnName'
END

--===============================
-- Drop a table
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'TableName' AND TABLE_SCHEMA='dbo')
BEGIN
	EXEC('DROP TABLE [TableName]')
END

--===============================
-- Drop a view
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_NAME = 'ViewName' AND TABLE_SCHEMA='dbo')
BEGIN
	EXEC('DROP VIEW [ViewName]')
END

--===============================
-- Drop a column
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='dbo'
	AND TABLE_NAME = 'TableName' AND COLUMN_NAME = 'ColumnName')
BEGIN

	-- If the column has an extended property, drop it first.
	IF EXISTS (SELECT 1 FROM sys.fn_listExtendedProperty(N'MS_Description', N'SCHEMA', N'dbo', N'Table',
				N'TableName', N'COLUMN', N'ColumnName'))
	BEGIN
		EXEC sys.sp_dropextendedproperty @name=N'MS_Description',
			@level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',
			@level1name = N'TableName', @level2type=N'COLUMN',
			@level2name = N'ColumnName'
	END

	EXEC('ALTER TABLE [TableName] DROP COLUMN [ColumnName]')
END

--===============================
-- Drop Primary key constraint
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='PRIMARY KEY' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'PK_Name')
BEGIN
	EXEC('ALTER TABLE [TableName] DROP CONSTRAINT [PK_Name]')
END

--===============================
-- Drop Foreign key constraint
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='FOREIGN KEY' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'FK_Name')
BEGIN
	EXEC('ALTER TABLE [TableName] DROP CONSTRAINT [FK_Name]')
END

--===============================
-- Drop Unique key constraint
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='UNIQUE' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'UNI_Name')
BEGIN
	EXEC('ALTER TABLE [TableNames] DROP CONSTRAINT [UNI_Name]')
END

--===============================
-- Drop Check constraint
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='CHECK' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'CH_Name')
BEGIN
	EXEC('ALTER TABLE [TableName] DROP CONSTRAINT [CH_Name]')
END

--===============================
-- Drop a column's Default value constraint
--===============================
DECLARE @ConstraintName VARCHAR(100)
SET @ConstraintName = (SELECT TOP 1 s.name FROM sys.sysobjects s JOIN sys.syscolumns c ON s.parent_obj=c.id
						WHERE s.xtype='d' AND c.cdefault=s.ID
						AND parent_obj = OBJECT_ID('TableName') AND c.name ='ColumnName')

IF @ConstraintName IS NOT NULL
BEGIN
	EXEC('ALTER TABLE [TableName] DROP CONSTRAINT ' + @ConstraintName)
END

--===============================
-- Example of how to drop dynamically named Unique constraint
--===============================
DECLARE @ConstraintName VARCHAR(100)
SET @ConstraintName = (SELECT TOP 1 CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
						WHERE CONSTRAINT_TYPE='UNIQUE' AND TABLE_SCHEMA='dbo'
						AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME LIKE 'FirstPartOfConstraintName%')

IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='UNIQUE' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = @ConstraintName)
BEGIN
	EXEC('ALTER TABLE [TableName] DROP CONSTRAINT ' + @ConstraintName)
END

--===============================
-- Check for and drop a temp table
--===============================
IF OBJECT_ID('tempdb..#TableName') IS NOT NULL DROP TABLE #TableName

--===============================
-- Drop a stored procedure
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE='PROCEDURE' AND ROUTINE_SCHEMA='dbo' AND
		ROUTINE_NAME = 'StoredProcedureName')
BEGIN
	EXEC('DROP PROCEDURE [dbo].[StoredProcedureName]')
END

--===============================
-- Drop a UDF
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE='FUNCTION' AND ROUTINE_SCHEMA='dbo' AND
		ROUTINE_NAME = 'UDFName')
BEGIN
	EXEC('DROP FUNCTION [UDFName]')
END

--===============================
-- Drop an Index
--===============================
IF EXISTS (SELECT 1 FROM SYS.INDEXES WHERE name = 'IndexName')
BEGIN
	EXEC('DROP INDEX [IndexName] ON [TableName]')
END

--===============================
-- Drop a Schema
--===============================
IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'SchemaName')
BEGIN
	EXEC('DROP SCHEMA [SchemaName]')
END

--===============================
-- Drop a Trigger
--===============================
IF EXISTS (SELECT 1 FROM SYS.TRIGGERS WHERE name = 'TriggerName')
BEGIN
	EXEC('DROP TRIGGER [TriggerName]')
END

--===============================
-- Drop a custom Type
--===============================
DECLARE @SchemaId INT = (SELECT schema_id FROM sys.schemas WHERE [name] = 'dbo')
IF EXISTS (SELECT 1 FROM SYS.TYPES WHERE schema_id = @SchemaId AND name = 'TypeName')
BEGIN
	EXEC('DROP TYPE [dbo].[TypeName]')
END

--===============================
-- Drop a Service Broker Message Type
--===============================
IF EXISTS (SELECT 1 FROM SYS.SERVICE_MESSAGE_TYPES WHERE name = 'MessageTypeName')
BEGIN
	EXEC('DROP TYPE [TypeName]')
END
```

[Download Code Snippet](/assets/Posts/2013/09/Common-SQL-Changescript-Code.zip)

You may have noticed that I wrap the actual `DROP` statements in an `EXEC` statement. This is because if you run the script once and it drops the schema object, if you try to run the script a second time SQL may complain that the schema object does not exist, and won’t allow you to run the script; sort of like failing a compile-time check. This seems stupid though since we check if the object exists before dropping it, but the "SQL compiler" doesn’t know that. So to avoid this we convert the drop statement to a string and put it in an `EXEC`, so that it is not evaluated until "run-time", and since the `IF EXISTS` checks prevent that code from being executed if the schema object does not exist, everything works fine.

Happy coding!
