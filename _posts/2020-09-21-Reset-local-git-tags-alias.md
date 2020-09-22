---
title: "Reset local Git tags alias"
permalink: /Reset-local-Git-tags-alias/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Git
  - Productivity
tags:
  - Git
  - Productivity
  - Tags
---

I've noticed that VS Code sometimes detects conflicts between my local Git tags and remote ones.
This results in a Git error when hitting the Git sync button in VS Code.



To easily solve this issue, I setup this alias in my .gitconfig to easily wipe my local tags and reset them to what the remote has:

```bash
delete-local-tags = !echo 'git tag -l | xargs git tag -d && git fetch --tags' && git tag -l | xargs git tag -d && git fetch --tags
```

Now when I encounter this error, from the command line I just type `git delete-local-tags` and it resets my local tags to what the remote has, making VS Code happy and enabling me to sync Git with a single button click again.
