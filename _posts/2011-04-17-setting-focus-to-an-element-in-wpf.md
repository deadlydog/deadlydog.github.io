---
title: Setting focus to an element in WPF
date: 2011-04-17T22:44:00-06:00
permalink: /setting-focus-to-an-element-in-wpf/
categories:
  - WPF
tags:
  - C#
  - focus
  - setting focus
  - snoop
  - WPF
---

So if you are trying to set focus to a WPF element, but are unable to. Here is a quick checklist to go through:

- is the control you are trying to set focus to `Enabled`, `Visible`, `Loaded`, and `Focusable`. If any of these properties are false, you cannot set focus to the element.

If you are using Binding to set these properties, make sure the binding is firing before you are trying to set the focus.

- does the control that already has focus allow focus to be taken away? If the control that currently has focus overrides the `PreviewLostFocus` event, it can set `e.Handled` to true to prevent other controls from stealing focus from it.

If all of these conditions seem to be met, but you still cannot seem to set the focus to a control, is another operation moving focus from your control to another control after you set focus to your control? From a dispatcher perhaps?

Using the tool [Snoop](http://snoopwpf.codeplex.com/) is great for viewing what control in your WPF application has focus, and is very useful in debugging for seeing what controls are receiving focus. Once you know what control is getting focus instead of your control, you can put a breakpoint in the control's GotFocus event to debug and see when focus is being moved to the control, and what function is performing the operation to move the focus.
