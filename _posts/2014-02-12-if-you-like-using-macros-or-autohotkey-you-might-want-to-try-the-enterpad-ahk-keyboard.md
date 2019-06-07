---
id: 720
title: If You Like Using Macros or AutoHotkey, You Might Want To Try The Enterpad AHK Keyboard
date: 2014-02-12T21:24:09-06:00
guid: http://dans-blog.azurewebsites.net/?p=720
permalink: /if-you-like-using-macros-or-autohotkey-you-might-want-to-try-the-enterpad-ahk-keyboard/
categories:
  - AutoHotkey
tags:
  - AHK
  - AutoHotkey
  - Keyboard
  - Macro
---

If you follow my blog then you already know I'm a huge fan of [AutoHotkey](http://www.autohotkey.com) (AHK), and that I created the [AHK Command Picker](http://ahkcommandpicker.codeplex.com) to allow me to have a limitless number of AHK macros quickly and easily accessible from my keyboard, without having a bunch of hotkeys (i.e. keyboard shortcuts) to remember. The team over at CEDEQ saw my blog posts and were kind enough to send me an [Enterpad AHK Keyboard](http://cedeq.com/enterpad/en/autohotkey/) for free :-)

## What is the Enterpad AHK Keyboard?

The Enterpad AHK keyboard is a physical device with 120 different touch spots on it, each of which can be used to trigger a different AHK macro/script. Here's a picture of it:

![Enterpad Keyboard](/assets/Posts/2014/02/enterpad_application_desktop_english_e2.jpg)

While macro keyboards/controllers are nothing new, there are a number of things that separate the Enterpad AHK keyboard from your typical macro keyboard:

1. The touch spots are not physical buttons; instead it uses a simple flat surface with 120 different positions that respond to touch. Think of it almost as a touch screen, but instead of having a screen to touch, you just touch a piece of paper.
1. This leads to my next point, which is that you can use any overlay you want on the surface of Enterpad AHK keyboard; the overlay is just a piece of paper. The default overlay (piece of paper) that it ships with just has 120 squares on it, each labeled with their number (as shown in the picture above). Because the overlay is just a piece of paper, you can write (or draw) on it, allowing you to create custom labels for each of your 120 buttons; something that you can't do with physical buttons. So what if you add or remap your macros after a month or a year? Just erase and re-write your labels (if you wrote them in pencil), or simply print off a new overlay. Also, you don't need to have 120 different buttons; if you only require 12, then you could map 10 buttons to each one of the 12 commands you have, allowing for a larger touch spot to launch a specific script.
1. It integrates directly with AHK. This means that you can easily write your macros/scripts in an awesome language that you (probably) already know. While you could technically have any old macro keyboard launch AHK scripts, it would mean mapping a keyboard shortcut for each script that you want to launch, which means cluttering up your keyboard shortcuts and potentially running them unintentionally. With the Enterpad AHK keyboard, AHK simply sees the 120 touch spots as an additional 120 keys on your keyboard, so you don't have to clutter up your real keyboard's hotkeys. Here is an example of a macro that displays a message box when the first touch spot is pressed:

```csharp
001:
MsgBox, "You pressed touch spot #1."
Return
```

## What do you mean when you say use it to launch a macro or script?

A macro or script is just a series of operations; basically they can be used to do ANYTHING that you can manually do on your computer. So some examples of things you can do are:

* Open an application or file.
* Type specific text (such as your home address).
* Click on specific buttons or areas of a window.

For example, you could have a script that opens Notepad, types "This text was written by an AHK script.", saves the file to the desktop, and then closes Notepad. Macros are useful for automating things that you do repeatedly, such as visiting specific websites, entering usernames and passwords, typing out canned responses to emails, and much more.

The AHK community is very large and very active. You can find a script to do almost anything you want, and when you can't (or if you need to customize an existing script) you are very likely to get answers to any questions that you post online. The Enterpad team also has [a bunch of general purpose scripts/examples available for you to use](http://cedeq.com/enterpad/en/autohotkey/useful-ahk-macros/), such having 10 custom clipboards, where button 1 copies to a custom clipboard, and button 11 pastes from it, button 2 copies to a different custom clipboard, and button 12 pastes from it, etc..

## Why would I want the Enterpad AHK Keyboard?

If you are a fan of AutoHotkey and would like a separate physical device to launch your macros/scripts, the Enterpad AHK Keyboard is definitely a great choice. If you don't want a separate physical device, be sure to check out [AHK Command Picker](http://ahkcommandpicker.codeplex.com), as it provides many of the same benefits without requiring a new piece of hardware.

### Some reasons you might want an Enterpad AHK Keyboard

* You use (or want to learn) AutoHotkey and prefer a separate physical device to launch your scripts.
* You want to be able to launch your scripts with a single button.
* You don't want to clutter up your keyboard shortcuts.
* You want to be able to label all of your hotkeys.

### Some reasons you may want a different macro keyboard

* It does not use physical buttons. This is great for some situations, but not for others. For example, if you are a gamer looking for a macro keyboard then you might prefer one with physical buttons so that you do not have to look away from the screen to be sure about which button you are pressing. Since the overlay is just a piece of paper though, you could perhaps do something like use little pieces of sticky-tac to mark certain buttons, so you could know which button your finger is on simply by feeling it.
* The price. At nearly $300 US, the Enterpad AHK keyboard is more expensive than many other macro keyboards. That said, those keyboards also don't provide all of the benefits that the Enterpad AHK keyboard does.

Even if you don't want to use the Enterpad AHK keyboard yourself, you may want to get it for a friend or relative; especially if you have a very non-technical one. For example, you could hook it up to your grandma's computer and write a AHK script that calls your computer via Skype, and then label a button (or 10 buttons to make it nice and big) on the Enterpad AHK keyboard so it is clear which button to press in order to call you.

One market that I think the Enterpad AHK keyboard could really be useful for is the corporate world, where you have many people doing the same job, and who all follow a set of instructions to do some processing. For example, at a call center where you have tens or hundreds of employees using the same software and performing the same job. One of their duties might be for placing new orders of a product for a caller, and this may involve clicking through 10 different menus or screens in order to get to the correct place to enter the customers information. This whole process could be automated to a single button press on the Enterpad AHK keyboard. You are probably thinking that the software should be redesigned to make the process of submitting orders less cumbersome, and you are right, but most companies don't develop the software that they use, so they are at the mercy of the 3rd party software provide0; In these cases, AHK can be a real time-saver, by a company deploying an Enterpad AHK keyboard to all of its staff with a custom labeled overlay, and the IT department writing the AHK scripts that the employees use with their Enterpad AHK keyboards, all of the staff can benefit from it without needing to know anything about AHK.

## Conclusion

So should you go buy an Enterpad AHK Keyboard? That is really up to you. I have one, but find that I don't use it very often because I tend to prefer to use the AHK Command Picker software so that my fingers never leave my keyboar0; Some of my co-workers have tried it out though and really love it, so if you prefer to have a separate physical device for launching your macros then the Enterpad AHK Keyboard might be perfect for you.
