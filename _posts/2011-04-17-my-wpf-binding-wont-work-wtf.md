---
id: 16
title: 'My WPF Binding won&#8217;t work. WTF!'
date: 2011-04-17T22:48:00-06:00
guid: https://deadlydog.wordpress.com/?p=16
permalink: /my-wpf-binding-wont-work-wtf/
jabber_published:
  - "1353106119"
categories:
  - Binding
  - WPF
tags:
  - Binding
  - C#
  - WPF
  - XAML
---
At one point or another I&#8217;m sure we&#8217;ve all been confused as to why our binding won&#8217;t work. Here&#8217;s a couple things to keep in mind:

&#8211; have you set the DataContext to be the class containing the property you are binding to? This can be done in XAML or in the code-behind. For example, put "this.DataContext = this" in the code-behind file&#8217;s constructor.

&#8211; is the property you&#8217;re binding to public? Also, it must be a property (with get; set; accessors), not a field (i.e. class variable).

&#8211; if you are using two way binding, do you have both a Get and Set accessor on the propery?

&#8211; if you are trying to set bindings on a usercontrol that you are placing on your form, you may have to set the DataContext in that control, or reference the property dynamically using something like:

<p class="MsoNormal" style="margin-bottom:0;line-height:normal;">
  <span style="font-size:10pt;font-family:consolas;color:blue;"><</span><span style="font-size:10pt;font-family:consolas;">controls<span style="color:blue;">:</span>CashCalculator</span>
</p>

<p class="MsoNormal" style="margin-bottom:0;line-height:normal;">
  <span style="font-size:10pt;font-family:consolas;"><span> </span><span style="color:red;">CanModifyAmount</span><span style="color:blue;">="{</span>Binding<span style="color:red;"> RelativeSource</span><span style="color:blue;">={</span>RelativeSource<span style="color:red;"> FindAncestor</span><span style="color:blue;">,</span><span style="color:red;"> AncestorType</span><span style="color:blue;">={</span>x<span style="color:blue;">:</span>Type<span style="color:red;"> src</span><span style="color:blue;">:</span><span style="color:red;">WpfViewBase</span><span style="color:blue;">}},</span><span style="color:red;"> </span></span>
</p>

<p class="MsoNormal" style="margin-bottom:0;line-height:normal;">
  <span style="font-size:10pt;font-family:consolas;color:red;">Path</span><span style="font-size:10pt;font-family:consolas;color:blue;">=CashOutViewModel.CanModifyAmount,</span><span style="font-size:10pt;font-family:consolas;color:red;"> Mode</span><span style="font-size:10pt;font-family:consolas;color:blue;">=OneWay}"</span>
</p>

<p class="MsoNormal" style="margin-bottom:0;line-height:normal;">
  <span style="font-size:10pt;font-family:consolas;"><span> </span><span style="color:red;">CashDetail</span><span style="color:blue;">="{</span>Binding<span style="color:red;"> RelativeSource</span><span style="color:blue;">={</span>RelativeSource<span style="color:red;"> FindAncestor</span><span style="color:blue;">,</span><span style="color:red;"> AncestorType</span><span style="color:blue;">={</span>x<span style="color:blue;">:</span>Type<span style="color:red;"> src</span><span style="color:blue;">:</span><span style="color:red;">WpfViewBase</span><span style="color:blue;">}},</span><span style="color:red;"> </span></span>
</p>

<p class="MsoNormal" style="margin-bottom:0;line-height:normal;">
  <span style="font-size:10pt;font-family:consolas;color:red;">Path</span><span style="font-size:10pt;font-family:consolas;color:blue;">=CashOutViewModel.CashOutModel.CashOut.CashDetail,</span><span style="font-size:10pt;font-family:consolas;color:red;"> Mode</span><span style="font-size:10pt;font-family:consolas;color:blue;">=OneWay}"/></span>
</p>

If you still don&#8217;t know why your binding isn&#8217;t working, be sure to check the Output window in Visual Studio while debugging, as it will display any binding errors that occur and (hopefully) give you more information about the problem. You can also change the level of verbosity that is displayed for Binding errors, so if you aren&#8217;t getting enough information, in Visual Studio go into Tools -> Options -> Debugging -> Output Window and make sure Data Binding is at least set to Warning.

If changes made to the UI propogate to the code-behind, but changes made in the code-behind don&#8217;t propogate to the UI, you will either need to use the [INotifyPropertyChanged](http://msdn.microsoft.com/en-us/library/system.componentmodel.inotifypropertychanged.aspx#Y228) pattern, or implement your code-behind properties as [DependencyProperties](http://msdn.microsoft.com/en-us/library/ms752914.aspx), in order to let the UI know that the value has been updated and show the change.
