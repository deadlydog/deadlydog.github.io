---
title: "Run PowerShell as another user"
permalink: /Run-PowerShell-as-another-user/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
categories:
  - PowerShell
tags:
  - PowerShell
---

I was looking for a way to run a PowerShell script as another user in our deployment pipeline.
There are many reasons you might want to do this; for me I needed to run some commands against a database that only supported Integrated Security, not SQL logins.

The trick to running PowerShell on the local machine as a different user is to use `Enter-PSSession` and specify `localhost` for the computer.

If you are running PowerShell interactively, you can have it prompt for the credentials of the user that you want to run as, like this:

```powershell
$credential = Get-Credential

Enter-PSSession -Credential $credential -ComputerName localhost

# Your PowerShell code goes here.
```

In an automated pipeline, you can provide the username and password like this:

```powershell
$username = 'Your.Username'
$password = 'Password in plain text'
$securePassword = ConvertTo-SecureString $password -AsPlainText -Force
$credential = New-Object System.Management.Automation.PSCredential ($username, $securePassword)

Enter-PSSession -Credential $credential -ComputerName localhost

# Your PowerShell code goes here.
```

__NOTE:__ Never store the plain text password in source control.
Instead, it should be injected into the script at runtime by some other mechanism, or pulled from a secrets store like Azure Key Vault.

If you want to run the PowerShell from a different computer, then you can use the `Invoke-Command` cmdlet instead, like this:

```powershell
[scriptblock] $scriptBlock = {
  # Your PowerShell code goes here.
}

Invoke-Command -Credential $credential -ComputerName 'SomeServer.OnYour.Domain' -ScriptBlock $scriptBlock
```

https://superuser.com/questions/1619630/run-powershell-script-as-a-different-user-and-elevated

https://stackoverflow.com/questions/28989750/running-powershell-as-another-user-and-launching-a-script
