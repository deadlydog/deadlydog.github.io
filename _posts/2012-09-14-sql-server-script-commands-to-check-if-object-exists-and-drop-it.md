---
id: 99
title: SQL Server script commands to check if object exists and drop it
date: 2012-09-14T14:03:00-06:00
author: deadlydog
guid: https://deadlydog.wordpress.com/?p=99
permalink: /sql-server-script-commands-to-check-if-object-exists-and-drop-it/
jabber_published:
  - "1353355440"
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
Over the past couple years I’ve been keeping track of common SQL Server script commands that I use so I don’t have to constantly Google them.&#160; Most of them are how to check if a SQL Server object exists before dropping it.&#160; I thought others might find these useful to have them all in one place, so here you go:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:8a021f85-751a-472b-8bac-979cc265f448" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: sql; pad-line-numbers: true; title: ; notranslate" title="">
--===============================
-- Create a new table and add keys and constraints
--===============================
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'TableName' AND TABLE_SCHEMA='dbo')
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
	ALTER TABLE [dbo].[TableName] WITH CHECK ADD CONSTRAINT [CH_Name] CHECK (([ColumnName] &gt;= 0.0000))
END

--===============================
-- Add a new column to an existing table
--===============================
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='dbo'
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
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'TableName' AND TABLE_SCHEMA='dbo')
BEGIN
	EXEC('DROP TABLE [dbo].[TableName]')
END

--===============================
-- Drop a view
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_NAME = 'ViewName' AND TABLE_SCHEMA='dbo')
BEGIN
	EXEC('DROP VIEW [dbo].[ViewName]')
END

--===============================
-- Drop a column
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='dbo'
	AND TABLE_NAME = 'TableName' AND COLUMN_NAME = 'ColumnName')
BEGIN

	-- If the column has an extended property, drop it first.
	IF EXISTS (SELECT * FROM sys.fn_listExtendedProperty(N'MS_Description', N'SCHEMA', N'dbo', N'Table',
				N'TableName', N'COLUMN', N'ColumnName'))
	BEGIN
		EXEC sys.sp_dropextendedproperty @name=N'MS_Description',
			@level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',
			@level1name = N'TableName', @level2type=N'COLUMN',
			@level2name = N'ColumnName'
	END

	EXEC('ALTER TABLE [dbo].[TableName] DROP COLUMN [ColumnName]')
END

--===============================
-- Drop Primary key constraint
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='PRIMARY KEY' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'PK_Name')
BEGIN
	EXEC('ALTER TABLE [dbo].[TableName] DROP CONSTRAINT [PK_Name]')
END

--===============================
-- Drop Foreign key constraint
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='FOREIGN KEY' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'FK_Name')
BEGIN
	EXEC('ALTER TABLE [dbo].[TableName] DROP CONSTRAINT [FK_Name]')
END

--===============================
-- Drop Unique key constraint
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='UNIQUE' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'UNI_Name')
BEGIN
	EXEC('ALTER TABLE [dbo].[TableNames] DROP CONSTRAINT [UNI_Name]')
END

--===============================
-- Drop Check constraint
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='CHECK' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = 'CH_Name')
BEGIN
	EXEC('ALTER TABLE [dbo].[TableName] DROP CONSTRAINT [CH_Name]')
END

--===============================
-- Drop a column's Default value constraint
--===============================
DECLARE @ConstraintName VARCHAR(100)
SET @ConstraintName = (SELECT TOP 1 s.name FROM sys.sysobjects s JOIN sys.syscolumns c ON s.parent_obj=c.id
						WHERE s.xtype='d' AND c.cdefault=s.id
						AND parent_obj = OBJECT_ID('TableName') AND c.name ='ColumnName')

IF @ConstraintName IS NOT NULL
BEGIN
	EXEC('ALTER TABLE [dbo].[TableName] DROP CONSTRAINT ' + @ConstraintName)
END

--===============================
-- Example of how to drop dynamically named Unique constraint
--===============================
DECLARE @ConstraintName VARCHAR(100)
SET @ConstraintName = (SELECT TOP 1 CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
						WHERE CONSTRAINT_TYPE='UNIQUE' AND TABLE_SCHEMA='dbo'
						AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME LIKE 'FirstPartOfConstraintName%')

IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE='UNIQUE' AND TABLE_SCHEMA='dbo'
		AND TABLE_NAME = 'TableName' AND CONSTRAINT_NAME = @ConstraintName)
BEGIN
	EXEC('ALTER TABLE [dbo].[TableName] DROP CONSTRAINT ' + @ConstraintName)
END

--===============================
-- Check for and drop a temp table
--===============================
IF OBJECT_ID('tempdb..#TableName') IS NOT NULL DROP TABLE #TableName

--===============================
-- Drop a stored procedure
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE='PROCEDURE' AND ROUTINE_SCHEMA='dbo' AND
		ROUTINE_NAME = 'StoredProcedureName')
BEGIN
	EXEC('DROP PROCEDURE [dbo].[StoredProcedureName]')
END

--===============================
-- Drop a UDF
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE='FUNCTION' AND ROUTINE_SCHEMA='dbo' AND
		ROUTINE_NAME = 'UDFName')
BEGIN
	EXEC('DROP FUNCTION [dbo].[UDFName]')
END

--===============================
-- Drop an Index
--===============================
IF EXISTS (SELECT * FROM SYS.INDEXES WHERE name = 'IndexName')
BEGIN
	EXEC('DROP INDEX TableName.IndexName')
END

--===============================
-- Drop a Schema
--===============================
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'SchemaName')
BEGIN
	EXEC('DROP SCHEMA SchemaName')
END

--===============================
-- Drop a Trigger
--===============================
IF EXISTS (SELECT * FROM SYS.TRIGGERS WHERE NAME = 'TriggerName')
BEGIN
	EXEC('DROP TRIGGER TriggerName')
END

</pre>
</div>

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:5f0777e4-9ea5-4606-8ccf-b706b9810e74" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2013/09/Common-SQL-Changescript-Code.zip" target="_blank">Download Code Snippet</a>
  </p>
</div>

You may have noticed that I wrap the actual DROP statements in an EXEC statement.&#160; This is because if you run the script once and it drops the schema object, if you try to run the script a second time SQL may complain that the schema object does not exist, and won’t allow you to run the script; sort of like failing a compile-time check.&#160; This seems stupid though since we check if the object exists before dropping it, but the “SQL compiler” doesn’t know that.&#160; So to avoid this we convert the drop statement to a string and put it in an EXEC, so that it is not evaluated until “run-time”, and since the IF EXISTS checks prevent that code from being executed if the schema object does not exist, everything works fine.

Happy coding!
