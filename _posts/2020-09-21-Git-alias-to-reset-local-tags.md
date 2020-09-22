---
title: "Git alias to reset local tags"
permalink: /Git-alias-to-reset-local-tags/
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
This results in a Git error when hitting the small Git sync button in the VS Code bottom toolbar.

![VS Code sync icon](/assets/Posts/2020-09-21-Git-alias-to-reset-local-tags/VsCodeSyncIcon.png)

To easily solve this issue, I setup this alias in my `.gitconfig` to easily wipe my local tags and reset them to what the remote has:

```bash
[alias]
    delete-local-tags = !echo 'git tag -l | xargs git tag -d && git fetch --tags' && git tag -l | xargs git tag -d && git fetch --tags
```

This snippet assumes you're running Git in a Bash prompt.

Now when I encounter this error, from the command line I just type `git delete-local-tags` and it resets my local tags to what the remote has, making VS Code happy and enabling me to sync Git with a single button click again.

The `!echo '[string]'` portion isn't required as it will simply display the command that is about to run, but I like to know what my aliases are doing behind the scenes when I run them.

Happy syncing :)
