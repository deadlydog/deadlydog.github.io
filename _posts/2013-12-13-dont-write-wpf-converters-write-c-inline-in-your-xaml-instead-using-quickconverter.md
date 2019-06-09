---
title: Don\'t Write WPF Converters; Write C# Inline In Your XAML Instead Using QuickConverter
date: 2013-12-13T15:09:10-06:00
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

## A simple inverse boolean converter example

As a simple example, let’s do an inverse boolean converter; something that is so basic I’m surprised that it is still not included out of the box with Visual Studio (and why packages like [WPF Converters](https://wpfconverters.codeplex.com/) exist). So basically if the property we are binding to is true, we want it to return false, and if it’s false, we want it to return true.

### The traditional approach

[This post shows the code](http://www.codeproject.com/Articles/24330/WPF-Bind-to-Opposite-Boolean-Value-Using-a-Convert) for how you would traditionally accomplish this. Basically you:

1. add a new file to your project to hold your new converter class,
1. have the class implement IValueConverter,
1. add the class as a resource in your xaml file, and then finally
1. use it in the Converter property of the xaml control. Man, that is a lot of work to flip a bit!

Just for reference, this is what step 4 might look like in the xaml:

```csharp
<CheckBox IsEnabled="{Binding Path=ViewModel.SomeBooleanProperty, Converter={StaticResource InverseBooleanConverter}" />
```

#### Using QuickConverter

This is what you would do using QuickConverter:

```csharp
<CheckBox IsEnabled="{qc:Binding '!$P', P={Binding Path=ViewModel.SomeBooleanProperty}}" />
```

That's it! 1 step! How freaking cool is that! Basically we bind our SomeBooleanProperty to the variable $P, and then write our C# expressions against `$P`, all in xaml! This also allows us to skip steps 1, 2, and 3 of the traditional approach, allowing you to get more done.

### More examples using QuickConverter

The [QuickConverter documentation page](https://quickconverter.codeplex.com/documentation) shows many more examples, such as a Visibility converter:

```csharp
Visibility="{qc:Binding '$P ? Visibility.Visible : Visibility.Collapsed', P={Binding ShowElement}}"
```

Doing a null check:

```csharp
IsEnabled="{qc:Binding '$P != null', P={Binding Path=SomeProperty}"
```

Checking a class instance’s property values:

```csharp
IsEnabled="{qc:Binding '$P.IsValid || $P.ForceAlways', P={Binding Path=SomeClassInstance}"
```

Doing two-way binding:

```csharp
Height="{qc:Binding '$P * 10', ConvertBack='$value * 0.1', P={Binding TestWidth, Mode=TwoWay}}"
```

Doing Multi-binding:

```csharp
Angle="{qc:MultiBinding 'Math.Atan2($P0, $P1) * 180 / 3.14159', P0={Binding ActualHeight, ElementName=rootElement}, P1={Binding ActualWidth, ElementName=rootElement}}"
```

Declaring and using local variables in your converter expression:

```csharp
IsEnabled="{qc:Binding '(Loc = $P.Value, A = $P.Show) => $Loc != null &amp;&amp; $A', P={Binding Obj}}"
```

* Note that the "&&" operator must be written as `&amp;&amp;` in XML.

And there is even limited support for using lambdas, which allows LINQ to be used:

```csharp
ItemsSource="{qc:Binding '$P.Where(( (int)i ) => (bool)($i % 2 == 0))', P={Binding Source}}"
```

### Quick Converter Setup

As mentioned above, [Quick Converter](https://quickconverter.codeplex.com/) is [available via NuGet](http://www.nuget.org/packages/QuickConverter/). Once you have it installed in your project, there are 2 things you need to do:

#### 1. Register assemblies for the types that you plan to use in your quick converters

For example, if you want to use the Visibility converter shown above, you need to register the System.Windows assembly, since that is where the System.Windows.Visibility enum being referenced lives. You can register the System.Windows assembly with QuickConverter using this line:

```csharp
QuickConverter.EquationTokenizer.AddNamespace(typeof(System.Windows.Visibility));
```

In order to avoid a XamlParseException at run-time, this line needs to be executed before the quick converter executes. To make this easy, I just register all of the assemblies with QuickConverter in my application’s constructor. That way I know they have been registered before any quick converter expressions are evaluated.

So my App.xaml.cs file contains this:

```csharp
public partial class App : Application
{
    public App() : base()
    {
        // Setup Quick Converter.
        QuickConverter.EquationTokenizer.AddNamespace(typeof(object));
        QuickConverter.EquationTokenizer.AddNamespace(typeof(System.Windows.Visibility));
    }
}
```

Here I also registered the System assembly (using "typeof(object)") in order to make the primitive types (like bool) available.

#### 2. Add the QuickConverter namespace to your Xaml files

As with all controls in xaml, before you can use a you a control you must create a reference to the namespace that the control is in. So to be able to access and use QuickConverter in your xaml file, you must include it’s namespace, which can be done using:

```csharp
xmlns:qc="clr-namespace:QuickConverter;assembly=QuickConverter"
```

### So should I go delete all my existing converters?

As crazy awesome as QuickConverter is, it’s not a complete replacement for converters. Here are a few scenarios where you would likely want to stick with traditional converters:

1. You need some very complex logic that is simply easier to write using a traditional converter. For example, we have some converters that access our application cache and lock resources and do a lot of other logic, where it would be tough (impossible?) to write all of that logic inline with QuickConverter. Also, by writing it using the traditional approach you get things like VS intellisense and compile-time error checking.
1. If the converter logic that you are writing is very complex, you may want it enclosed in a converter class to make it more easily reusable; this allows for a single reusable object and avoids copy-pasting complex logic all over the place. Perhaps the first time you write it you might do it as a QuickConverter, but if you find yourself copy-pasting that complex logic a lot, move it into a traditional converter.
1. If you need to debug your converter, that can’t be done with QuickConverter (yet?).

### Summary

So QuickConverter is super useful and can help speed up development time by allowing most, if not all, of your converters to be written inline. In my experience 95% of converters are doing very simple things (null checks, to strings, adapting one value type to another, etc.), which are easy to implement inline. This means fewer files and classes cluttering up your projects. If you need to do complex logic or debug your converters though, then you may want to use traditional converters for those few cases.

So, writing C# inline in your xaml; how cool is that! I can’t believe Microsoft didn’t think of and implement this. One of the hardest things to believe is that Johannes Moersch came up with this idea and implemented it while on a co-op work term in my office! A CO-OP STUDENT WROTE QUICKCONVERTER! Obviously Johannes is a very smart guy, and he’s no longer a co-op student; he’ll be finishing up his bachelor’s degree in the coming months.

I hope you find QuickConverter as helpful as I have, and if you have any suggestions for improvements, [be sure to leave Johannes a comment on the CodePlex page](https://quickconverter.codeplex.com/discussions).

Happy coding!
