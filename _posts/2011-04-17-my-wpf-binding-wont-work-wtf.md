---
id: 16
title: My WPF Binding won't work. WTF!
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
At one point or another I'm sure we've all been confused as to why our binding won't work. Here's a couple things to keep in mind:

- have you set the `DataContext` to be the class containing the property you are binding to? This can be done in XAML or in the code-behind. For example, put "this.DataContext = this" in the code-behind file's constructor.

- is the property you're binding to public? Also, it must be a property (with `get; set;` accessors), not a field (i.e. class variable).

- if you are using two way binding, do you have both a Get and Set accessor on the property?

- if you are trying to set bindings on a user control that you are placing on your form, you may have to set the `DataContext` in that control, or reference the property dynamically using something like:

```csharp
<controls:CashCalculator
  CanModifyAmount="{Binding RelativeSource={RelativeSource FindAncestor, AncestorType={x:Type src:WpfViewBase}},
  Path=CashOutViewModel.CanModifyAmount, Mode=OneWay}"
  CashDetail="{Binding RelativeSource={RelativeSource FindAncestor, AncestorType={x:Type src:WpfViewBase}},
  Path=CashOutViewModel.CashOutModel.CashOut.CashDetail, Mode=OneWay}" />
```

If you still don't know why your binding isn't working, be sure to check the Output window in Visual Studio while debugging, as it will display any binding errors that occur and (hopefully) give you more information about the problem. You can also change the level of verbosity that is displayed for Binding errors, so if you aren't getting enough information, in Visual Studio go into Tools -> Options -> Debugging -> Output Window and make sure Data Binding is at least set to Warning.

If changes made to the UI propagate to the code-behind, but changes made in the code-behind don't propagate to the UI, you will either need to use the [INotifyPropertyChanged](http://msdn.microsoft.com/en-us/library/system.componentmodel.inotifypropertychanged.aspx#Y228) pattern, or implement your code-behind properties as [DependencyProperties](http://msdn.microsoft.com/en-us/library/ms752914.aspx), in order to let the UI know that the value has been updated and show the change.
