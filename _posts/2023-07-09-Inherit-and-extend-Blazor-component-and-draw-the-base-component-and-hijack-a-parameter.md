---
title: "Inherit and extend Blazor component, draw the base component, and hijack a parameter"
permalink: /Inherit-and-extend-Blazor-component-and-draw-the-base-component-and-hijack-a-parameter/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: true
categories:
  - Blazor
  - C#
tags:
  - Blazor
  - C#
---

In this post we see how to extend a Blazor component to show additional UI elements around it, how to override some of the default property settings, and how to hijack an EventCallback property and specify a new one for component consumers to use.

## The use case

I have a lot of data grids in my Blazor app, and I always want to show a header on the grid that displays the number of rows in the grid, and buttons to export the grid to CSV and Excel.
Once I found myself copy-pasting the same code into 3 different pages, I figured it was time to refactor it into a new component.

For my specific app and in this example I'm using the [Radzen](https://blazor.radzen.com) DataGrid, but the same principles apply to any Blazor component.

[Radzen](https://github.com/radzenhq/radzen-blazor) is a great component library for Blazor.
It is free, open source, and being updated all the time.
I highly recommend it.

## Failed attempt: Contain the base component in a new component

My initial thought was to create a new component that simply contained a `RadzenDataGrid` component, and then add the header and button above that component.
So I figured the razor component code would look something like this:

```html
<div class="GridHeader">
  ... Display the header stuff here by referencing myGrid ...
</div>

<RadzenDataGrid @ref="myGrid" />

@code {
  private RadzenDataGrid<TItem> myGrid { get; set; }
  ... Figure out how to apply component parameters to myGrid ...
}
```

And then using the component would look something like this:

```html
<MyRadzenDataGridWithHeader Data="productMappings" TItem="ProductMappingDto" ... Other RadzenDataGrid properties ...>
  <Columns>
    <RadzenDataGridColumn Title="Source Product ID" Property="Source_ProductIdentifier" TItem="ProductMappingDto" />
    ... More columns ...
  </Columns>
</MyRadzenDataGridWithHeader>
```


This was problematic for a few reasons:

1. The `RadzenDataGrid` component has a generic type parameter, and I could not define a RadzenDataGrid component without providing the generic type.
   I later found the [@typeparam directive](https://docs.microsoft.com/en-us/aspnet/core/blazor/components/generic-type-support#typeparam-directive) which likely would have helped solved this problem, but I had moved on to a different solution by the time I found it.
1. The `RadzenDataGrid` component has a lot of parameters, and I didn't want to have to define all of them in my new component only to forward them to the RadzenDataGrid component.
   One option I found was to use [attribute splatting](https://learn.microsoft.com/en-us/aspnet/core/blazor/components/splat-attributes-and-arbitrary-parameters), as mentioned in [this StackOverflow answer](https://stackoverflow.com/a/73171242/602585).
   When using attribute splatting though, you lose the intellisense in the editor, so it would not have shown all of the parameters of the RadzenDataGrid component in my component.
1. The `RadzenDataGrid` expects the columns to be defined as child elements, so I would have also had to figure out a way to forward those to the RadzenDataGrid component.
   I suspect it could be done using the `ChildContent` property, as shown in [the docs](https://learn.microsoft.com/en-us/aspnet/core/blazor/components/#child-content-render-fragments), but I didn't get that far.

Before going too far down this path I found a better solution, which was to inherit the RadzenDataGrid component.

## Better solution: Inherit and extend the base component

Rather than containing a RadzenDataGrid instance within a new component, I found I could inherit the RadzenDataGrid component and add my header and buttons to it.

### Component code

I'll just drop the entire code for my new component here, and then explain what's going on piece by piece.
This is the code for my new component, `StandardRadzenDataGrid.razor`:

```csharp
@typeparam TItem
@inherits RadzenDataGrid<TItem>

<!-- Grid header with button to populate grid, show the number of rows, and buttons to export to CSV/Excel. -->
<div style="margin-bottom: 0.4rem;">
  <RadzenButton Click="RetrieveGridData" Text="@RetrieveDataButtonText" Disabled="@(IsDisabled || IsLoading)" />

  <span style="float:right;">
    @if (numberOfRecords >= 0)
    {
      <span style="margin-right: 0.5rem;">Showing @numberOfRecordsShown of @numberOfRecords records</span>
    }
    <RadzenGridExportOptions Grid="this" />
  </span>
</div>

@{
  // Display the base RadzenDataGrid that we are inheriting from.
  base.BuildRenderTree(__builder);
}

@code {
  [Parameter]
  public EventCallback<Task> RetrieveData { get; set; }

  [Parameter]
  public string RetrieveDataButtonText { get; set; } = "Retrieve Data";

  [Parameter]
  public bool IsDisabled { get; set; } = false;

  [Parameter]
  public EventCallback<DataGridColumnFilterEventArgs<TItem>> FilterApplied { get; set; }

  // By declaring this as new, and not using the Parameter attribute, we are hiding the base Filter property so it cannot be set on the component.
  // We hide the base Filter property so that we can add our own OnFilterChanged event handler to it, and not allow users to set it.
  // We instead expose a FilterApplied event that users can set on the component.
  public new EventCallback<DataGridColumnFilterEventArgs<TItem>> Filter { get; set; }

  protected override void OnInitialized()
  {
    base.OnInitialized();
    base.Filter = EventCallback.Factory.Create<DataGridColumnFilterEventArgs<TItem>>(this, OnFilterChanged);

    OverrideDefaultRadzenSettingsWithOnesWePrefer();
  }

  private void OverrideDefaultRadzenSettingsWithOnesWePrefer()
  {
    AllowFiltering = true;
    AllowSorting = true;
    AllowMultiColumnSorting = true;
    AllowColumnResize = true;
    AllowColumnReorder = true;
  }

  int numberOfRecords = -1;
  int numberOfRecordsShown = -1;

  private async Task RetrieveGridData()
  {
    try
    {
      IsLoading = true;

      await RetrieveData.InvokeAsync();

      numberOfRecords = Count;
      numberOfRecordsShown = numberOfRecords;
    }
    finally
    {
      IsLoading = false;
    }
  }

  private async Task OnFilterChanged(DataGridColumnFilterEventArgs<TItem> args)
  {
    numberOfRecordsShown = View.Count();
    await FilterApplied.InvokeAsync(args);
  }
}
```

#### Inheriting the base component

```csharp
@typeparam TItem
@inherits RadzenDataGrid<TItem>
```

The [@inherits directive](https://learn.microsoft.com/en-us/aspnet/core/blazor/components/#specify-a-base-class) allows us to inherit from the RadzenDataGrid component.
Since the RadzenDataGrid component has a generic type parameter, we need to specify that type parameter in our component as well, which is what the [@typeparam directive](https://learn.microsoft.com/en-us/aspnet/core/blazor/components/generic-type-support) is for.

The above razor code is the equivalent to doing this in C#:

```csharp
public class StandardRadzenDataGrid<TItem> : RadzenDataGrid<TItem>
```

#### Adding our own header and buttons

```csharp
<!-- Grid header with button to populate grid, show the number of rows, and buttons to export to CSV/Excel. -->
<div style="margin-bottom: 0.4rem;">
  <RadzenButton Click="RetrieveGridData" Text="@RetrieveDataButtonText" Disabled="@(IsDisabled || IsLoading)" />

  <span style="float:right;">
    @if (numberOfRecords >= 0)
    {
      <span style="margin-right: 0.5rem;">Showing @numberOfRecordsShown of @numberOfRecords records</span>
    }
    <RadzenGridExportOptions Grid="this" />
  </span>
</div>
...
@code {
  [Parameter]
  public EventCallback<Task> RetrieveData { get; set; }

  [Parameter]
  public string RetrieveDataButtonText { get; set; } = "Retrieve Data";

  [Parameter]
  public bool IsDisabled { get; set; } = false;
...
  int numberOfRecords = -1;
  int numberOfRecordsShown = -1;

  private async Task RetrieveGridData()
  {
    try
    {
      IsLoading = true;

      await RetrieveData.InvokeAsync();

      numberOfRecords = Count;
      numberOfRecordsShown = numberOfRecords;
    }
    finally
    {
      IsLoading = false;
    }
  }
```

The code above is adding the header functionality I wanted on all my grids.

```html
<RadzenButton Click="RetrieveGridData" Text="@RetrieveDataButtonText" Disabled="@(IsDisabled || IsLoading)" />
```

This line displays a button that the user will click to populate the grid with data.

The `RetrieveGridData` method will be called when the button is clicked.
The method calls a user-provided method, `RetrieveData`, which is passed in as a parameter to the component.
`RetrieveData` will do the actual work of retrieving the data and populating the grid.

The `IsDisabled` parameter allows the user to specify if the button should be disabled or not.
e.g. If the user has not provided all of the required parameters for the grid to be populated, then the button should be disabled.
We also disable it automatically while the grid is being populated, via the `IsLoading` property provided by the `RadzenDataGrid`, so that the user cannot click it again while it is still working.

```html
<span style="margin-right: 0.5rem;">Showing @numberOfRecordsShown of @numberOfRecords records</span>
```

This line displays the number of records that are currently being shown in the grid, and the total number of records that are available.
You can see that these variables are set in the `RetrieveGridData` method after the users `RetrieveData` method has been called.
Initially, all records are shown.

```html
<RadzenGridExportOptions Grid="this" />
```

This line displays the CSV and Excel export buttons for the grid.
Here I'm using the [Radzen.Blazor.GridExportOptions](https://github.com/Inspirare-LLC/Radzen.Blazor.GridExportOptions) 3rd party control.
If you do not want to use a 3rd party library or need more control over the export, [the Blazor docs](https://blazor.radzen.com/export-excel-csv) show how [the open source code](https://github.com/radzenhq/radzen-blazor/blob/master/RadzenBlazorDemos.Host/Controllers/ExportController.cs) handles the exports, which you can copy and paste into your own project.

#### Displaying the base component

```csharp
@{
  // Display the base RadzenDataGrid that we are inheriting from.
  base.BuildRenderTree(__builder);
}
```

This is how we display the base RadzenDataGrid that we are inheriting from.
We place it after the header code so that it is displayed under the header.

[The docs](https://learn.microsoft.com/en-us/aspnet/core/blazor/advanced-scenarios) say that using `BuildRenderTree` should only be used in advanced scenarios.
Here we are just displaying the base component, not changing the implementation of how it gets rendered, so it is safe to do.

#### Hiding the base Filter parameter

```csharp
  [Parameter]
  public EventCallback<DataGridColumnFilterEventArgs<TItem>> FilterApplied { get; set; }

  // By declaring this as new, and not using the Parameter attribute, we
  // are hiding the base Filter property so it cannot be set on the component.
  // We hide the base Filter property so that we can add our own OnFilterChanged
  // event handler to it, and not allow users to set it.
  // We instead expose a FilterApplied event that users can set on the component.
  public new EventCallback<DataGridColumnFilterEventArgs<TItem>> Filter { get; set; }

  protected override void OnInitialized()
  {
    base.OnInitialized();
    base.Filter = EventCallback.Factory.Create<DataGridColumnFilterEventArgs<TItem>>(this, OnFilterChanged);
    ...
  }
...
  private async Task OnFilterChanged(DataGridColumnFilterEventArgs<TItem> args)
  {
    numberOfRecordsShown = View.Count();
    await FilterApplied.InvokeAsync(args);
  }
```

The `RadzenDataGrid` has a `Filter` EventCallback property that allows you to set an event handler that will be called when the user changes the filter.
We want to hook into this event so that we can update the number of records shown in the header when the user filters the grid.
Unlike regular C# events which can handle multiple event handlers by using the `+=` operator, only one event handler can be assigned on [an EventCallback property](https://learn.microsoft.com/en-us/aspnet/core/blazor/components/event-handling?view=aspnetcore-7.0#eventcallback).
This means that we need to prevent users from setting their own event handler on the `Filter` property, and instead expose our own `FilterApplied` event that users can set.

We hide the base `Filter` property by declaring our own `Filter` property as `new`, and not using the `Parameter` attribute.
This means that the base `Filter` property cannot be set on our component.
We instead expose a `FilterApplied` EventCallback that users can set on the component.

Lastly, in the `OnInitialized` method, we set the base `Filter` property to call our own `OnFilterChanged` method when the user changes the filter, which in turn triggers the `FilterApplied` event.

#### Specifying our own default property values for the base component

```csharp
  protected override void OnInitialized()
  {
    base.OnInitialized();
    ...
    OverrideDefaultRadzenSettingsWithOnesWePrefer();
  }

  private void OverrideDefaultRadzenSettingsWithOnesWePrefer()
  {
    AllowFiltering = true;
    AllowSorting = true;
    AllowMultiColumnSorting = true;
    AllowColumnResize = true;
    AllowColumnReorder = true;
  }
```

There were a few default settings that I found myself setting on every grid, so I decided to set them as the default values for the component.

The settings I changed are in the `OverrideDefaultRadzenSettingsWithOnesWePrefer` function, which is called from the `OnInitialized` method.
This approach means I no longer need to set these parameters on every grid, but they can still be overridden using the parameters as usual when using the StandardRadzenDataGrid component if required.

### Using our new component

Now that we have our new component, we can use it in our Blazor pages.

```csharp
@page "/get-product-mappings"
@using App.ProductMappings
@inject SettingsService SettingsService

<StandardRadzenDataGrid Data="productMappings" TItem="ProductMappingDto"
        RetrieveData="RetrieveProductMappings" RetrieveDataButtonText="Retrieve Product Mappings"
        IsDisabled="@(!SettingsService.SettingsAreValid)">
  <Columns>
    <RadzenDataGridColumn Title="Source Product Name" Property="Source_ProductName" TItem="ProductMappingDto" />
    <RadzenDataGridColumn Title="Destination Product Name" Property="Destination_ProductName" TItem="ProductMappingDto" />
  </Columns>
</StandardRadzenDataGrid>

@code {
  IEnumerable<ProductMappingDto> productMappings = Enumerable.Empty<ProductMappingDto>();

  private async Task RetrieveProductMappings()
  {
    productMappings = await GetProductMappingQuery.RunAsync(SettingsService.Settings);
  }
}
```

In the code above you can see that we are using our new `StandardRadzenDataGrid` component.
We provide the `RetrieveProductMappings` method to the `RetrieveData` parameter, which will be called when the user clicks the button to retrieve the data.

We use the `RetrieveDataButtonText` parameter to specify what text should appear on the button, and have set the `IsDisabled` parameter to `true` when the settings are not valid for the Get Product Mappings query to be performed.

## Conclusion

We have seen how we can inherit and extend an existing component to create our own component.
We are able to add new UI elements to it, override default values of the base component, and even hide parameters of the base component so that users cannot set them.

Creating our own component in this way allows us to reduce the amount of code we need to write in our Blazor pages, and also allows us to enforce certain settings on the component that we want to be consistent across our application.

If you found this article helpful, or have any other thoughts on how this could be improved or different approaches that could be taken, leave a comment below.

Happy coding!
