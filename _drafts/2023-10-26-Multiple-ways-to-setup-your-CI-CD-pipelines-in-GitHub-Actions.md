---
title: "Multiple ways to setup your CI/CD pipelines in GitHub Actions"
permalink: /Multiple-ways-to-setup-your-CI-CD-pipelines-in-GitHub-Actions/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - GitHub
  - Build
  - Deploy
tags:
  - GitHub
  - Build
  - Deploy
  - GitHub Actions
  - Continuous Integration
  - Continuous Deployment
---

In this post I'll show four different approaches to setting up your build and deployment workflows with GitHub Actions, as well as some pros and cons of each.

## Background

Over the summer I created [the tiPS PowerShell module](https://github.com/deadlydog/PowerShell.tiPS) project in GitHub and decided to use GitHub Actions for the CI/CD process.
For the past few years I have been using Azure DevOps for my CI/CD pipelines, but I wanted to try out GitHub Actions to see how it compared, especially since my code was also hosted in GitHub.
The approaches I'll show here are the ones I tried out in the tiPS project, along with links to the various PRs of switching from one method to another, before finally settling on the one I liked best.

## Terminology

Azure DevOps and much of the industry use the term "pipeline" to refer to the automated steps to build and deploy software, but GitHub Actions uses the term "workflow" instead.
For the purposes of this post, the terms "pipeline" and "workflow" are interchangeable.

## The approaches

The approaches we will look at are:

1. Place all of the build and deployment steps in a single workflow file.
1. Have a deploy workflow listen for when the build workflow completes (`pull` method).
1. Have the build workflow trigger the deploy workflow (`push` method).
1. Have the deploy workflow include the build workflow (`include` method).

I typically prefer to use the `include` method, but I'll show each method so you can decide which one you prefer.

If you are curious or confused about any of the workflow yaml syntax shown in the examples below, checkout [the workflow syntax for GitHub Actions docs](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions).

## Considerations

Before we get into the details of each approach, some things to keep in mind as we see the different approaches are:

- Do you want to build when a Pull Request (PR) is created?
  How about when a change is pushed to a branch?
- Do you want to deploy PR builds?
- Do you need to deploy to multiple environments?
- Do you want deployments to some environments to happen automatically, and others to be manually triggered, or require approval?

## Approach 1: Build and deploy with a single workflow file

Here is an example of a single workflow file that builds and deploys some code:

```yaml
name: build-and-deploy

on:
  pull_request:
    branches: main # Run workflow on PRs to the main branch.

  push:
    branches: main # Run workflow on pushes to the main branch.

  # Allows you to run this workflow manually from the Actions tab.
  workflow_dispatch:

env:
  artifactName: buildArtifact

jobs:
  build-and-test:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout the repo source code
        uses: actions/checkout@v3

      # Version, build, and test the code here.

      - name: Upload artifact
        uses: actions/upload-artifact@v3
        with:
          name: ${{ env.artifactName }}
          path: ./path/to/buildArtifact

  deploy:
    # Only run this deploy job after the build-and-test job completes successfully.
    needs: build-and-test
    runs-on: ubuntu-latest
    # Only deploy on pushes or manual triggers (not on PRs though) to the main branch.
    if: (github.event_name == 'push' || github.event_name = 'workflow_dispatch') && github.ref == 'refs/heads/main'
    steps:
      - name: Download artifact
        uses: actions/download-artifact@v2
        with:
          name: ${{ env.artifactName }}
          path: ./path/to/put/buildArtifact

      # Deploy the code here.
```

There are a few things to note here.
First, the workflow is automatically triggered when a PR to the main branch is created, or when a change is pushed to the main branch.
It can also be manually triggered.
Second, the workflow has two jobs: `build-and-test` and `deploy`.
Notice in the `deploy` job we use a conditional `if` statement to ensure we do not deploy if the workflow was triggered by a PR, or if the `push` was not for the `main` branch.

Technically we did not need to create a separate `deploy` job, and could have just put the deployment steps in the `build-and-test` job.
In general, it is a good idea to separate the build steps from the deployment steps to maintain a separation of concerns.
A technical reason for keeping them separate is GitHub Actions allows you to add approvals to a job via [the `environment` key](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions#jobsjob_idenvironment).
Approvals are often used to block deployments to production until someone manually approves it.
Only jobs support an `environment`.
In the example we only deploy to a single environment, but in a real project you would likely have a deployment job for each environment that you need to deploy to.

Lastly, if you do decide to use a single job for both the build and deployment steps, then you technically do not need the `Upload artifact` and `Download artifact` steps.
I would still recommend using the `Upload artifact` step though so that the build artifact is available for download in the GitHub Actions UI, in case you need to inspect it.

### Pros and cons of using a single workflow file

Pros:

- Simple to setup, as it is only a single file.
- Environment variables can be used to easily share predefined constants between jobs.
  e.g. `env.artifactName`

Cons:

- The workflow file will quickly grow in size as more steps and jobs are added.
  Having a single yaml file that is several hundred or thousands of lines long can be more difficult to maintain and daunting to look at.
- If you want to deploy to multiple environments, you will need to duplicate the deployment steps for each environment.
- The deployment steps will be skipped for PRs and branch builds, but will still show up in the workflow UI.
  This can be confusing to users, as they may not understand why those jobs/steps were skipped.
- The PR builds will show up in the same `build-and-deploy` workflow runs as the `main` branch builds.
  If there are a lot of PR runs, they may bury the `main` branch runs, forcing you to go back several pages to find the `main` branch runs to answer questions like, "When was the last time we deployed to production?".
  ![Main branch build buried under PR builds](/assets/Posts/2023-10-26-Multiple-ways-to-setup-your-CI-CD-pipelines-in-GitHub-Actions/main-branch-build-buried-under-pr-builds-in-GitHub-workflow-runs.png)

## Approach 2: Deploy workflow listens for build workflow to complete (Pull method)

Here is an example of a workflow file that just builds the code:

```yaml

```

Here is an example of a workflow file that deploys the code using the pull method:

```yaml

```

Here the build workflow
