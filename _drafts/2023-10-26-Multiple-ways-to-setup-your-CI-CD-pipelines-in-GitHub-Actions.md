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

In this post I'll show different approaches to setting up your build and deployment workflows with GitHub Actions, as well as some pros and cons of each.

## Background

Over the summer I created [the tiPS PowerShell module](https://github.com/deadlydog/PowerShell.tiPS) in GitHub and decided to use GitHub Actions for the CI/CD process.
For the past few years I have been using Azure DevOps for my CI/CD pipelines, but I wanted to try out GitHub Actions to see how it compared, especially since my code was also hosted in GitHub.
The approaches I show here are ones I tried out in the tiPS project as it evolved, until settling on an approach I was happy with.

## Terminology

Azure DevOps and much of the industry use the term "pipeline" to refer to the automated steps to build and deploy software, but GitHub Actions uses the term "workflow" instead.
For the purposes of this post, the terms "pipeline" and "workflow" are interchangeable.

Similarly, Azure DevOps uses the term "template", while GitHub Actions uses the term "reusable workflow", so I may use them interchangeably as well.

## The approaches

The approaches we will look at are:

1. Place all of the build and deployment steps in a single workflow file.
1. Have a deploy workflow listen for when the build workflow completes (`pull` approach).
1. Have the build workflow trigger the deploy workflow (`push` approach).
1. Have the deploy workflow include the build workflow (`include` approach).

I typically prefer to use the `include` approach, but I'll show each approach so you can decide which one you prefer for a given scenario.

If you are curious or confused about any of the workflow yaml syntax shown in the examples below, checkout [the workflow syntax for GitHub Actions docs](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions).

I created [this sample GitHub repository](https://github.com/deadlydog/GitHub.Experiment.CiCdApproachesWithGitHubActions) that contains all of the examples shown in this post, so you view their code and can see how they look in [the GitHub Actions web UI](https://github.com/deadlydog/GitHub.Experiment.CiCdApproachesWithGitHubActions/actions).

In the example yaml code, I use the "👇" emoji to call out specific things to take note of, or that have changed from one approach to the next.

## Approach 1: Build and deploy with a single workflow file

Here is an example of a single workflow file that builds and deploys some code:

```yaml
name: single-file--build-and-deploy

on:
  pull_request:
    branches: main # Run workflow on PRs to the main branch.

  # Run workflow on pushes to any branch.
  push:

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

      # Steps to version, build, and test the code go here.

      - name: Upload artifact
        uses: actions/upload-artifact@v3
        with:
          name: ${{ env.artifactName }}
          path: ./ # Put the path to the build artifact files directory here.

  deploy-to-staging:
    # 👇 Only run this deploy job after the build-and-test job completes successfully.
    needs: build-and-test
    runs-on: ubuntu-latest
    environment: staging # Used for environment-specific variables, secrets, and approvals.
    # 👇 Only deploy on pushes or manual triggers (not on PRs though) to the main branch.
    if: (github.event_name == 'push' || github.event_name == 'workflow_dispatch') && github.ref == 'refs/heads/main'
    steps:
      - name: Download artifact
        uses: actions/download-artifact@v2
        with:
          name: ${{ env.artifactName }}
          path: ./buildArtifact

      # Steps to deploy the code go here.

  deploy-to-production:
    # 👇 Only run this deploy job after the deploy-to-staging job completes successfully.
    needs: deploy-to-staging
    runs-on: ubuntu-latest
    environment: production # Used for environment-specific variables, secrets, and approvals.
    steps:
      - name: Download artifact
        uses: actions/download-artifact@v2
        with:
          name: ${{ env.artifactName }}
          path: ./buildArtifact

      # Steps to deploy the code go here.
```

There are a few things to note here.
First, the workflow is automatically triggered when a PR to the main branch is created, or when a change is pushed to the main branch.
It can also be manually triggered.
Second, the workflow has 3 jobs: `build-and-test`, `deploy-to-staging`, and `deploy-to-production`.
Notice in the `deploy-to-staging` job we use a conditional `if` statement to ensure we do not deploy if the workflow was triggered by a PR, or if the `push` was not for the `main` branch.

Technically we did not need to create separate deploy jobs, and could have just put the deployment steps in the `build-and-test` job.
In general, it is a good idea to separate the build steps from the deployment steps to maintain a separation of concerns.
A technical reason for keeping them separate is GitHub Actions allows you to add approvals to a job via [the `environment` key](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions#jobsjob_idenvironment).
Approvals are often used to block deployments until someone manually approves it.
Only jobs support an `environment`, and you would typically have a deployment job for each environment that you need to deploy to.

Lastly, if you do decide to use a single job for both the build and deployment steps, then you technically do not need the `Upload artifact` and `Download artifact` steps.
I would still recommend using the `Upload artifact` step though so that the build artifact is available for download in the GitHub Actions UI, in case you need to inspect its files.

### Pros and cons of using a single workflow file

Pros:

- Simple to setup, as it is only a single file.
- Environment variables can be used to easily share predefined constants between jobs.
  e.g. `env.artifactName`

Cons:

- The workflow file may quickly grow in size as more steps and jobs are added.
  Having a single yaml file that is several hundred or thousands of lines long can be more difficult to maintain and daunting to look at.
- If you want to deploy to multiple environments, you will need to duplicate the deployment steps for each environment (see the Reusable Workflows section below on how to solve this).
- The deployment jobs/steps will be skipped for PRs and branch builds, but will still show up in the workflow UI on the GitHub website.
  This can be confusing to users, as they may not understand why those jobs/steps were skipped.
- The PR builds and non-main branch builds will show up in the same `single-file--build-and-deploy` workflow runs as the `main` branch builds.
  If there are a lot of PR runs or pushes to branches, they may bury the `main` branch runs, forcing you to go back several pages to find the `main` branch runs to answer questions like, "When was the last time we deployed to production?".
  ![Main branch build buried under PR builds](/assets/Posts/2023-10-26-Multiple-ways-to-setup-your-CI-CD-pipelines-in-GitHub-Actions/main-branch-build-buried-under-pr-builds-in-GitHub-workflow-runs.png)

## Approach 2: Deploy workflow listens for build workflow to complete (Pull approach)

Here is an example of a workflow file that just builds the code:

```yaml
name: pull--build

on:
  pull_request:
    branches: main # Run workflow on PRs to the main branch.

  # Run workflow on pushes to any branch.
  push:

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

      # Steps to version, build, and test the code go here.

      - name: Upload artifact
        uses: actions/upload-artifact@v3
        with:
          name: ${{ env.artifactName }}
          path: ./ # Put the path to the build artifact files directory here.
```

And the accompanying workflow file that deploys the code using the pull approach:

```yaml
name: pull--deploy

on:
  # Run workflow anytime the pull--build workflow completes for the main branch.
  workflow_run:
    workflows: pull--build
    types: completed
    branches: main

  # Allows you to run this workflow manually from the Actions tab.
  workflow_dispatch:
    inputs:
      workflowRunId:
        description: 'The build workflow run ID containing the artifacts to use. The run ID can be found in the URL of the build workflow run.'
        type: number
        required: true

env:
  artifactName: buildArtifact # This must match the artifact name in the pull--build workflow.
  # Ternary operator to use input value if manually triggered, otherwise use the workflow_run.id of the workflow run that triggered this one.
  workflowRunId: ${{ github.event_name == 'workflow_dispatch' && inputs.workflowRunId || github.event.workflow_run.id }}

jobs:
  deploy-to-staging:
    # Only run the deployment if manually triggered, or the build workflow succeeded.
    if: ${{ github.event_name == 'workflow_dispatch' || github.event.workflow_run.conclusion == 'success' }}
    runs-on: ubuntu-latest
    steps:
      # Must use a 3rd party action to download artifacts from other workflows.
      - name: Download prerelease module artifact from triggered workflow
        uses: dawidd6/action-download-artifact@v2
        with:
          run_id: ${{ env.workflowRunId }}
          name: ${{ env.artifactName}}
          path: ./buildArtifact
          search_artifacts: true

      # Steps to deploy the code go here.

  deploy-to-production:
    # Only run this deploy job after the deploy-to-staging job completes successfully.
    needs: deploy-to-staging
    runs-on: ubuntu-latest
    environment: production # Used for environment-specific variables, secrets, and approvals.
    steps:
      # Must use a 3rd party action to download artifacts from other workflows.
      - name: Download prerelease module artifact from triggered workflow
        uses: dawidd6/action-download-artifact@v2
        with:
          run_id: ${{ env.workflowRunId }}
          name: ${{ env.artifactName}}
          path: ./buildArtifact
          search_artifacts: true

      # Steps to deploy the code go here.
```

Here the build workflow is separate from the deployment workflow.
The build workflow is triggered when there is a push to any branch, a PR to the main branch, or when manually triggered.
The deployment workflow waits and listens for the build workflow to complete against the main branch.

Because the build uses its own workflow, the deployment workflow needs a reference to the build's workflow run ID so it knows which build run to download the artifacts from.
This is provided automatically when the build triggers the deployment workflow, but must be provided manually when the deployment workflow is manually triggered.
You can find the build workflow run ID by opening the build workflow run in the GitHub Actions UI and looking at the URL.
The URL will look something like `https://github.com/deadlydog/PowerShell.tiPS/actions/runs/6364723098`, where the run ID is `6364723098`.

The next thing to note is that the `artifactName` environment variable is duplicated in both the build and deployment workflows.
We could have the build workflow create an output variable that the deployment workflow could reference, but for the sake of simplicity I just duplicated the environment variable here.

Next, notice that the `deploy-to-staging` job has a conditional `if` statement that will only run the job if the workflow was manually triggered, or if the build workflow completed successfully.
Unfortunately, at this time, [the `on.workflow_run` event](https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#workflow_run) does not have a property to indicate that the deploy workflow should only be triggered if the build workflow completed successfully, so we have to do the check ourselves on the job.

Lastly, the deployment jobs use a 3rd party action to download the build artifact.
At this time, GitHub Actions does not have a built-in action to download artifacts from other workflows.
They do provide [API endpoints to download artifacts from other workflows](https://stackoverflow.com/a/77009805/602585), but it is simpler to use the 3rd party action.

### Pros and cons of using the pull approach

Pros:

- The build and deployment steps are separated into their own workflows, which makes it easier to maintain and understand.
- Builds for PRs and non-main branches that we do not want deployed do not trigger deployment workflows.
- The build workflow only shows the build steps in the GitHub Actions UI, and the deployment workflow only shows the deployment steps.
- Deployments for non-main branches and PRs can still be manually deployed if needed without any workflow code changes.

Cons:

- The deployment workflow is triggered even if the build workflow fails, resulting in skipped deployment runs showing in the workflow UI.
  This can be confusing to users, and it clutters the workflow UI.
- Certain variables must be duplicated between the build and deployment workflows (e.g. `env.artifactName`), or additional code added to pass the variables between the workflows.

### Additional thoughts

After using Azure DevOps classic pipelines, this approach felt very natural.
In Azure DevOps, you would explicitly create your build and deployment pipelines, and the first step of the deployment pipeline is specifying the build that it should pull the artifacts from, and potentially automatically trigger off of.

This worked quite well in GitHub at first, but I really did not like how blank, skipped deployment workflow runs got created when the build failed.
It quickly cluttered up the deployment runs when issues were encountered with the build workflow that took many attempts to fix.

## Approach 3: Build workflow triggers deploy workflow (Push approach)

Here is an example of a workflow that builds the code and then triggers a deployment workflow:

```yaml
name: push--build

on:
  pull_request:
    branches: main # Run workflow on PRs to the main branch.

  # Run workflow on pushes to any branch.
  push:

  # Allows you to run this workflow manually from the Actions tab.
  workflow_dispatch:
    inputs:
      deploy:
        description: 'Deploy the build artifacts. Only has effect when not building the main branch.'
        required: false
        type: boolean
        default: false

env:
  artifactName: buildArtifact

jobs:
  build-and-test:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout the repo source code
        uses: actions/checkout@v3

      # Steps to version, build, and test the code go here.

      - name: Upload artifact
        uses: actions/upload-artifact@v3
        with:
          name: ${{ env.artifactName }}
          path: ./ # Put the path to the build artifact files directory here.

  trigger-deployment:
    needs: build-and-test
    # Only trigger a deployment if the deploy parameter was set, or this build is for a push (not a PR) on the default branch (main).
    if: inputs.deploy || (github.event_name != 'pull_request' && github.ref == format('refs/heads/{0}', github.event.repository.default_branch))
    uses: ./.github/workflows/3-push--deploy.yml
    secrets: inherit
```

And here is the accompanying deployment workflow:

```yaml
name: push--deploy

on:
  workflow_call: # Allow this workflow to be called by other workflows.

env:
  artifactName: buildArtifact # This must match the artifact name in the push--build workflow.

jobs:
  deploy-to-staging:
    runs-on: ubuntu-latest
    steps:
      - name: Download artifact
        uses: actions/download-artifact@v2
        with:
          name: ${{ env.artifactName }}
          path: ./buildArtifact

      # Steps to deploy the code go here.

  deploy-to-production:
    # Only run this deploy job after the deploy-to-staging job completes successfully.
    needs: deploy-to-staging
    runs-on: ubuntu-latest
    environment: production # Used for environment-specific variables, secrets, and approvals.
    steps:
      - name: Download artifact
        uses: actions/download-artifact@v2
        with:
          name: ${{ env.artifactName }}
          path: ./buildArtifact

      # Steps to deploy the code go here.
```

## Reusable workflows (templates)

You probably noticed that we are always deploying to 2 environments: staging and production.
You may have even more environments that you need to deploy to.
This results in a lot of duplicate code in the deployment workflows.

To avoid the duplicate code, we can use a reusable workflow to define the deployment jobs, and then include the reusable workflow in the deployment workflow.
Azure DevOps calls these "templates", but GitHub Actions calls them "reusable workflows".
You can think of a reusable workflow as a function that accepts parameters, so you define it once, and then can call it multiple times with different parameters.
One caveat to be aware of is that while templates may also include other templates, GitHub only allows up to 4 levels of template nesting.
Also, a workflow may only call up to 20 other workflows, including nested ones.

Here is an example of a reusable workflow that deploys the code:

```yaml

```

And here is the accompanying deployment workflow that calls the reusable workflow:

```yaml

```

See the [GitHub docs on reusable workflows](https://docs.github.com/en/actions/using-workflows/reusing-workflows) for more information.

## Other considerations

Some things that may affect which approach you use are:

- Do you want to build when a Pull Request (PR) is created?
  How about when a change is pushed to a branch?
- Do you want to deploy PR builds?
- Do you need to deploy to multiple environments?
- Do you want deployments to some environments to happen automatically, and others to be manually triggered, or require approval?

You may have workflows that you want to run on a schedule, when a tag is created, or some other reason.
For example, you may want to run a load testing workflow every Tuesday night.
Hopefully you an use one of the techniques above to setup your workflows.

## Conclusion

In this post we've seen a number of different approach you can take to define your build and deploy workflows.
I personally prefer the last `include` approach, but you may prefer another.

I also created [this sample GitHub repository](https://github.com/deadlydog/GitHub.Experiment.CiCdApproachesWithGitHubActions) that contains all of the examples shown in this post, so you view their code and can see how they look in the GitHub Actions menu.
