---
id: 101
title: 'Adding and accessing custom sections in your C# App.config'
date: 2012-09-25T14:04:00-06:00
author: deadlydog
guid: https://deadlydog.wordpress.com/?p=101
permalink: /adding-and-accessing-custom-sections-in-your-c-app-config/
jabber_published:
  - "1353355941"
categories:
  - .NET
  - 'C#'
tags:
  - app.config
  - 'C#'
  - configuration
  - CSharp
  - section
---
**Update (Feb 10, 2016):** I found a NuGet package called [simple-config](https://github.com/spadger/simple-config/) that allows you to dynamically bind a section in your web/app.config file to a strongly typed class without having to write all of the boiler-plate code that I show here. This may be an easier solution for you than going through the code I show below in this post.

So I recently thought I’d try using the app.config file to specify some data for my application (such as URLs) rather than hard-coding it into my app, which would require a recompile and redeploy of my app if one of our URLs changed.  By using the app.config it allows a user to just open up the .config file that sits beside their .exe file and edit the URLs right there and then re-run the app; no recompiling, no redeployment necessary.

I spent a good few hours fighting with the app.config and looking at examples on Google before I was able to get things to work properly.  Most of the examples I found showed you how to pull a value from the app.config if you knew the specific key of the element you wanted to retrieve, but it took me a while to find a way to simply loop through all elements in a section, so I thought I would share my solutions here.

Due to the popularity of this post, I have created a sample solution that shows the full implementation of both of the methods mentioned below.

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:8ba5bc7e-2d9b-44b3-81f5-b3f88338cfa7" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <p>
    <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2014/02/AppConfigExample1.zip" target="_blank">Download the source code example.</a>
  </p>
</div>

<span style="color: #d16349; font-size: medium;"><strong>Simple and Easy</strong></span>

The easiest way to use the app.config is to use the built-in types, such as NameValueSectionHandler.  For example, if we just wanted to add a list of database server urls to use in my app, we could do this in the app.config file like so:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:7f0178f6-9661-4260-a12e-7d3181bf3cd9" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: xml; gutter: true; title: ; notranslate" title="">
&lt;?xml version="1.0" encoding="utf-8" ?&gt;
&lt;configuration&gt;
    &lt;configSections&gt;
        &lt;section name="ConnectionManagerDatabaseServers" type="System.Configuration.NameValueSectionHandler" /&gt;
    &lt;/configSections&gt;
    &lt;startup&gt;
        &lt;supportedRuntime version="v4.0" sku=".NETFramework,Version=v4.5" /&gt;
    &lt;/startup&gt;
    &lt;ConnectionManagerDatabaseServers&gt;
        &lt;add key="localhost" value="localhost" /&gt;
        &lt;add key="Dev" value="Dev.MyDomain.local" /&gt;
        &lt;add key="Test" value="Test.MyDomain.local" /&gt;
        &lt;add key="Live" value="Prod.MyDomain.com" /&gt;
    &lt;/ConnectionManagerDatabaseServers&gt;
&lt;/configuration&gt;
</pre>
</div>

And then you can access these values in code like so:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:1dbfe17a-cb71-45f9-819b-10c39e6b7117" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: csharp; title: ; notranslate" title="">
string devUrl = string.Empty;
var connectionManagerDatabaseServers = ConfigurationManager.GetSection("ConnectionManagerDatabaseServers") as NameValueCollection;
if (connectionManagerDatabaseServers != null)
{
    devUrl = connectionManagerDatabaseServers["Dev"].ToString();
}
</pre>
</div>

Sometimes though you don’t know what the keys are going to be and you just want to grab all of the values in that ConnectionManagerDatabaseServers section.  In that case you can get them all like this:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:48aaaff3-ed0f-42d3-822a-40fb0d2f9bee" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: csharp; title: ; notranslate" title="">
// Grab the Environments listed in the App.config and add them to our list.
var connectionManagerDatabaseServers = ConfigurationManager.GetSection("ConnectionManagerDatabaseServers") as NameValueCollection;
if (connectionManagerDatabaseServers != null)
{
    foreach (var serverKey in connectionManagerDatabaseServers.AllKeys)
    {
        string serverValue = connectionManagerDatabaseServers.GetValues(serverKey).FirstOrDefault();
        AddDatabaseServer(serverValue);
    }
}
</pre>
</div>

And here we just assume that the AddDatabaseServer() function adds the given string to some list of strings.

One thing to note is that in the app.config file, <configSections> must be the first thing to appear in the <configuration> section, otherwise an error will be thrown at runtime. Also, the ConfigurationManager class is in the System.Configuration namespace, so be sure you have

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:f1adcc7f-7e35-4217-abd3-985463dc8be1" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: csharp; gutter: false; title: ; notranslate" title="">
using System.Configuration
</pre>
</div>

at the top of your C# files, as well as the “System.Configuration” assembly included in your project’s references.

So this works great, but what about when we want to bring in more values than just a single string (or technically you could use this to bring in 2 strings, where the “key” could be the other string you want to store; for example, we could have stored the value of the Key as the user-friendly name of the url).

<span style="color: #d16349; font-size: medium;"><strong>More Advanced (and more complicated)</strong></span>

So if you want to bring in more information than a string or two per object in the section, then you can no longer simply use the built-in System.Configuration.NameValueSectionHandler type provided for us.  Instead you have to build your own types.  Here let’s assume that we again want to configure a set of addresses (i.e. urls), but we want to specify some extra info with them, such as the user-friendly name, if they require SSL or not, and a list of security groups that are allowed to save changes made to these endpoints.

So let’s start by looking at the app.config:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:049bb6de-ae35-4de6-bd81-c549c12cb5ce" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: xml; title: ; notranslate" title="">
&lt;?xml version="1.0" encoding="utf-8" ?&gt;
&lt;configuration&gt;
    &lt;configSections&gt;
        &lt;section name="ConnectionManagerDataSection" type="ConnectionManagerUpdater.Data.Configuration.ConnectionManagerDataSection, ConnectionManagerUpdater" /&gt;
    &lt;/configSections&gt;
    &lt;startup&gt;
        &lt;supportedRuntime version="v4.0" sku=".NETFramework,Version=v4.5" /&gt;
    &lt;/startup&gt;
    &lt;ConnectionManagerDataSection&gt;
        &lt;ConnectionManagerEndpoints&gt;
            &lt;add name="Development" address="Dev.MyDomain.local" useSSL="false" /&gt;
            &lt;add name="Test" address="Test.MyDomain.local" useSSL="true" /&gt;
            &lt;add name="Live" address="Prod.MyDomain.com" useSSL="true" securityGroupsAllowedToSaveChanges="ConnectionManagerUsers" /&gt;
        &lt;/ConnectionManagerEndpoints&gt;
    &lt;/ConnectionManagerDataSection&gt;
&lt;/configuration&gt;
</pre>
</div>

The first thing to notice here is that my section is now using the type “ConnectionManagerUpdater.Data.Configuration.ConnectionManagerDataSection” (the fully qualified path to my new class I created) “, ConnectionManagerUpdater” (the name of the assembly my new class is in).  Next, you will also notice an extra layer down in the <ConnectionManagerDataSection> which is the <ConnectionManagerEndpoints> element.  This is a new collection class that I created to hold each of the Endpoint entries that are defined.  Let’s look at that code now:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:72b87175-c05a-4901-aeb8-e10d830c17e8" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: csharp; pad-line-numbers: true; title: ; notranslate" title="">
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConnectionManagerUpdater.Data.Configuration
{
    public class ConnectionManagerDataSection : ConfigurationSection
    {
        /// &lt;summary&gt;
        /// The name of this section in the app.config.
        /// &lt;/summary&gt;
        public const string SectionName = "ConnectionManagerDataSection";

        private const string EndpointCollectionName = "ConnectionManagerEndpoints";

        [ConfigurationProperty(EndpointCollectionName)]
        [ConfigurationCollection(typeof(ConnectionManagerEndpointsCollection), AddItemName = "add")]
        public ConnectionManagerEndpointsCollection ConnectionManagerEndpoints { get { return (ConnectionManagerEndpointsCollection)base[EndpointCollectionName]; } }
    }

    public class ConnectionManagerEndpointsCollection : ConfigurationElementCollection
    {
        protected override ConfigurationElement CreateNewElement()
        {
            return new ConnectionManagerEndpointElement();
        }

        protected override object GetElementKey(ConfigurationElement element)
        {
            return ((ConnectionManagerEndpointElement)element).Name;
        }
    }

    public class ConnectionManagerEndpointElement : ConfigurationElement
    {
        [ConfigurationProperty("name", IsRequired = true)]
        public string Name
        {
            get { return (string)this["name"]; }
            set { this["name"] = value; }
        }

        [ConfigurationProperty("address", IsRequired = true)]
        public string Address
        {
            get { return (string)this["address"]; }
            set { this["address"] = value; }
        }

        [ConfigurationProperty("useSSL", IsRequired = false, DefaultValue = false)]
        public bool UseSSL
        {
            get { return (bool)this["useSSL"]; }
            set { this["useSSL"] = value; }
        }

        [ConfigurationProperty("securityGroupsAllowedToSaveChanges", IsRequired = false)]
        public string SecurityGroupsAllowedToSaveChanges
        {
            get { return (string)this["securityGroupsAllowedToSaveChanges"]; }
            set { this["securityGroupsAllowedToSaveChanges"] = value; }
        }
    }
}
</pre>
</div>

So here the first class we declare is the one that appears in the <configSections> element of the app.config.  It is ConnectionManagerDataSection and it inherits from the necessary System.Configuration.ConfigurationSection class.  This class just has one property (other than the expected section name), that basically just says I have a Collection property, which is actually a ConnectionManagerEndpointsCollection, which is the next class defined.

The ConnectionManagerEndpointsCollection class inherits from ConfigurationElementCollection and overrides the required fields.  The first tells it what type of Element to create when adding a new one (in our case a ConnectionManagerEndpointElement), and a function specifying what property on our ConnectionManagerEndpointElement class is the unique key, which I’ve specified to be the Name field.

The last class defined is the actual meat of our elements.  It inherits from ConfigurationElement and specifies the properties of the element (which can then be set in the xml of the App.config).  The “ConfigurationProperty” attribute on each of the properties tells what we expect the name of the property to correspond to in each element in the app.config, as well as some additional information such as if that property is required and what it’s default value should be.

Finally, the code to actually access these values would look like this:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:94735977-0a45-4c28-8f8e-29bbbcf30a93" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: csharp; title: ; notranslate" title="">
// Grab the Environments listed in the App.config and add them to our list.
var connectionManagerDataSection = ConfigurationManager.GetSection(ConnectionManagerDataSection.SectionName) as ConnectionManagerDataSection;
if (connectionManagerDataSection != null)
{
    foreach (ConnectionManagerEndpointElement endpointElement in connectionManagerDataSection.ConnectionManagerEndpoints)
    {
        var endpoint = new ConnectionManagerEndpoint() { Name = endpointElement.Name, ServerInfo = new ConnectionManagerServerInfo() { Address = endpointElement.Address, UseSSL = endpointElement.UseSSL, SecurityGroupsAllowedToSaveChanges = endpointElement.SecurityGroupsAllowedToSaveChanges.Split(',').Where(e =&gt; !string.IsNullOrWhiteSpace(e)).ToList() } };
        AddEndpoint(endpoint);
    }
}
</pre>
</div>

This looks very similar to what we had before in the “simple” example.  The main points of interest are that we cast the section as ConnectionManagerDataSection (which is the class we defined for our section) and then iterate over the endpoints collection using the ConnectionManagerEndpoints property we created in the ConnectionManagerDataSection class.

Also, some other helpful resources around using app.config that I found (and for parts that I didn’t really explain in this article) are:

[How do you use sections in C# 4.0 app.config?](http://stackoverflow.com/questions/4670669/how-do-you-use-sections-in-c-sharp-4-0-app-config) (Stack Overflow) <== Shows how to use Section Groups as well, which is something that I did not cover here, but might be of interest to you.

[How to: Create Custom Configuration Sections Using Configuration Section](http://msdn.microsoft.com/en-us/library/2tw134k3.aspx) (MSDN)

[ConfigurationSection Class](http://msdn.microsoft.com/en-us/library/system.configuration.configurationsection.aspx) (MSDN)

[ConfigurationCollectionAttribute Class](http://msdn.microsoft.com/en-us/library/system.configuration.configurationcollectionattribute.aspx) (MSDN)

[ConfigurationElementCollection Class](http://msdn.microsoft.com/en-us/library/system.configuration.configurationelementcollection.aspx) (MSDN)

I hope you find this helpful.  Feel free to leave a comment.  Happy Coding!
