---
title: "Easily get output and logs from users you help remotely"
permalink: /Easily-get-output-and-logs-from-users-you-help-remotely/
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

There are many times when you need to help someone with a technical issue remotely.
Maybe it's a coworker or friend, and you're helping them over the phone, Slack, or email.
You ask them to run a command and send you the output, but they're not sure how.
You have a few options.

## Video call

If you have the ability to do a synchronous video call, this is often the easiest way to help someone remotely.
You can see their screen and guide them through the process of running commands and seeing the output.

You can use the screen sharing feature in a video conferencing tool like Zoom, Microsoft Teams, Slack, or Discord.

If the user will need to reboot their PC as part of the process, you may prefer to have them video call you from their phone instead of their PC, using an app like FaceTime, WhatsApp, Google Meet, or Zoom.

We often help others asynchronously though, such as through chat or email, so screen sharing and video calls aren't always an option.

## Screenshot

Use the <kbd>PrtSc</kbd> (or <kbd>Print Screen</kbd>) key to capture the whole screen, or <kbd>Alt</kbd> + <kbd>PrtSc</kbd> to capture just the active window.
This will copy the screenshot to the clipboard, which they can then paste into a Slack or Teams message or email.

## Video

If the issue is more complex, you might want to ask them to record a video of their screen while they reproduce the issue.

## Terminal commands

If you just need the output of a command, you can ask them to run the command and copy the output to the clipboard or save it to a file.

When in the PowerShell terminal:

- Append `| clip` to the end of the command to copy the output to the clipboard, which they can then paste into a message to you.
  - e.g. `Get-Process | clip` or `dsregcmd /status | clip`
  - `clip` is the alias for `Set-Clipboard`, which is a built-in PowerShell cmdlet that copies input to the clipboard.
- Use `Out-File` to save the output to a text file, which they can then send to you.
  - e.g. `Get-Process | Out-File -FilePath C:\temp\output.txt`
  - This is especially useful if the output is very long and would be difficult to read in a message or screenshot.
  - The shorthand for `Out-File` is `>`, so you can also do `Get-Process > C:\temp\output.txt`
    - This alias works in other shells too, like Command Prompt and Bash, so it's a good one to know.
  - One downside is that the output is not written to the terminal; it is only written to the file.
- Use `Tee-Object` to see the output in the terminal and save it to a file.
  - e.g. `Get-Process | Tee-Object -FilePath C:\temp\output.txt`
  - This will write the output to both the terminal and the file.
  - The shorthand for `Tee-Object` is `Tee`, so you can also do `Get-Process | Tee C:\temp\output.txt`





Try to include at least one image, as it makes preview links more appealing since the first image often gets used as the preview image:

![Example image](/assets/Posts/2026-02-24-Easily-get-output-and-logs-from-users-you-help-remotely/image-name.png)
