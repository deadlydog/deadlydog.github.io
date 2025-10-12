---
title: "Should Observability replace good error messages?"
permalink: /Should-Observability-replace-good-error-messages-/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Blog
tags:
  - Should all
  - Start with
  - Capitals
---

Open Telemetry (OTEL) and Observability tools have come a long way in recent years.
They can provide a wealth of information about what your application was doing when an error occurred.
But can they really replace good error messages that provide context about what went wrong?

## Background

Dave Callan, a fellow Microsoft MVP, recently [asked on LinkedIn](https://www.linkedin.com/posts/davidcallan_net-devs-when-you-expect-exactly-1-item-activity-7382327791356334080-PgYc/) if .NET developers prefer to use `First`, `FirstOrDefault`, `Single`, or `SingleOrDefault` when they expect exactly one item in a collection.

I've written about this very subject, so I commented with a link to [my blog post](https://blog.danskingdom.com/First-Single-and-SingleOrDefault-in-dotnet-are-harmful/) where I state that I prefer to always use `FirstOrDefault` because it allows me to provide a descriptive custom error message, which makes troubleshooting easier.
[Dave replied](https://www.linkedin.com/feed/update/urn:li:ugcPost:7382327790437814272?commentUrn=urn%3Ali%3Acomment%3A%28ugcPost%3A7382327790437814272%2C7382838797274845184%29&replyUrn=urn%3Ali%3Acomment%3A%28ugcPost%3A7382327790437814272%2C7383115784740417536%29&dashCommentUrn=urn%3Ali%3Afsd_comment%3A%287382838797274845184%2Curn%3Ali%3AugcPost%3A7382327790437814272%29&dashReplyUrn=urn%3Ali%3Afsd_comment%3A%287383115784740417536%2Curn%3Ali%3AugcPost%3A7382327790437814272%29) that he prefers to use `Single`, even though it gives a generic error message, and rely on Observability tools to troubleshoot issues when they arise.

This got me thinking about whether Observability tools can really replace good error messages.

## Observability tools have come a long way


Open Telemetry (OTEL) and Observability tools have come a long way in recent years.
If your team has the budget for good observability tooling, and is disciplined about ensuring everything is instrumented and kept up to date (e.g. what parameters were used to run the query, what the dataset was, etc.) then you may be able to troubleshoot the issue without using custom error messages.
For now.

You have to consider what your team, your app/service, and your organization will look like 3 years from now.
Will you still have the same budget for observability?
Will you still have the same team members who know how to use the tools?
Will you still have the same observability tools?

My experience has been that team members come and go, budgets change, and tools change.
Your organization may change observability providers (e.g. New Relic to Datadog to Splunk to ...), but






Start with a brief description of the post to entice readers to continue.

Use headers to organize your post, if it's long enough.
Include a TL;DR if appropriate.

End with a brief conclusion.

Your regular markdown goes here. It also supports HTML :)

Try to include at least one image, as it makes preview links more appealing since the first image often gets used as the preview image:

![Example image](/assets/Posts/2025-10-12-Should-Observability-replace-good-error-messages-/image-name.png)

Posts in this _drafts directory will not show up on the website unless you build using `--draft` when compiling:

> bundle exec jekyll serve --incremental --draft
