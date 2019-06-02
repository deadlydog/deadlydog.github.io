---
id: 141
title: Have Windows Automatically Login Without Entering Your Password
date: 2012-12-04T10:44:57-06:00
guid: https://deadlydog.wordpress.com/?p=141
permalink: /have-windows-automatically-login-without-entering-your-password/
jabber_published:
  - "1354639497"
categories:
  - Windows
tags:
  - No Password
  - Password
  - Windows
---

If you are like me and don't want to have to enter your password each time Windows loads, you can have Windows start up without prompting you to enter a user name or password. The simple (and BAD) way to do this is to simply not have a password on your user account, but that's a big security risk and will allow people to easily remote desktop into your computer.

So, first set a password on your windows account if you don't already have on0; Then select `Run...` from the start menu (or use Windows Key + R to open the Run window) and type `control userpasswords2`, which will open the user accounts application.

![Run command](/assets/Posts/2012/12/image.png)

On the Users tab, clear the box for `Users must enter a user name and password to use this computer`, and click on OK. An Automatically Log On dialog box will appear; enter the user name and password for the account you want to use to automatically log into Windows. That's it.

![User Accounts window](/assets/Posts/2012/12/image1.png)

You may also want to make sure your screen saver is not set to prompt you for a password when it exits either.

![Screen Saver Settings window](/assets/Posts/2012/12/image2.png)

Now your computer is secure without getting in your way. :-)

__A word of caution about this__: ANYBODY will be able to get into your computer. This is probably fine for your home desktop PCs, but you may want to think about enabling this on your laptop, especially if you regularly take it out and about and it has sensitive information on it (ID, credit card info, saved usernames/passwords in your web browser). If you weren't using a password before anyways though, using this trick is still more secure than without it ;-)
