---
id: 686
title: 'Don&rsquo;t Write WPF Converters; Write C# Inline In Your XAML Instead Using QuickConverter'
date: 2013-12-13T15:09:10-06:00
guid: http://dans-blog.azurewebsites.net/?p=686
permalink: /dont-write-wpf-converters-write-c-inline-in-your-xaml-instead-using-quickconverter/
categories:
  - C#
  - WPF
  - XAML
tags:
  - C#
  - Converter
  - CSharp
  - Inline
  - Quick
  - QuickConverter
  - WPF
  - XAML
---
If you’ve used binding at all in WPF then you more then likely have also written a converter. There are [lots of](http://wpftutorial.net/ValueConverters.html) [tutorials on](http://www.wpf-tutorial.com/data-binding/value-conversion-with-ivalueconverter/) [creating converters](http://www.codeproject.com/Articles/418271/Custom-Value-Conversion-in-WPF), so I’m not going to discuss that in length here. Instead I want to spread the word about [a little known gem called QuickConverter](https://quickconverter.codeplex.com/). QuickConverter is awesome because it allows you to write C# code directly in your XAML; this means no need for creating an explicit converter class. And [it’s available on NuGet](http://www.nuget.org/packages/QuickConverter/) so it’s a snap to get it into your project.



### A simple inverse boolean converter example

As a simple example, let’s do an inverse boolean converter; something that is so basic I’m surprised that it is still not included out of the box with Visual Studio (and why packages like [WPF Converters](https://wpfconverters.codeplex.com/) exist). So basically if the property we are binding to is true, we want it to return false, and if it’s false, we want it to return true.

#### The traditional approach

[This post shows the code](http://www.codeproject.com/Articles/24330/WPF-Bind-to-Opposite-Boolean-Value-Using-a-Convert) for how you would traditionally accomplish this. Basically you:

1) add a new file to your project to hold your new converter class,

2) have the class implement IValueConverter,

3) add the class as a resource in your xaml file, and then finally

4) use it in the Converter property of the xaml control. Man, that is a lot of work to flip a bit!

Just for reference, this is what step 4 might look like in the xaml:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:dd8d182a-ba04-429f-a732-717e4f6e7c8e" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: xml; gutter: false; title: ; notranslate" title="">
<CheckBox IsEnabled="{Binding Path=ViewModel.SomeBooleanProperty, Converter={StaticResource InverseBooleanConverter}" />
</pre>
</div>

####

#### Using QuickConverter

This is what you would do using QuickConverter:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:20a4e40b-0b74-4a89-be92-6208d47a3ffd" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: xml; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
<CheckBox IsEnabled="{qc:Binding '!$P', P={Binding Path=ViewModel.SomeBooleanProperty}}" />
</pre>
</div>

That it! 1 step! How freaking cool is that! Basically we bind our SomeBooleanProperty to the variable $P, and then write our C# expressions against $P, all in xaml! This also allows us to skip steps 1, 2, and 3 of the traditional approach, allowing you to get more done.



### More examples using QuickConverter

The [QuickConverter documentation page](https://quickconverter.codeplex.com/documentation) shows many more examples, such as a Visibility converter:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:1cc1ca81-e8c3-425d-b320-27047445a102" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
Visibility="{qc:Binding '$P ? Visibility.Visible : Visibility.Collapsed', P={Binding ShowElement}}"
</pre>
</div>



Doing a null check:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:f6bf3617-9df8-4629-937c-9ac30cbff775" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
IsEnabled="{qc:Binding '$P != null', P={Binding Path=SomeProperty}"
</pre>
</div>



Checking a class instance’s property values:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:e9d8987c-3f3a-40e8-8470-d0ed50ea6686" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
IsEnabled="{qc:Binding '$P.IsValid || $P.ForceAlways', P={Binding Path=SomeClassInstance}"
</pre>
</div>



Doing two-way binding:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:6c875271-4498-484a-92d9-6bbd38608db7" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
Height="{qc:Binding '$P * 10', ConvertBack='$value * 0.1', P={Binding TestWidth, Mode=TwoWay}}"
</pre>
</div>



Doing Multi-binding:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:2a1db181-c052-4adc-a4e3-8ff89b6ada2a" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
Angle="{qc:MultiBinding 'Math.Atan2($P0, $P1) * 180 / 3.14159', P0={Binding ActualHeight, ElementName=rootElement}, P1={Binding ActualWidth, ElementName=rootElement}}"
</pre>
</div>



Declaring and using local variables in your converter expression:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:c9450039-982e-4ee6-9104-72807e12a235" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
IsEnabled="{qc:Binding '(Loc = $P.Value, A = $P.Show) => $Loc != null &amp;&amp; $A', P={Binding Obj}}"
</pre>
</div>

* Note that the "&&" operator must be written as "&&" in XML.



And there is even limited support for using lambdas, which allows LINQ to be used:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:6fd045a3-0ddb-4a89-b60b-73d43397de78" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
ItemsSource="{qc:Binding '$P.Where(( (int)i ) => (bool)($i % 2 == 0))', P={Binding Source}}"
</pre>
</div>



### Quick Converter Setup

As mentioned above, [Quick Converter](https://quickconverter.codeplex.com/) is [available via NuGet](http://www.nuget.org/packages/QuickConverter/). Once you have it installed in your project, there are 2 things you need to do:

#### 1. Register assemblies for the types that you plan to use in your quick converters

For example, if you want to use the Visibility converter shown above, you need to register the System.Windows assembly, since that is where the System.Windows.Visibility enum being referenced lives. You can register the System.Windows assembly with QuickConverter using this line:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:7c4328c4-f388-4058-a0cb-959a1e1a360a" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
QuickConverter.EquationTokenizer.AddNamespace(typeof(System.Windows.Visibility));
</pre>
</div>

In order to avoid a XamlParseException at run-time, this line needs to be executed before the quick converter executes. To make this easy, I just register all of the assemblies with QuickConverter in my application’s constructor. That way I know they have been registered before any quick converter expressions are evaluated.

So my App.xaml.cs file contains this:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:d623e3ab-1c40-48c0-ab6d-26b0edff49dc" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: csharp; title: ; notranslate" title="">
public partial class App : Application
{
	public App() : base()
	{
		// Setup Quick Converter.
		QuickConverter.EquationTokenizer.AddNamespace(typeof(object));
		QuickConverter.EquationTokenizer.AddNamespace(typeof(System.Windows.Visibility));
	}
}
</pre>
</div>

Here I also registered the System assembly (using "typeof(object)") in order to make the primitive types (like bool) available.



#### 2. Add the QuickConverter namespace to your Xaml files

As with all controls in xaml, before you can use a you a control you must create a reference to the namespace that the control is in. So to be able to access and use QuickConverter in your xaml file, you must include it’s namespace, which can be done using:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:c026a573-1d6f-4e80-b458-9330a7edcfa0" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: xml; gutter: false; title: ; notranslate" title="">
xmlns:qc="clr-namespace:QuickConverter;assembly=QuickConverter"
</pre>
</div>



### So should I go delete all my existing converters?

As crazy awesome as QuickConverter is, it’s not a complete replacement for converters. Here are a few scenarios where you would likely want to stick with traditional converters:

1. You need some very complex logic that is simply easier to write using a traditional converter. For example, we have some converters that access our application cache and lock resources and do a lot of other logic, where it would be tough (impossible?) to write all of that logic inline with QuickConverter. Also, by writing it using the traditional approach you get things like VS intellisense and compile-time error checking.

2. If the converter logic that you are writing is very complex, you may want it enclosed in a converter class to make it more easily reusable; this allows for a single reusable object and avoids copy-pasting complex logic all over the place. Perhaps the first time you write it you might do it as a QuickConverter, but if you find yourself copy-pasting that complex logic a lot, move it into a traditional converter.

3. If you need to debug your converter, that can’t be done with QuickConverter (yet?).



### Summary

So QuickConverter is super useful and can help speed up development time by allowing most, if not all, of your converters to be written inline. In my experience 95% of converters are doing very simple things (null checks, to strings, adapting one value type to another, etc.), which are easy to implement inline. This means fewer files and classes cluttering up your projects. If you need to do complex logic or debug your converters though, then you may want to use traditional converters for those few cases.

So, writing C# inline in your xaml; how cool is that! I can’t believe Microsoft didn’t think of and implement this. One of the hardest things to believe is that Johannes Moersch came up with this idea and implemented it while on a co-op work term in my office! A CO-OP STUDENT WROTE QUICKCONVERTER! Obviously Johannes is a very smart guy, and he’s no longer a co-op student; he’ll be finishing up his bachelor’s degree in the coming months.

I hope you find QuickConverter as helpful as I have, and if you have any suggestions for improvements, [be sure to leave Johannes a comment on the CodePlex page](https://quickconverter.codeplex.com/discussions).

Happy coding!
