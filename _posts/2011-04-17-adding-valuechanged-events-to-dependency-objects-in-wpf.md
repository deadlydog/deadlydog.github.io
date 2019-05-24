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
You may be wondering which is the best way to hookup a DependencyProperty&#8217;s Callback event handler to handle Value Changed events.&#160; The two methods to consider are:

Method 1 &#8211; Use static event handlers, like so:

<div style="color:black;background-color:white;">
  <pre><span style="color:blue;">public</span> <span style="color:blue;">virtual</span> <span style="color:blue;">int</span> SelectedID
{
	<span style="color:blue;">get</span> { <span style="color:blue;">return</span> (<span style="color:blue;">int</span>)GetValue(SelectedIDProperty); }
	<span style="color:blue;">set</span> { SetValue(SelectedIDProperty, value); }
}

<span style="color:blue;">public</span> <span style="color:blue;">static</span> <span style="color:blue;">readonly</span> DependencyProperty SelectedIDProperty =
	DependencyProperty.Register(<span>"SelectedID"</span>, <span style="color:blue;">typeof</span>(<span style="color:blue;">int</span>), <span style="color:blue;">typeof</span>(SelectorBase),
<span style="font-size:11pt;font-family:&#039;color:blue;line-height:115%;"><span>&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160; </span></span><span style="color:blue;">new</span> PropertyMetadata(0, <span style="color:blue;">new</span> PropertyChangedCallback(OnSelectedIDChanged)));

<span style="color:blue;">private</span> <span style="color:blue;">static</span> <span style="color:blue;">void</span> OnSelectedIDChanged(DependencyObject d, DependencyPropertyChangedEventArgs e)
{
	<span style="color:green;">// Perform event handler logic</span>
}</pre>
</div>

&#160;

Method 2 &#8211; Hookup event handler at initialize, and remove it during Dispose(), like so:

<div style="color:black;background-color:white;">
  <pre><span style="color:green;">// Constructor</span>
<span style="color:blue;">public</span> SelectorBase()
{
	HookupEventHandlers();
}

<span style="color:blue;">private</span> <span style="color:blue;">void</span> HookupEventHandlers()
{
	TypeDescriptor.GetProperties(<span style="color:blue;">this</span>)[<span>"SelectedID"</span>].AddValueChanged(<span style="color:blue;">this</span>, SelectedID_ValueChanged);
}

<span style="color:blue;">private</span> <span style="color:blue;">void</span> RemoveEventHandlers()
{
	TypeDescriptor.GetProperties(<span style="color:blue;">this</span>)[<span>"SelectedID"</span>].RemoveValueChanged(<span style="color:blue;">this</span>, SelectedID_ValueChanged);
}

<span style="color:blue;">protected</span> <span style="color:blue;">override</span> <span style="color:blue;">void</span> Dispose(<span style="color:blue;">bool</span> isDisposing)
{
	<span style="color:blue;">base</span>.Dispose(isDisposing);
	<span style="color:green;">// If managed resources should be released</span>
	<span style="color:blue;">if</span> (isDisposing)
	{
		RemoveEventHandlers();
	}
}

<span style="color:blue;">public</span> <span style="color:blue;">virtual</span> <span style="color:blue;">int</span> SelectedID
{
	<span style="color:blue;">get</span> { <span style="color:blue;">return</span> (<span style="color:blue;">int</span>)GetValue(SelectedIDProperty); }
	<span style="color:blue;">set</span> { SetValue(SelectedIDProperty, value); }
}

<span style="color:blue;">public</span> <span style="color:blue;">static</span> <span style="color:blue;">readonly</span> DependencyProperty SelectedIDProperty =
	DependencyProperty.Register(<span>"SelectedID"</span>, <span style="color:blue;">typeof</span>(<span style="color:blue;">int</span>), <span style="color:blue;">typeof</span>(SelectorBase), <span style="color:blue;">new</span> PropertyMetadata(0));

<span style="color:blue;">private</span> <span style="color:blue;">void</span> SelectedID_ValueChanged(<span style="color:blue;">object</span> sender, EventArgs e)
{
	<span style="color:green;">// Perform event handler logic</span>
}</pre>
</div>

So the advantage to using method 1 is that we have access to the property&#8217;s old and new values, we <a href="http://social.msdn.microsoft.com/Forums/en-US/wpf/thread/6f18c879-6ea4-4473-b316-30c4fd5f43b5" target="_blank">don&#8217;t have to worry about memory leaks</a> (since the event handler is static), and if we create 100 instances of the control we still only have one static event handler in memory, instead of 100 local ones.&#160; The disadvantage to method 1 is that these event handlers are going to exist in memory for the entire lifetime of the app, even if the view/control they are on is never referenced.

The advantage to using method 2 is that the event handlers only exist in memory if the view/control they are on is actually open.&#160; The disadvantage is that we don&#8217;t have access to the property&#8217;s old value, and the developer has to remember to properly unhook the event in order to avoid a memory leak.

So method 1 is best suited to items that are used in many places (such as custom controls that may be plastered all of the place), while method 2 is best suited for views where there is likely to never be more than a few instances open at any given time, or in places that may not be accessed at all (e.g. a settings menu that is rarely accessed).
