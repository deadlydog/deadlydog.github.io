---
title: "Updating from System.Data.SqlClient to Microsoft.Data.SqlClient"
permalink: /Updating-from-System-Data-SqlClient-to-Microsoft-Data-SqlClient/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - .NET
  - C#
  - SQL
tags:
  - .NET
  - C#
  - SQL
---

Over the past couple days I've been updating a .NET 8 solution from using `System.Data.SqlClient` to `Microsoft.Data.SqlClient`.
The reason for this, besides the fact that [System.Data.SqlClient is now deprecated](https://techcommunity.microsoft.com/t5/sql-server-blog/announcement-system-data-sqlclient-package-is-now-deprecated/ba-p/4227205), is that I wanted to see SQL performance counter metrics, such as how many active SQL connections my app was using.
`System.Data.SqlClient` supports performance counters on .NET Framework apps, but not .NET Core apps.
To [get SqlClient metrics in .NET Core apps](https://learn.microsoft.com/en-us/sql/connect/ado-net/event-counters), you need to use `Microsoft.Data.SqlClient`.

## TL;DR

The SqlClient GitHub repo has a [migration cheat sheet](https://github.com/dotnet/SqlClient/blob/main/porting-cheat-sheet.md) for everything that you will need to change when migrating from `System.Data.SqlClient` to `Microsoft.Data.SqlClient`.

The team also plans to automate much of the migration process with the [.NET Upgrade Assistant](https://dotnet.microsoft.com/en-us/platform/upgrade-assistant) in the future, so keep an eye out for that.

## My journey

[Microsoft.Data.SqlClient was introduced in 2019](https://devblogs.microsoft.com/dotnet/introducing-the-new-microsoftdatasqlclient/), and skimming [the GitHub migration guide issue](https://github.com/dotnet/SqlClient/issues/2778) said to simply add the `Microsoft.Data.SqlClient` NuGet package to your projects and update the `using` statements from `System.Data.SqlClient` to `Microsoft.Data.SqlClient`.
That seemed simple enough.

So I did that, and everything compiled fine.
Nice! 💪

However, I then ran my integration tests and saw a lot of failures 😭.

> NOTE: Be weary of runtime issues after migrating.
> Be sure to test your application.

The first error I encountered was an issue connecting to the SQL database:

```text
A connection was successfully established with the server, but then an error occurred during the login process.
(provider: SSL Provider, error: 0 - The certificate chain was issued by an authority that is not trusted.)
```

`Microsoft.Data.SqlClient` is meant to be a backward compatible improvement over `System.Data.SqlClient`, and that includes being more secure.
All connections are now encrypted by default, and the server's certificate must be trusted.
If you are connecting to a SQL Server that is using a self-signed certificate, you will need to add the certificate to the trusted root certificate store on the machine running the application, or (not recommended) adjust the connection string to either use `Encrypt=False` or `TrustServerCertificate=True`.

The next error I ran into was:

```text
Failed to convert parameter value from a SqlDataRecord[] to a IEnumerable`1.
unhandled_exception_InvalidCastException
```

After a few hours of unravelling the app and debugging it, I finally came across [this comment on a GitHub issue](https://github.com/dotnet/SqlClient/issues/323#issuecomment-556775371) mentioning that in addition to updating the `using System.Data.SqlClient;` statements, I also needed to update the `using Microsoft.SqlServer.Server;` statements to `using Microsoft.Data.SqlClient.Server;`.
I also found [this Stack Overflow answer](https://stackoverflow.com/a/61713249/602585) that mentioned the same thing.

The final problem I ran into was some of the unit tests compared the SQL connection string to ensure it had all of the expected properties and values.
It seems that the `SqlConnectionStringBuilder.ConnectionString` property in `Microsoft.Data.SqlClient.` changed the formatting to use spaces in the connection string properties, so `ApplicationIntent` becomes `Application Intent`, and `MultiSubnetFailover` becomes `Multi Subnet Failover`.
It's a very minor change that doesn't affect the functionality of the connection string, but I thought I'd mention it.

After making these changes, everything worked as expected.

Later I noticed I had overlooked [this migration cheat sheet](https://github.com/dotnet/SqlClient/blob/main/porting-cheat-sheet.md) that was linked to from the deprecation announcement page 🤦‍♂️.
It includes the first two changes I mentioned above, as well as a few others that I didn't run into.
The deprecation announcement page also mentions they plan to update [the .NET Upgrade Assistant](https://dotnet.microsoft.com/en-us/platform/upgrade-assistant) to help with this migration in the future, so by the time you read this, you may be able to use that tool.

If you need to migrate your apps, I hope you find this useful.

Happy coding!
