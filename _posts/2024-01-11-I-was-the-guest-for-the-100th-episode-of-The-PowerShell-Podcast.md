---
title: "I was the guest for the 100th episode of The PowerShell Podcast!"
permalink: /I-was-the-guest-for-the-100th-episode-of-The-PowerShell-Podcast/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - PowerShell
  - Social
tags:
  - PowerShell
  - Social
  - Podcast
---

I was recently invited to be a guest on The PowerShell Podcast ([Podbean](https://powershellpodcast.podbean.com/)) ([YouTube playlist](https://www.youtube.com/playlist?list=PL1mL90yFExsjUS8DRkzfLUcHds7vlxqgM)) ([Apple Podcasts](https://podcasts.apple.com/us/podcast/the-powershell-podcast/id1616048196)).
I had a great time talking with [Andrew Pla](https://twitter.com/AndrewPlaTech), and it was a bit surreal as I've listened to every episode.
This was my first time on a podcast, and I was very nervous.
You can tell because I was talking very fast, so hopefully I was still understandable to everyone 😅.

While I didn't realize it until a few days after recording, I was on the show's 100th episode!
[Check out the episode on YouTube here](https://youtu.be/sBpm_R1MQ38?si=sEduyCx8ONY3NfNC).
That is a huge accomplishment for the podcast, and I'm honoured to have been a part of it.
Congratulations to Andrew and the rest of the team for reaching this milestone!

I was a little bummed that I did not get to also meet [Jordan Hammond](https://twitter.com/DevOpsJordan), as he decided to take a break from the show at the end of 2023.
I missed out on meeting him by just a couple episodes.
Even though he stopped just shy of 100 episodes, it is still a great feat and he should be proud.
I hope he returns to the show in the future, even if only as a guest and to check off the "100 episodes milestone" box 😁.

## Podcast amendments

When writing blog posts and recording videos, I have time to gather my thoughts and later update any information that I left out.
While chatting live, I don't have that luxury.
After we were done recording I was soon thinking of all the things that I could have said better, or things that I left out and should have mentioned.

If I was able to edit the podcast, these are some things I would have mentioned:

### My [PowerShell tiPS module](https://github.com/deadlydog/PowerShell.tiPS)

- The tip is delivered to your PowerShell terminal.
- Provides not only a PowerShell tip, but also:
  - Example code of what the tip describes.
  - Links to external sources for more information.
  - Categorizes the tip (e.g. Performance, Security, Syntax, Terminal, Module, Community, etc.).

### Is getting a certification worth it?

- The exams can be a bit expensive, but often your employer will pay for them if you ask.
  - If you attend a conference, such as MS Build or Ignite, you can often get a free exam voucher.
- Don't get too discouraged if you have trouble passing the exam.
  Some people are great at traditional school and taking exams, others are not.
  If you are someone that struggles with traditional exams, don't let that discourage you.
  The real value is in the knowledge you gain while studying for the exam.
  Also, the MS exams in particular I've found often have at least one or two "trick" questions, where they will word the question in such a way that the answer is not obvious, even for a subject that you know very well.

### Working from home

- When working in the office you can build relationships with people you otherwise wouldn't have.
  I'm a software developer, but have built relationships with people in other departments, like IT, Support, HR, and Finance.
  Not only has this been helpful for both expected and unexpected situations (e.g. "I wonder how we bill our customers; I'll ask Heather in Finance"), but they are also great people and I'm glad to know them.
- Having a Zoom/Teams call open all day long for your team where you all have the cameras off and are muted is a great way to reproduce the informal conversations that happen in the office.
  You can unmute to ask a team member a question, and others that are on the call can chime in if they choose, or just listen in.
  If you start a screenshare, the others can choose to watch as well if they want, and may learn something new, or tell you a better way to perform the task.
  This is much more organic than scheduling a meeting, and leads to more collaboration in an low-friction way.

### DevOps, SRE, and Platform Engineering

- DevOps
  - Not only about monitoring how your application performs in production, but also how it is being used.
  This is done by adding telemetry, and can tell you things like which features are being the most used, and which ones are not used at all and can potentially be removed to reduce application complexity.
- SRE
  - Another large focus is infrastructure costs.
  You want to ensure the applications have redundancy, disaster recovery, and can perform well, but without being wasteful by overprovisioning resources and paying for a bunch of compute and storage that is not utilized.
  - Often focus on infrastructure security as well.
  This could be things like ensuring applications are only able to accept https traffic (not http), and that only up to date TLS versions are allowed.
- Platform Engineering
  - Ideally a dev team is able to just specify the git repository that contains their application code, and what type of application it is (web service, background service, cron job), and the platform will take care of the rest to ensure the application is deployed in a safe a secure manner, including an automated rollback process in case of failure.

### My favourite thing I use PowerShell for

- Anything that requires connecting to multiple remote machines to retrieve information or run commands, such as:
  - Finding IIS website certs that are expiring soon.
  - Testing web services locally to find which servers in a pool are not responding correctly.
  - Pulling down log files from servers that are not yet using distributed logging.
  - Installing or removing software or files on multiple machines.
  - Recycling IIS app pools on multiple machines.
  - Restarting servers en masse.

## Conclusion

I try to hold the content I create to a high quality standard, and am often my own worst critic.
Writing these amendments shows that I still have some growth to do in learning how to relax and let go of things that are not perfect with the content I create.
But, I just couldn't help myself 😅.

Overall I think the podcast went well and I had a great time.
Andrew was a great host, very easy to work with, and made me feel very comfortable, both before/after recording and while talking live.
He's a true pro!
I'm looking forward to doing another show in the future.
