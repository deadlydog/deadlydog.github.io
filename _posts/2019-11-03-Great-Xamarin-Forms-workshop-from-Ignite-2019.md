---
title: "Great Xamarin.Forms workshop from Ignite 2019"
permalink: /Great-Xamarin-Forms-workshop-from-Ignite-2019/
#date: 2099-01-17T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Xamarin
  - C#
tags:
  - Xamarin
  - C#
---

I'm lucky enough to attend the Microsoft Ignite 2019 conference this year, and do a pre-conference workshop.
The workshop I chose to do was `Build your first iOS and Android app with C#, Xamarin, and Azure` presented by James Montemagno.

While I had to pay for the interactive hands-on experience, James was awesome enough to provide [the workshop for free on his GitHub repo](https://github.com/jamesmontemagno/xamarin.forms-workshop).
So if you are new to Xamarin or haven't done it in a while, be sure to check out the repo and run through the exercises.
He provides a `Start` directory for you to start from, and a `Finish` directory containing what the finished product should look like after you've ran through each exercise part.
James' also mentioned that he'll be uploading the slide deck he ran through to the GitHub repo as well.

Before starting on the workshop, you may want to run through [this Xamarin setup](https://dotnet.microsoft.com/learn/xamarin/hello-world-tutorial/intro) to get your machine configured to run the Android emulator so you can easily build and debug your Xamarin apps.

## My personal notes

In addition to the setup guide and James' workshop, here are some tidbits I took from things James said that aren't in the slides or workshop.

- [SyncFusion](https://www.syncfusion.com), Telerik, Infragistics, DevExpress, ComponentOne, and Steema for cross platform and Xamarin compatible controls.
  SyncFusion offers [a free community license](https://www.syncfusion.com/products/communitylicense).
- Use <https://app.quicktype.io/> for intelligently converting json to classes for many libraries.
- When in a .xaml file you can open the Toolbox window to see all of the available native controls to use, and drag and drop them in to get the boilerplate code for the control.
- You can use [MacInCloud](https://www.macincloud.com) to rent a mac in the cloud for building and debugging on iOS.
- [The `x:DataType` attribute](https://github.com/jamesmontemagno/xamarin.forms-workshop/tree/master/Part%201%20-%20Displaying%20Data#displaying-data-1) in XAML is what provides compile-time checking for attributes specified in the XAML bindings.
- Use [the `ContentPage.BindingContext` attribute](https://github.com/jamesmontemagno/xamarin.forms-workshop/tree/master/Part%202%20-%20MVVM%20%26%20Data%20Binding#build-the-monkeys-user-interface) to get intellisense on binding properties.
- [James' Xamarin MVVM helpers library](https://github.com/jamesmontemagno/mvvm-helpers).
- Can use `<BoxView HeightRequest="1" Color="#DDDDDD"/>` to create small horizontal line separators.
- Can use [NuGet package for circular images](https://github.com/jamesmontemagno/xamarin.forms-workshop/tree/master/Part%203%20-%20Navigation#create-detailspagexaml-ui).
- The ListView has a poor default caching strategy (for legacy reasons) [that should be set for better performance](https://github.com/jamesmontemagno/xamarin.forms-workshop/tree/master/Part%205%20-%20Pull%20To%20Refresh%20%26%20ListView%20Optimizations#caching-strategy).
- Can use the `Analytics.TrackEvent` to automatically track events to the App Center.
- Can use the `Crashes` class to see things like if the app crashed on the last run, log errors, and much more.
- Can use the `Data`class to easily store and retrieve data from your Azure Cosmos DB (still in preview, and not free yet, but they may have a free data tier of some sort when it comes out of preview).
  It does local offline caching for you for free.
- <https://github.com/xamarin/XamarinComponents> has some xamarin plugins for things like data caching and database plugins and others.

Happy coding!
