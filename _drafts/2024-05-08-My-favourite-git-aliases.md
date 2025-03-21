---
title: "My favourite git aliases"
permalink: /My-favourite-git-aliases/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Git
tags:
  - Git
---

Git is super powerful, but can also be confusing.
It has so many commands, and I typically only regularly use maybe 5% of them.

To make it both easier to remember commands I rarely use, and to reduce the number of keystrokes needed to execute the ones I use all the time, I use Git aliases.

## Show me the code!

I go over each alias in detail below, but here's the alias section taken directly from my root .gitconfig file in my user directory. e.g. `C:\Users\[Your Name]\.gitconfig`.

```text
[alias]
  alias = !echo 'git config --get-regexp ^alias\\.' && git config --get-regexp ^alias\\.
  ac = !echo 'git add -A , git commit -m' && git add -A && git commit -m
  b = !echo 'git branch' && git branch
  browse = !echo 'start `git config remote.origin.url`' && start `git config remote.origin.url`
  co = !echo 'git checkout' && git checkout
  commit-empty = !echo 'git commit --allow-empty -m \"chore: Empty commit to re-trigger build\"' && git commit --allow-empty -m \"chore: Empty commit to re-trigger build\"
  delete-local-branches-already-merged-in-remote = !echo 'git branch --merged | egrep -i -v(main|master|develop|dev|staging|release)| xargs -r git branch -d' && git branch --merged | egrep -i -v '(main|master|develop|dev|staging|release)' | xargs -r git branch -d
  delete-local-branches-already-merged-in-remote-what-if = !echo 'git branch --merged | egrep -i -v(main|master|develop|dev|staging|release)//| xargs -r git branch -d' && git branch --merged | egrep -i -v '(main|master|develop|dev|staging|release)'
  delete-local-tags = !echo 'git tag -l | xargs git tag -d && git fetch --tags' && git tag -l | xargs git tag -d && git fetch --tags
  delete-stale-remote-tracking-branches-from-local = !echo 'git remote prune origin' && git remote prune origin
  ge = !echo 'GitExtensions' && GitExtensions
  gec = !echo 'GitExtensions commit' && GitExtensions commit
  history = !echo 'git log --oneline --graph --decorate --all' && git log --oneline --graph --decorate --all
  s = !echo 'git status' && git status
  pushf = !echo 'git push --force-with-lease' && git push --force-with-lease
  pushnew = !echo 'git push --set-upstream origin branch_name' && git push --set-upstream origin `git symbolic-ref --short HEAD`
  stashall = !echo 'git stash push --include-untracked' && git stash push --include-untracked
```



![Example image](/assets/Posts/2024-05-08-My-favourite-git-aliases/image-name.png)

Posts in this _drafts directory will not show up on the website unless you build using `--draft` when compiling:

> bundle exec jekyll serve --incremental --draft
