---
title: "Fixing SQL72023 Containment error when deploying SQL SSDT database project from a dacpac"
permalink: /Fixing-SQL72023-Containment-error-when-deploying-SQL-SSDT-database-project-from-a-dacpac/
#date: 2099-01-17T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - SQL
  - Database
  - Deploy
tags:
  - SQL
  - Database
  - Deploy
  - SSDT
---

We setup a new Visual Studio Database Project using SSDT (SQL Server Data Tools) and built it, which generates a .dapac file that can be used for deployments. While performing an automated deploy using the .dapac file, we encountered the following error:

```text
*** Could not deploy package.
Warning SQL72023: The database containment option has been changed to None.  This may result in deployment failure if the state of the database is not compliant with this containment level.
Error SQL72014: .Net SqlClient Data Provider: Msg 5061, Level 16, State 1, Line 5 ALTER DATABASE failed because a lock could not be placed on database 'Example_SqlDatabase'. Try again later.
Error SQL72045: Script execution error.  The executed script:
IF EXISTS (SELECT 1
           FROM   [master].[dbo].[sysdatabases]
           WHERE  [name] = N'$(DatabaseName)')
Time elapsed 00:00:32.67
    BEGIN
        ALTER DATABASE [$(DatabaseName)]
            SET CONTAINMENT = NONE
            WITH ROLLBACK IMMEDIATE;
    END

Error SQL72014: .Net SqlClient Data Provider: Msg 5069, Level 16, State 1, Line 5 ALTER DATABASE statement failed.
Error SQL72045: Script execution error.  The executed script:
IF EXISTS (SELECT 1
           FROM   [master].[dbo].[sysdatabases]
           WHERE  [name] = N'$(DatabaseName)')
    BEGIN
        ALTER DATABASE [$(DatabaseName)]
            SET CONTAINMENT = NONE
            WITH ROLLBACK IMMEDIATE;
    END
```

On the same morning, a different team ran into this same issue with one of their databases, but with a slightly different error message:

```text
*** Could not deploy package.
Warning SQL72023: The database containment option has been changed to None.  This may result in deployment failure if the state of the database is not compliant with this containment level.
Error SQL72014: .Net SqlClient Data Provider: Msg 12809, Level 16, State 1, Line 5 You must remove all users with password before setting the containment property to NONE.
```

The root cause of both is the same; It seems the deployment was trying to set the database `Containment` mode to `None`, which requires additional permissions and isn't allowed when using SQL Availability Groups.

The fix for this is to modify the .sqlproj file to change it's default `Containment` mode.
You can do this from Visual Studio by right-clicking on the project in Solution Explorer, choosing `Properties`, in the `Project Settings` tab click the `Database Settings...` button, then in the `Miscellaneous` tab change the value of the `Containment` drop-down to `None` or `Partial` as needed.

![How to change the Containment mode from Visual Studio](/assets/Posts/2020-06-15-Fixing-SQL72023-Containment-error-when-deploying-SQL-SSDT-database-project-from-a-dacpac/SetVisualStudioDatabaseProjectContainmentMode.png)

Changing that value from `None` to `Partial` ends up adding the following element to the database's .sqlproj file:

```xml
<Containment>Partial</Containment>
```

One of the teams reported that they also had to go into their database project settings and change the `Target platform` from `SQL Server 2008` to `SQL Server 2016` before the above change would work.

If you encounter this problem as well, hopefully this helps get you going.

Happy coding!
