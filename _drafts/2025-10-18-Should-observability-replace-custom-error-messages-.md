---
title: "Should observability replace custom error messages?"
permalink: /Should-observability-replace-custom-error-messages-/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Software Development
tags:
  - Software Development
---

Open Telemetry (OTEL) and observability tools have come a long way in recent years.
They can provide a wealth of information about what your application was doing when an error occurred.
But can they replace good custom error messages that provide additional context about what went wrong?

## Background

Dave Callan, a fellow Microsoft MVP, recently [asked on LinkedIn](https://www.linkedin.com/posts/davidcallan_net-devs-when-you-expect-exactly-1-item-activity-7382327791356334080-PgYc/) if .NET developers prefer to use `.First()`, `.FirstOrDefault()`, `.Single()`, or `.SingleOrDefault()` when they expect exactly one item in a collection.

I've written about this very subject, so I commented with a link to [my blog post](https://blog.danskingdom.com/First-Single-and-SingleOrDefault-in-dotnet-are-harmful/) where I state that I prefer to always use `.FirstOrDefault()` because it allows me to provide a descriptive custom error message, which can make troubleshooting much easier.
[Dave replied](https://www.linkedin.com/feed/update/urn:li:ugcPost:7382327790437814272?commentUrn=urn%3Ali%3Acomment%3A%28ugcPost%3A7382327790437814272%2C7382838797274845184%29&replyUrn=urn%3Ali%3Acomment%3A%28ugcPost%3A7382327790437814272%2C7383115784740417536%29&dashCommentUrn=urn%3Ali%3Afsd_comment%3A%287382838797274845184%2Curn%3Ali%3AugcPost%3A7382327790437814272%29&dashReplyUrn=urn%3Ali%3Afsd_comment%3A%287383115784740417536%2Curn%3Ali%3AugcPost%3A7382327790437814272%29) that he prefers to use `.Single()`, even though it gives a generic error message, and rely on observability tools to troubleshoot issues when they arise; especially for issues that "should never happen".

This got me thinking about whether observability tools can truly replace good custom error messages.

## Observability is awesome, but is it a silver bullet?

There's no question that adding OTEL instrumentation to your application can provide valuable insights into its behavior.
It provides additional information, allowing you to troubleshoot issues and spot trends before they become problems.
When possible, you should absolutely add OTEL instrumentation to your application.

Should observability replace custom error messages though?

To be clear, I'm not talking about simply catching an exception and rewriting it with a more user-friendly message.
I'm talking about including specific data that may have led to the error, such as the parameter values that were used, the dataset that was being queried, or any other relevant context that could aid in troubleshooting.
As with all logging though, be mindful of not including sensitive information in error messages, such as passwords or personal data.

I would argue that custom error messages are still valuable, for a number of reasons:

### Quicker troubleshooting

- Custom error messages can provide immediate context about what went wrong, and can be surfaced in different ways:
  - Shown in the observability tooling.
  - Logged in application logs (local or remote), separate from the observability tooling.
  - Displayed to the user, if appropriate.
- Allows developers to identify problems faster, as they don't need to query the observability tooling and follow breadcrumbs to find the relevant information.
- Potentially allows users to self-serve and resolve issues on their own, if the error message is displayed to them.
  - If a user presses a button to submit a form and sees the error message "System.InvalidOperationException: Sequence contains more than one matching element", they may not know what to do.
  However, if they see "Error: More than one user found with the email address '<me@example.com>'", they have a clearer understanding of the issue and can potentially resolve it themselves.
  - Allowing users or support staff to resolve issues on their own saves both users and developers time, making everyone happier.

### Do observability tools have the data you need?

- Observability tooling relies on the data that is sent to it.
  If the necessary data is not being captured, it may not be available when you need it.
- Custom error messages can provide additional context that may not be captured by the observability tooling.
  - For example, a custom error message can include the parameters that were used for a specific query, or the dataset that was being queried.
- Do you have every component in the chain instrumented?
  - If your application calls an external service or third-party library that is not instrumented, it may not have the necessary data to help troubleshoot an issue.

### Budgets change, or are non-existent

- Observability tooling can be expensive, and not all organizations have the budget for it.
- Even if your organization has the budget now, it may not in the future.
- Many organizations implement sampling to reduce costs (e.g. only retain 20% of traces), which means you may not have the necessary data to troubleshoot a specific issue.

### Access to observability tooling

- Not all developers may have access to the observability tooling, especially in larger organizations, or where you pay per user.
  - Does every team member have access to the tooling?
  What about support staff?

### Team members change

- Do all team members know how to effectively use the observability tooling?
  - Team members who are familiar with the tools may leave the organization.
  - New team members may not be familiar with the observability tools, know where to find them, or even know they exist.

### Observability tooling changes

- Organizations may change observability providers (e.g. New Relic to Datadog to Honeycomb to ...), which means team members may need to learn new tools.
- Have all of the old apps and services been updated to send data to the new observability provider?

> Aside: I'm a huge fan of [Honeycomb.io](https://www.honeycomb.io).

### The type of application matters

- Do users expect a desktop/mobile application to be sending observability data over the network?
- Do users expect your app to store all of that observability data locally?
- Users may not want an application to send or store observability data at all, due to privacy, data usage, or performance concerns.

## Conclusion

It probably seems obvious that it's preferable to include information in error message that can help troubleshoot the issue.
I can't tell you though how many times I've been frustrated, both as a user and as a developer, by generic error messages that provide no context about what went wrong.

I'm of the opinion that the more information you can provide when an error occurs, the better.
There's no doubt that observability can provide additional information that can help troubleshoot issues.
However, there's no guarantee that it will provide the same information that a well-crafted custom error message can provide, or that the observability data will be available when you need it.

A developer spending an extra couple of minutes writing a custom error message that provides additional context can save hours, even days, of troubleshooting time later on.
Even if it's not an error message the end-user will see, it can still be tremendously helpful for developers and support staff.

So the next time you're calling a method that you know may potentially thrown an exception, consider whether catching and re-throwing the exception with additional context information may be beneficial.

<!-- ![Example image](/assets/Posts/2025-10-18-Should-observability-replace-custom-error-messages-/image-name.png) -->
