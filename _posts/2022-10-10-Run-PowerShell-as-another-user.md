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
There are many reasons you might want to do this; for me I needed to run some commands against a database that only supported Integrated Security, not SQL logins, and thus my script needed to run as the domain user with appropriate database permissions.

## Run as different user on the local computer

The trick to running PowerShell on the local machine as a different user is to use `Enter-PSSession` ([see the docs](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/enter-pssession?view=powershell-7.2)) or `Invoke-Command` ([see the docs](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/invoke-command?view=powershell-7.2)) and specify `localhost` for the `-ComputerName`.

Use `Enter-PSSession` when running commands interactively, and use `Invoke-Command` to run code in a script.

__NOTE:__ To connect to `localhost` you will need to be running PowerShell as admin.
If you are not running as admin, you will get an `Access Denied` error message.
See the later section for ways to deal with this.

If you are running PowerShell interactively, you can have it prompt for the credentials of the user that you want to run as like this:

```powershell
$credential = Get-Credential # You will be prompted for the username and password here.

Enter-PSSession -ComputerName localhost -Credential $credential

# Run your PowerShell commands here.

Exit-PSSession
```

In a non-interactive automated script, you can provide the username and password like this:

```powershell
$username = 'Your.Username'
$password = 'Password in plain text'
$securePassword = ConvertTo-SecureString $password -AsPlainText -Force
$credential = New-Object System.Management.Automation.PSCredential ($username, $securePassword)

Invoke-Command -ComputerName localhost -Credential $credential -ScriptBlock {
  # Your PowerShell code goes here.
}
```

__NOTE:__ Never store the plain text password in source control.
Instead, it should be injected into the script at runtime by some other mechanism, or pulled from a secrets store like Azure Key Vault.

Aside: You may prefer to define your scriptblock as a variable to help better organize your code, like this:

```powershell
[scriptblock] $scriptBlock = {
  # Your PowerShell code goes here.
}

Invoke-Command -ComputerName 'SomeServer.OnYour.Domain' -Credential $credential -ScriptBlock $scriptBlock
```

## Run as different user on a remote computer

If you want to run the PowerShell commands on a different computer, you can simply replace `localhost` with the remote computer in the `-ComputerName` parameter.
e.g.

```powershell
Enter-PSSession -ComputerName 'SomeServer.OnYour.Domain' -Credential $credential
```

When connecting to a remote computer you do not need to worry about running the local PowerShell session as admin, as PSRemoting always connects as admin since only administrators are allowed to run commands remotely.

You can also specify the `-Authentication` parameter to specify the authentication method to use, such as CredSSP or Kerberos.
Different authentication modes may require additional configuration setup on the local and remote computers.

If you want to connect to the remote computer over SSH instead of WinRM (Windows Remote Management), then use the `-HostName` parameter instead of `-ComputerName`.

```powershell
Enter-PSSession -HostName 'SomeUser@SomeServer.OnYour.Domain'
```

SSH requires PowerShell 6 or later, and supports both password (for interactive sessions) and key-based user authentication.
[See the docs](https://learn.microsoft.com/en-us/powershell/scripting/learn/remoting/ssh-remoting-in-powershell-core?view=powershell-7.2) for more details on using SSH.

You can read more about WinRM and PowerShell remoting in the docs [here](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_remote?view=powershell-7.2) and [here](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_remote_requirements?view=powershell-7.2).

## Needing to run PowerShell as admin

As mentioned above, running commands against `localhost` requires you to be running PowerShell as admin.
This is typically easy to do if you are running PowerShell interactively (assuming your user is an administrator).

[This blog](https://adamtheautomator.com/powershell-run-as-administrator/) describes many different ways that you can launch the interactive PowerShell command prompt as admin.
Near the end it also mentions two ways to run a PowerShell script as admin: using `Start-Process PowerShell -Verb RunAs`, and using the Windows Task Scheduler.

If we need to run a PowerShell script as admin in another system however, such as a CI/CD pipeline in Azure DevOps Pipelines or GitHub Actions, we don't always have control over if the PowerShell process is started as admin or not.

[This StackOverflow post](https://stackoverflow.com/questions/7690994/running-a-command-as-administrator-using-powershell) describes a few different ways to run a PowerShell script as admin, including this code snippet that you can put at the top of your script to have a it elevate itself to run as admin:

```powershell
if (-NOT ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator"))
{
  $arguments = "& '" +$myinvocation.mycommand.definition + "'"
  Start-Process powershell -Verb runAs -ArgumentList $arguments
  Break
}
# Your script code goes here.
```

This approach may work for your needs.
While this approached worked for scripts on my local machine, it did not work for scripts running in my Azure DevOps Pipelines; I still received the `Access Denied` error message.
Even if it had worked, one problem you may have spotted is that it starts up a new PowerShell process to run the script as admin.
This would have resulted in the script output not getting captured by the deployment pipeline, and not being available for monitoring and debugging.

The workaround I ended up using for my specific scenario of connecting to a remote database and performing some operations was to use `Invoke-Command` to run the PowerShell scriptblock as a different user from a remote server.
This isn't an ideal solution as it introduces a dependency on another server simply for the purpose of running some PowerShell code, and can introduce additional security risks by enabling WinRM on the server if it's unneeded otherwise, but it was the best solution I could make work.
We actually have several different pipelines that have this need, so I created a small server whose main purpose is to run PowerShell scripts as a different user.

If you have any other potential solutions, please let me know in the comments below.
I hope you found this post useful.

Happy coding!
