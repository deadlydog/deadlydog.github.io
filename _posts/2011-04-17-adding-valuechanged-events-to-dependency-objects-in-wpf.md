---
id: 19
title: Adding ValueChanged events to Dependency Objects in WPF
date: 2011-04-17T22:56:00-06:00
guid: https://deadlydog.wordpress.com/?p=19
permalink: /adding-valuechanged-events-to-dependency-objects-in-wpf/
jabber_published:
  - "1353106547"
categories:
  - WPF
tags:
  - C#
  - Dependency Object
  - memory leak
  - Value Changed
  - ValueChanged
  - WPF
---

You may be wondering which is the best way to hookup a DependencyProperty's Callback event handler to handle Value Changed events. The two methods to consider are:

Method 1 - Use static event handlers, like so:

```csharp
public virtual int SelectedID
{
	get { return (int)GetValue(SelectedIDProperty); }
	set { SetValue(SelectedIDProperty, value); }
}

public static readonly DependencyProperty SelectedIDProperty =
	DependencyProperty.Register("SelectedID", typeof(int), typeof(SelectorBase),
		new PropertyMetadata(0, new PropertyChangedCallback(OnSelectedIDChanged)));

private static void OnSelectedIDChanged(DependencyObject d, DependencyPropertyChangedEventArgs e)
{
	// Perform event handler logic
}
```

Method 2 - Hookup event handler at initialize, and remove it during Dispose(), like so:

```csharp
// Constructor
public SelectorBase()
{
	HookupEventHandlers();
}

private void HookupEventHandlers()
{
	TypeDescriptor.GetProperties(this)["SelectedID"].AddValueChanged(this, SelectedID_ValueChanged);
}

private void RemoveEventHandlers()
{
	TypeDescriptor.GetProperties(this)["SelectedID"].RemoveValueChanged(this, SelectedID_ValueChanged);
}

protected override void Dispose(bool isDisposing)
{
	base.Dispose(isDisposing);
	// If managed resources should be released
	if (isDisposing)
	{
		RemoveEventHandlers();
	}
}

public virtual int SelectedID
{
	get { return (int)GetValue(SelectedIDProperty); }
	set { SetValue(SelectedIDProperty, value); }
}

public static readonly DependencyProperty SelectedIDProperty =
	DependencyProperty.Register("SelectedID", typeof(int), typeof(SelectorBase), new PropertyMetadata(0));

private void SelectedID_ValueChanged(object sender, EventArgs e)
{
	// Perform event handler logic
}
```

So the advantage to using method 1 is that we have access to the property's old and new values, we [don't have to worry about memory leaks](http://social.msdn.microsoft.com/Forums/en-US/wpf/thread/6f18c879-6ea4-4473-b316-30c4fd5f43b5) (since the event handler is static), and if we create 100 instances of the control we still only have one static event handler in memory, instead of 100 local ones. The disadvantage to method 1 is that these event handlers are going to exist in memory for the entire lifetime of the app, even if the view/control they are on is never referenced.

The advantage to using method 2 is that the event handlers only exist in memory if the view/control they are on is actually open. The disadvantage is that we don't have access to the property's old value, and the developer has to remember to properly unhook the event in order to avoid a memory leak.

So method 1 is best suited to items that are used in many places (such as custom controls that may be plastered all of the place), while method 2 is best suited for views where there is likely to never be more than a few instances open at any given time, or in places that may not be accessed at all (e.g. a settings menu that is rarely accessed).
