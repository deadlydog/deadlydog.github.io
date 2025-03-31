---
title: "Using GitHub Issues and Actions to create a simple form that can create pull requests"
permalink: /Using-GitHub-Issues-and-Actions-to-create-a-simple-form-that-can-create-pull-requests/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - GitHub
tags:
  - GitHub
---

I wanted a simple way for users to be able to submit tips to [the tiPS PowerShell module](https://github.com/deadlydog/PowerShell.tiPS).
Adding a tip involves creating a pull request to the repository with a new tip file.

In this post I'll show how I use a GitHub Issue Template to create a simple form that users can fill out to submit a tip.
When the issue is created, it triggers a GitHub Action that creates a new branch, creates a new tip file in the branch, and creates a pull request from the branch to the main branch.
This is a simple way to allow users to submit tips without having to fork the repo and create a pull request themselves.

## Other approaches I considered

I initially thought of hosting a simple web form on GitHub Pages and calling the GitHub APIs.
I wasn't sure if it would be possible to do that without exposing a personal access token (PAT) in the browser to call the GitHub APIs with, which would be a security risk.
I didn't really want to pay to setup a server/function/lambda just to handle the form submission with the PAT either.

Then I saw [this blog post on GitHub IssueOps](https://github.blog/engineering/issueops-automate-ci-cd-and-more-with-github-issues-and-actions/), which reminded me that using GitHub Issues and Actions was an option.
I did not use the IssueOps app, as it would involve more setup and complexity than I wanted for my simple use case, but the concept is the same.

## Creating the GitHub Issue Template




Start with a brief description of the post to entice readers to continue.

Use headers to organize your post, if it's long enough.
Include a TL;DR if appropriate.

End with a brief conclusion.

Your regular markdown goes here. It also supports HTML :)

Try to include at least one image, as it makes preview links more appealing since the first image often gets used as the preview image:

![Example image](/assets/Posts/2025-03-29-Using-GitHub-Issues-and-Actions-to-create-a-simple-form-that-can-create-pull-requests/image-name.png)
