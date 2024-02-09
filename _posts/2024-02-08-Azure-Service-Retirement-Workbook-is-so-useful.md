---
title: "Azure Service Retirement Workbook is so useful"
permalink: /Azure-Service-Retirement-Workbook-is-so-useful/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Azure
tags:
  - Azure
---

The Azure portal has a Service Retirement Workbook that shows all of the services you are using which are being retired within the next few years.
This is extremely helpful when your organization has many teams using a lot of different services, and they need time to plan the migration work into their roadmaps.
Shout out to my colleague Amer Gill for showing me this!

To access the view in the Azure portal, open up the `Advisor`, navigate to `Workbooks` (or use [this link](https://portal.azure.com/#view/Microsoft_Azure_Expert/AdvisorMenuBlade/~/workbooks)), and open the `Service Retirement (Preview)` workbook.

![Navigating to the Service Retirement Workbook](/assets/Posts/2024-02-08-Azure-Service-Retirement-Workbook-is-so-useful/navigate-to-services-retirement-workbook-in-azure-advisor.png)

At the top you will see a list of services you are using that are being retired soon, the date they are being retired, how many of your resources will be affected, and a link to documentation about the retirement and actions to take.

![Service Retirement Workbook list of services](/assets/Posts/2024-02-08-Azure-Service-Retirement-Workbook-is-so-useful/azure-services-retirement-workbook-list-of-services-retiring.png)

Below that is a list of resources that will be affected by the service retirements you have selected in the list above.
This tells you the resources' subscription, resource group, name, type, location, tags, and a link to actions to take.

![Service Retirement Workbook list of resources](/assets/Posts/2024-02-08-Azure-Service-Retirement-Workbook-is-so-useful/list-of-resources-affected-by-service-retirement.png)

You can also export the results to Excel, making the information easy to share with others.

Having a single up-to-date view of all the services being retired, which resources will be affected, and links to documentation on actions that need to be taken is extremely helpful and convenient.
Anybody in the organization is able to view the workbook, and as teams migrate their resources to newer services they are removed from the list, giving a realtime view of which resources still need attention.

__Recommended Action:__ Set up a recurring reminder to check this workbook every month or quarter, so you can stay on top of any upcoming retirements that may affect you or your organization.
Share this information with other teams so they can do the same.

Did you find this information as helpful as I did?
Have any other related tips?
Let me know in the comments below.

Cheers!
