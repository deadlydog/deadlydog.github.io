---
title: Tell Microsoft To Fix The Sql Server Management Studio "Connect to Server" Dialog Position
date: 2014-10-17T10:17:39-06:00
permalink: /tell-microsoft-to-fix-the-sql-server-management-studio-connect-to-server-dialog-position/
categories:
  - SQL
tags:
  - bug
  - connect
  - Connect to Server
  - dialog
  - Management
  - monitor
  - monitors
  - multiple
  - popup
  - Server
  - SQL
  - Sql Server Management Studio
  - SSMS
  - Studio
  - window
---

If you use Sql Server Management Studio (SSMS) with multiple monitors, you likely run into the issue where the "Connect to Server" dialog window opens up either half or completely off the screen when SSMS is opened on a monitor that is not the primary one (see screenshot below).

Several bugs have been reported for this, and apparently MS thinks it is not really an issue since they have decided to close all of the bugs related to it as "Won’t Fix". Here’s a quote:

> We took a look at this bug and triaged it against several others and unfortunately, it did not meet the bar to be fixed and we are closing it as 'won't fix'.

Why they admit that it is a problem and close it as "Won’t Fix" instead of just leaving it open with a low priority is beyond me.

What’s even more surprising though is that these issues currently have less than 10 upvotes! Let’s fix that. Like many people, I use SSMS daily, and this is easily my biggest beef with it, especially since the fix is so simple (literally 3 clicks on a Windows Forms or WPF app).

Please go to the following 3 Connect bugs __and up-vote them__ so MS reconsiders fixing this.

1. [https://connect.microsoft.com/SQLServer/feedback/details/755689/sql-server-management-studio-connect-to-server-popup-dialog](https://connect.microsoft.com/SQLServer/feedback/details/755689/sql-server-management-studio-connect-to-server-popup-dialog "https://connect.microsoft.com/SQLServer/feedback/details/755689/sql-server-management-studio-connect-to-server-popup-dialog")

1. [https://connect.microsoft.com/SQLServer/feedback/details/724909/connection-dialog-appears-off-screen](https://connect.microsoft.com/SQLServer/feedback/details/724909/connection-dialog-appears-off-screen "https://connect.microsoft.com/SQLServer/feedback/details/724909/connection-dialog-appears-off-screen")

1. [https://connect.microsoft.com/SQLServer/feedback/details/389165/sql-server-management-studio-gets-confused-dealing-with-multiple-displays](https://connect.microsoft.com/SQLServer/feedback/details/389165/sql-server-management-studio-gets-confused-dealing-with-multiple-displays "https://connect.microsoft.com/SQLServer/feedback/details/389165/sql-server-management-studio-gets-confused-dealing-with-multiple-displays")

Here’s a screenshot of the problem. Here my secondary monitors are above my primary one, but the same problem occurs even if all monitors are horizontal to one another.

![Sql Management Studio Multi-Monitor Bug](/assets/Posts/2014/10/Sql-Management-Studio-Multi-Monitor-Bug.png)
