---
title: "My favourite git aliases"
permalink: /My-favourite-git-aliases/
#date: 2099-01-15T00:00:00-06:00
last_modified_at: 2025-04-03
comments_locked: false
toc: false
categories:
  - Git
  - Productivity
tags:
  - Git
  - Productivity
---

Git is super powerful, but can also be confusing, especially when using the command line interface (CLI).
It has so many commands, and I only regularly use a handful of them.

To make it both easier to remember commands I rarely use, and to reduce the number of keystrokes needed to execute the ones I use all the time, I use Git aliases.

## Show me the code!

I go over each alias in detail below, but here's the alias section taken directly from my current global `.gitconfig` file in my user directory.
e.g. `C:\Users\[Your Name]\.gitconfig` on Windows, or `~/.gitconfig` on Linux/Mac.

```shell
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
  ge = !echo 'GitExtensions .' && GitExtensions .
  gec = !echo 'GitExtensions commit' && GitExtensions commit
  gtfo = !echo 'git reset --hard , git clean -xfd' && git reset --hard && git clean -xfd
  history = !echo 'git log --oneline --graph --decorate --all' && git log --oneline --graph --decorate --all
  s = !echo 'git status' && git status
  pushf = !echo 'git push --force-with-lease' && git push --force-with-lease
  pushnew = !echo 'git push --set-upstream origin branch_name' && git push --set-upstream origin `git symbolic-ref --short HEAD`
  stashall = !echo 'git stash push --include-untracked' && git stash push --include-untracked
```

You will notice that each alias begins with `!echo '...' &&`.
This is not mandatory and you can remove it if you like; it will simply print the command to the console before executing it, so that you can see the actual commands being run.

For brevity, I will not include the `!echo '...' &&` part in the explanations below.

## Updating your .gitconfig file

To add these aliases to your global `.gitconfig` file, you can either:

1. Use the `git config` command to add them one by one.
   - For example, to add the `ac` alias, you can run the following command in your terminal:

      ```shell
      git config --global alias.ac '!echo "git add -A , git commit -m" && git add -A && git commit -m'
      ```

1. To mass edit, open the file in a text editor and add them manually.
   - To open the file in the default editor, use the following command:

      ```shell
      git config edit --global
      ```

## Individual aliases in detail

```shell
alias = git config --get-regexp ^alias\\.
```

Typing `git alias` will show you all the aliases you have set up in your `.gitconfig` file.

---

```shell
ac = git add -A && git commit -m
```

Typing `git ac "The commit message"` will add all changes to the staging area and commit them with the provided message.

---

```shell
b = git branch
```

Typing `git b` will show you all the branches in your local repository.
This alias just saves a few keystrokes.

---

```shell
browse = start `git config remote.origin.url`
```

Typing `git browse` will open the remote repository in your default web browser.

__Tip:__ Use this to open the repository in GitHub, Azure DevOps, Bitbucket, etc. to create pull requests after committing changes to a branch and pushing them up, if you prefer the PR creation web experience to the IDE experience.

__Note:__ `start` is a Windows command to open a file or URL in the default application.
You may need to adjust this for the Linux/Mac equivalent, which is may be `open` for Mac and `xdg-open` for Linux.

---

```shell
co = git checkout
```

Typing `git co branch_name` will switch to the specified branch.
This alias just saves a few keystrokes.

---

```shell
commit-empty = git commit --allow-empty -m \"chore: Empty commit to re-trigger build\"
```

Typing `git commit-empty` will create an empty commit with the message `chore: Empty commit to re-trigger build`.
This is useful for re-triggering builds in CI/CD pipelines without making any code changes.

---

```shell
delete-local-branches-already-merged-in-remote = git branch --merged | egrep -i -v '(main|master|develop|dev|staging|release)' | xargs -r git branch -d
```

Typing `git delete-local-branches-already-merged-in-remote` will delete all local branches that have already been merged into the remote repository, except for the specified branches (main, master, develop, dev, staging, release).
This is useful for cleaning up your local branches after merging pull requests.

__Tip:__ Change the branches in the `egrep` command to match your own branch names that you never want it to delete.

__Tip:__ If you don't like the long verbose alias name, give it a new one! 😊

---

```shell
delete-local-branches-already-merged-in-remote-what-if = git branch --merged | egrep -i -v '(main|master|develop|dev|staging|release)'
```

Typing `git delete-local-branches-already-merged-in-remote-what-if` will show you all local branches that would be deleted if you ran the `delete-local-branches-already-merged-in-remote` alias command.

---

```shell
delete-local-tags = git tag -l | xargs git tag -d && git fetch --tags
```

Typing `git delete-local-tags` will delete all local tags and fetch the latest tags from the remote repository, ensuring that your local tags exactly match the remote's.

---

```shell
delete-stale-remote-tracking-branches-from-local = git remote prune origin
```

Typing `git delete-stale-remote-tracking-branches-from-local` will delete any stale remote-tracking branches from your local repository.
That is, if a branch has been deleted from the remote repository, this will remove it from your local repository as well if needed.

---

```shell
ge = GitExtensions .
```

Typing `git ge` will open the GitExtensions GUI for the current repository.
This requires having [GitExtensions](https://gitextensions.github.io/) installed on your machine.

I use this when I'm not in an IDE, like VS Code, and want to view the history of branches and view their commits and diffs.

__Tip:__ If you prefer a different git tool over GitExtensions, you may be able to replace `GitExtensions` with the name of your preferred tool.

---

```shell
gec = GitExtensions commit
```

Typing `git gec` will open the GitExtensions GUI for committing changes in the current repository.
Again, this requires having [GitExtensions](https://gitextensions.github.io/) installed on your machine.

I use this when I'm in the terminal and want a more rich and easy diff, stage, and commit experience than the command line provides.

__Tip:__ Again, if you prefer a different git tool over GitExtensions, you may be able to use it if it accepts command line arguments.

---

```shell
gtfo = git reset --hard && git clean -xfd
```

Typing `git gtfo` will reset your current branch to the last commit, deleting any uncommitted changes and removing all untracked files and directories.

---

```shell
history = git log --oneline --graph --decorate --all
```

Typing `git history` will show you a more compact representation of the commit history for all branches in your repository.

Here is a sample output of the `git history` alias:

![Git history alias output](/assets/Posts/2025-04-02-My-favourite-git-aliases/git-history-alias-command-output.png)

Compare that to the default `git log` output:

![Git log output](/assets/Posts/2025-04-02-My-favourite-git-aliases/git-log-default-command-output.png)

---

```shell
s = git status
```

Typing `git s` will show you the status of your current branch, including any uncommitted changes and the current branch name.
This alias just saves a few keystrokes.

---

```shell
pushf = git push --force-with-lease
```

Typing `git pushf` will force push your changes to the remote repository, with a lease.
This is useful when you need to overwrite the remote branch with your local changes, but you want to ensure that you don't accidentally overwrite someone else's changes by forgetting to add `--force-with-lease`.

---

```shell
pushnew = git push --set-upstream origin `git symbolic-ref --short HEAD`
```

Typing `git pushnew` will push your current branch to the remote repository and set the upstream branch to the same name.
This is useful when you create a new branch locally and want to push it to the remote repository for the first time, and aren't using an IDE that does this for you.
This is similar to the `git push --set-upstream origin branch_name` command, but it automatically uses the name of the current branch.

---

```shell
stashall = git stash push --include-untracked
```

Typing `git stashall` will stash all changes in your working directory, including untracked files.
This is useful when you want to temporarily save your changes without committing them, and you want to include untracked files as well.

## Why aren't you using PowerShell commands in the alias?

I'm a huge proponent of PowerShell, so you may be wondering why I'm using unix commands like `xargs` and `egrep` in my aliases.
The main reason is that PowerShell is not installed by default on Linux and Mac.
Git Bash is installed with Git though, so I believe the aliases should work on all platforms and in non-PowerShell shells.

## Conclusion

I use these aliases to make my life easier when working with Git on the command line.
Hopefully you've found some of them useful, or they've inspired you to create new ones not listed here.
Feel free to customize these aliases to fit your preferences.

If you have suggestions for other aliases, please leave a comment below!
If you found this post helpful, consider sharing it with friends and colleagues.

Happy coding!
