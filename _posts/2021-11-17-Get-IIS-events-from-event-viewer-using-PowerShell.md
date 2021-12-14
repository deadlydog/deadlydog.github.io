---
title: "Get IIS events from Event Viewer using PowerShell"
permalink: /Get-IIS-events-from-Event-Viewer-using-PowerShell/
#date: 2099-01-15T00:00:00-06:00
last_modified_at: 2021-12-13T00:00:00-06:00
comments_locked: false
categories:
  - IIS
  - PowerShell
tags:
  - IIS
  - PowerShell
---

Sometimes services hosted in IIS don't behave as we expect and we need to dig into what's happening.
If the problem lies in your application's code, you may need to rely on your application's logging.
You can also [check the IIS logs](https://stackify.com/beyond-iis-logs-find-failed-iis-asp-net-requests/) to find requests and their response codes.
Sometimes however, the problem lies underneath your application, in the server or IIS infrastructure or configuration.
Luckily, IIS records all sorts of events to the Windows Event Viewer to help us troubleshoot these types of issues.

Common types of IIS problem events recorded to the Event Viewer include:

- Out of memory exceptions.
- Application pool restarts.
- Unable to load required modules/dlls.

There's [plenty of](https://www.howtogeek.com/123646/htg-explains-what-the-windows-event-viewer-is-and-how-you-can-use-it/) [other articles](https://www.dummies.com/computers/operating-systems/windows-10/how-to-use-event-viewer-in-windows-10/) describing how to use the Windows Event Viewer GUI, [filter in it](https://www.papertrail.com/solution/tips/windows-event-log-filtering-techniques/), and [query it using PowerShell](https://evotec.xyz/powershell-everything-you-wanted-to-know-about-event-logs/), so I won't cover that here.

__Side note:__ In this post I will be using [the Get-EventLog cmdlet](https://docs.microsoft.com/en-us/powershell/module/microsoft.powershell.management/get-eventlog?view=powershell-5.1).
The MS docs say that [the Get-WinEvent cmdlet](https://docs.microsoft.com/en-us/powershell/module/microsoft.powershell.diagnostics/get-winevent?view=powershell-7.2) is replacing it, but I find Get-EventLog much easier to work with and more intuitive, and the docs say it will be kept around for backward compatibility.

## Getting IIS events using PowerShell

Below are some PowerShell commands which you can use to pull out just the IIS related events.
Depending on the type of event and it's source, IIS may store it in different Log sources (Application or System), so we want to query them both.

```powershell
Get-EventLog -LogName Application -Source *W3SVC* -Newest 10
```

```powershell
Get-EventLog -LogName System -Source WAS -Newest 10
```

If you want to ignore Informational events and only get Warnings and Errors, you can specify that.

```powershell
Get-EventLog -LogName System -Source WAS -Newest 10 -EntryType Warning,Error
```

![Get logs as table](/assets/Posts/2021-11-17-Get-IIS-events-from-event-viewer-using-PowerShell/GetEventLogAsTable.png)

You may notice that the Message column gets truncated.
To view the full details of the events, you can pipe them to `Format-List`.

```powershell
Get-EventLog -LogName System -Source WAS -Newest 10 | Format-List
```

![Get logs as list](/assets/Posts/2021-11-17-Get-IIS-events-from-event-viewer-using-PowerShell/GetEventLogAsList.png)

As with any PowerShell command, you can dump the results to a text file using `> filename.txt`.

```powershell
Get-EventLog -LogName System -Source WAS -Newest 10 | Format-List > C:\temp.txt
```

Or if you want the output dumped to both the console and a text file, use `Tee-Object`:

```powershell
Get-EventLog -LogName System -Source WAS -Newest 10 | Format-List | Tee-Object -FilePath C:\temp.txt
```

Some IIS logging events, such as request timeouts, are also written to the Windows Event Viewer.
If you want to also see those, you can include the ASP.NET source in your query.

```powershell
Get-EventLog -LogName Application -Source *W3SVC*,*ASP* -Newest 10
```

## Querying multiple servers

One of the best things about using PowerShell to query the Windows Event Viewer is that you can do it remotely.
Rather than having to remote desktop onto each server and run the above PowerShell commands (or poke around in the Windows Event Viewer GUI), you can query all of the servers from your local machine.
This assumes you have appropriate permissions and the server's security configuration allows for it.

By default `Get-EventLog` queries the local machine.
To query other computers, simply provide them in the `-ComputerName` parameter:

```powershell
Get-EventLog -ComputerName Server1,Server2,Server3 -LogName Application -Source *W3SVC* -Newest 10
```

Ideally you'd have an observability system in place to collect the events and logs from all of the servers so you could view and query them in a central place.
Unfortunately, many of us don't have the time, money, or resources to put that in place, so this is the next best thing.

## Copy-paste ready commands

Here's a one-liner you can use to query both the Application and System log sources:

```powershell
'Application','System' | ForEach-Object { Get-EventLog -LogName $_ -Source *W3SVC*,WAS -Newest 10 }
```

If you want to include IIS log alerts, such as request timeout events, include ASP.NET events:

```powershell
'Application','System' | ForEach-Object { Get-EventLog -LogName $_ -Source *W3SVC*,WAS,*ASP* -Newest 10 }
```

If you only want to see Warnings and Errors:

```powershell
'Application','System' | ForEach-Object { Get-EventLog -LogName $_ -Source *W3SVC*,WAS,*ASP* -Newest 10 -EntryType Warning,Error }
```

If you want to see the full details of the events:

```powershell
'Application','System' | ForEach-Object { Get-EventLog -LogName $_ -Source *W3SVC*,WAS,*ASP* -Newest 10 -EntryType Warning,Error } | Format-List
```

I hope you find this information useful.

Happy IIS troubleshooting!
