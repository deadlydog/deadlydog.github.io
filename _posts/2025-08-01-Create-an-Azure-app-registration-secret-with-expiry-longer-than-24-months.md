---
title: "Create an Azure app registration secret with expiry longer than 24 months"
permalink: /Create-an-Azure-app-registration-secret-with-expiry-longer-than-24-months/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Azure
tags:
  - Azure
---

The Azure portal allows you to create app registration secrets with a maximum expiry of 24 months.
This follows the security best practice of forcing you to rotate secrets regularly to minimize the risk of exposure.
However, there are scenarios where you might want a secret that lasts longer than this limit.

> NOTE: Using long-lived secrets can increase the risk of exposure, so it's typically not recommended for production applications.
> Especially for secrets that would grant access to sensitive resources or data, or that have high privileges.
> A better approach is to use short-lived secrets and automate rotating them regularly.

To create an Azure app registration (i.e. service principal) secret with an expiry longer than 2 years, you can use [the Azure CLI](https://learn.microsoft.com/en-us/cli/azure/?view=azure-cli-latest).
Here's an example command that creates a secret with a 5 year expiry, where `<app-id>` is the Application (Client) ID of your app registration:

```bash
az ad app credential reset --id <application-id> --years 5
```

This will create a new secret on the app registration and output the `password`, which is the secret value.
Be sure to copy the value and store it somewhere secure, such as an Azure Key Vault, as you will not be able to retrieve it again.

You can do the same operation in PowerShell using the `Az` PowerShell module (specifically the `Az.Resources` module) and [the New-AzADAppCredential cmdlet](https://learn.microsoft.com/en-us/powershell/module/az.resources/new-azadappcredential), but you must use the app registration's Object ID instead of the Application ID:

```powershell
$startDate = (Get-Date)
$endDate = $startDate.AddYears(5)
New-AzADAppCredential -ObjectId <object-id> -StartDate $startDate -EndDate $endDate -DisplayName 'User friendly description'
```

Hopefully you (and my future self) find this helpful.

Happy coding!

![Azure secrets 2+ years](/assets/Posts/2025-08-01-Create-an-Azure-app-registration-secret-with-expiry-longer-than-24-months/azure-secret-2+-years.png)
