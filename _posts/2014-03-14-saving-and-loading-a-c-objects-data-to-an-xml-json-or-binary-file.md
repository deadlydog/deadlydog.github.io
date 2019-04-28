---
id: 731
title: 'Saving And Loading A C# Object&rsquo;s Data To An Xml, Json, Or Binary File'
date: 2014-03-14T15:53:35-06:00
author: deadlydog
layout: post
guid: http://dans-blog.azurewebsites.net/?p=731
permalink: /saving-and-loading-a-c-objects-data-to-an-xml-json-or-binary-file/
categories:
  - 'C#'
  - Json
  - XML
tags:
  - Binary
  - Class
  - file
  - Json
  - List
  - Load
  - object
  - Read
  - Save
  - Settings
  - Write
  - XML
---
I love creating tools, particularly ones for myself and other developers to use.&#160; A common situation that I run into is needing to save the user’s settings to a file so that I can load them up the next time the tool is ran.&#160; I find that the easiest way to accomplish this is to create a Settings class to hold all of the user’s settings, and then use serialization to save and load the class instance to/from a file.&#160; I mention a Settings class here, but you can use this technique to save any object (or list of objects) to a file.

There are tons of different formats that you may want to save your object instances as, but the big three are Binary, XML, and Json.&#160; Each of these formats have their pros and cons, which I won’t go into.&#160; Below I present functions that can be used to save and load any object instance to / from a file, as well as the different aspects to be aware of when using each method.

The follow code (without examples of how to use it) [is also available here](https://dansutilitylibraries.codeplex.com/SourceControl/latest#DansUtilityLibraries/DansCSharpLibrary/Serialization/BinarySerialization.cs), and can be used directly from [my NuGet Package](https://www.nuget.org/packages/DansUtilityLibraries.CSharpLibrary/).

### &#160;

### Writing and Reading an object to / from a Binary file

  * Writes and reads ALL object properties and variables to / from the file (i.e. public, protected, internal, and private).
  * The data saved to the file is not human readable, and thus cannot be edited outside of your application.
  * Have to decorate class (and all classes that it contains) with a **[Serializable]** attribute.
  * Use the **[NonSerialized]** attribute to exclude a variable from being written to the file; there is no way to prevent an auto-property from being serialized besides making it use a backing variable and putting the [NonSerialized] attribute on that.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:b11cb8df-99d0-49ca-b5bd-5147e6683a42" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; pad-line-numbers: true; title: ; notranslate" title="">
/// &lt;summary&gt;
/// Functions for performing common binary Serialization operations.
/// &lt;para&gt;All properties and variables will be serialized.&lt;/para&gt;
/// &lt;para&gt;Object type (and all child types) must be decorated with the [Serializable] attribute.&lt;/para&gt;
/// &lt;para&gt;To prevent a variable from being serialized, decorate it with the [NonSerialized] attribute; cannot be applied to properties.&lt;/para&gt;
/// &lt;/summary&gt;
public static class BinarySerialization
{
	/// &lt;summary&gt;
	/// Writes the given object instance to a binary file.
	/// &lt;para&gt;Object type (and all child types) must be decorated with the [Serializable] attribute.&lt;/para&gt;
	/// &lt;para&gt;To prevent a variable from being serialized, decorate it with the [NonSerialized] attribute; cannot be applied to properties.&lt;/para&gt;
	/// &lt;/summary&gt;
	/// &lt;typeparam name="T"&gt;The type of object being written to the XML file.&lt;/typeparam&gt;
	/// &lt;param name="filePath"&gt;The file path to write the object instance to.&lt;/param&gt;
	/// &lt;param name="objectToWrite"&gt;The object instance to write to the XML file.&lt;/param&gt;
	/// &lt;param name="append"&gt;If false the file will be overwritten if it already exists. If true the contents will be appended to the file.&lt;/param&gt;
	public static void WriteToBinaryFile&lt;T&gt;(string filePath, T objectToWrite, bool append = false)
	{
		using (Stream stream = File.Open(filePath, append ? FileMode.Append : FileMode.Create))
		{
			var binaryFormatter = new System.Runtime.Serialization.Formatters.Binary.BinaryFormatter();
			binaryFormatter.Serialize(stream, objectToWrite);
		}
	}

	/// &lt;summary&gt;
	/// Reads an object instance from a binary file.
	/// &lt;/summary&gt;
	/// &lt;typeparam name="T"&gt;The type of object to read from the XML.&lt;/typeparam&gt;
	/// &lt;param name="filePath"&gt;The file path to read the object instance from.&lt;/param&gt;
	/// &lt;returns&gt;Returns a new instance of the object read from the binary file.&lt;/returns&gt;
	public static T ReadFromBinaryFile&lt;T&gt;(string filePath)
	{
		using (Stream stream = File.Open(filePath, FileMode.Open))
		{
			var binaryFormatter = new System.Runtime.Serialization.Formatters.Binary.BinaryFormatter();
			return (T)binaryFormatter.Deserialize(stream);
		}
	}
}
</pre>
</div>

&#160;

And here is an example of how to use it:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:a4c84087-8308-457d-8298-89423a0307d7" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; title: ; notranslate" title="">
[Serializable]
public class Person
{
	public string Name { get; set; }
	public int Age = 20;
	public Address HomeAddress { get; set;}
	private string _thisWillGetWrittenToTheFileToo = "even though it is a private variable.";

	[NonSerialized]
	public string ThisWillNotBeWrittenToTheFile = "because of the [NonSerialized] attribute.";
}

[Serializable]
public class Address
{
	public string StreetAddress { get; set; }
	public string City { get; set; }
}

// And then in some function.
Person person = new Person() { Name = "Dan", Age = 30; HomeAddress = new Address() { StreetAddress = "123 My St", City = "Regina" }};
List&lt;Person&gt; people = GetListOfPeople();
BinarySerialization.WriteToBinaryFile&lt;Person&gt;("C:\person.bin", person);
BinarySerialization.WriteToBinaryFile&lt;List&lt;People&gt;&gt;("C:\people.bin", people);

// Then in some other function.
Person person = BinarySerialization.ReadFromBinaryFile&lt;Person&gt;("C:\person.bin");
List&lt;Person&gt; people = BinarySerialization.ReadFromBinaryFile&lt;List&lt;Person&gt;&gt;("C:\people.bin");
</pre>
</div>

&#160;

### Writing and Reading an object to / from an XML file (Using System.Xml.Serialization.XmlSerializer in the System.Xml assembly)

  * Only writes and reads the Public properties and variables to / from the file.
  * Classes to be serialized must contain a public parameterless constructor.
  * The data saved to the file is human readable, so it can easily be edited outside of your application.
  * Use the **[XmlIgnore]** attribute to exclude a public property or variable from being written to the file.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:94d8336a-1e98-4394-a260-d2271f82e547" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; title: ; notranslate" title="">
/// &lt;summary&gt;
/// Functions for performing common XML Serialization operations.
/// &lt;para&gt;Only public properties and variables will be serialized.&lt;/para&gt;
/// &lt;para&gt;Use the [XmlIgnore] attribute to prevent a property/variable from being serialized.&lt;/para&gt;
/// &lt;para&gt;Object to be serialized must have a parameterless constructor.&lt;/para&gt;
/// &lt;/summary&gt;
public static class XmlSerialization
{
	/// &lt;summary&gt;
	/// Writes the given object instance to an XML file.
	/// &lt;para&gt;Only Public properties and variables will be written to the file. These can be any type though, even other classes.&lt;/para&gt;
	/// &lt;para&gt;If there are public properties/variables that you do not want written to the file, decorate them with the [XmlIgnore] attribute.&lt;/para&gt;
	/// &lt;para&gt;Object type must have a parameterless constructor.&lt;/para&gt;
	/// &lt;/summary&gt;
	/// &lt;typeparam name="T"&gt;The type of object being written to the file.&lt;/typeparam&gt;
	/// &lt;param name="filePath"&gt;The file path to write the object instance to.&lt;/param&gt;
	/// &lt;param name="objectToWrite"&gt;The object instance to write to the file.&lt;/param&gt;
	/// &lt;param name="append"&gt;If false the file will be overwritten if it already exists. If true the contents will be appended to the file.&lt;/param&gt;
	public static void WriteToXmlFile&lt;T&gt;(string filePath, T objectToWrite, bool append = false) where T : new()
	{
		TextWriter writer = null;
		try
		{
			var serializer = new XmlSerializer(typeof(T));
			writer = new StreamWriter(filePath, append);
			serializer.Serialize(writer, objectToWrite);
		}
		finally
		{
			if (writer != null)
				writer.Close();
		}
	}

	/// &lt;summary&gt;
	/// Reads an object instance from an XML file.
	/// &lt;para&gt;Object type must have a parameterless constructor.&lt;/para&gt;
	/// &lt;/summary&gt;
	/// &lt;typeparam name="T"&gt;The type of object to read from the file.&lt;/typeparam&gt;
	/// &lt;param name="filePath"&gt;The file path to read the object instance from.&lt;/param&gt;
	/// &lt;returns&gt;Returns a new instance of the object read from the XML file.&lt;/returns&gt;
	public static T ReadFromXmlFile&lt;T&gt;(string filePath) where T : new()
	{
		TextReader reader = null;
		try
		{
			var serializer = new XmlSerializer(typeof(T));
			reader = new StreamReader(filePath);
			return (T)serializer.Deserialize(reader);
		}
		finally
		{
			if (reader != null)
				reader.Close();
		}
	}
}
</pre>
</div>

&#160;

And here is an example of how to use it:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:6480431c-d8ac-4f5f-8612-2ffe93f2c3ff" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; title: ; notranslate" title="">
public class Person
{
	public string Name { get; set; }
	public int Age = 20;
	public Address HomeAddress { get; set;}
	private string _thisWillNotGetWrittenToTheFile = "because it is not public.";

	[XmlIgnore]
	public string ThisWillNotBeWrittenToTheFile = "because of the [XmlIgnore] attribute.";
}

public class Address
{
	public string StreetAddress { get; set; }
	public string City { get; set; }
}

// And then in some function.
Person person = new Person() { Name = "Dan", Age = 30; HomeAddress = new Address() { StreetAddress = "123 My St", City = "Regina" }};
List&lt;Person&gt; people = GetListOfPeople();
XmlSerialization.WriteToXmlFile&lt;Person&gt;("C:\person.txt", person);
XmlSerialization.WriteToXmlFile&lt;List&lt;People&gt;&gt;("C:\people.txt", people);

// Then in some other function.
Person person = XmlSerialization.ReadFromXmlFile&lt;Person&gt;("C:\person.txt");
List&lt;Person&gt; people = XmlSerialization.ReadFromXmlFile&lt;List&lt;Person&gt;&gt;("C:\people.txt");
</pre>
</div>

&#160;

### Writing and Reading an object to / from a Json file (using the [Newtonsoft.Json](http://james.newtonking.com/json) assembly in the [Json.NET NuGet package](http://www.nuget.org/packages/Newtonsoft.Json/))

  * Only writes and reads the Public properties and variables to / from the file.
  * Classes to be serialized must contain a public parameterless constructor.
  * The data saved to the file is human readable, so it can easily be edited outside of your application.
  * Use the **[JsonIgnore]** attribute to exclude a public property or variable from being written to the file.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:024b40ba-51fa-498e-b151-08725060151e" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; title: ; notranslate" title="">
/// &lt;summary&gt;
/// Functions for performing common Json Serialization operations.
/// &lt;para&gt;Requires the Newtonsoft.Json assembly (Json.Net package in NuGet Gallery) to be referenced in your project.&lt;/para&gt;
/// &lt;para&gt;Only public properties and variables will be serialized.&lt;/para&gt;
/// &lt;para&gt;Use the [JsonIgnore] attribute to ignore specific public properties or variables.&lt;/para&gt;
/// &lt;para&gt;Object to be serialized must have a parameterless constructor.&lt;/para&gt;
/// &lt;/summary&gt;
public static class JsonSerialization
{
	/// &lt;summary&gt;
	/// Writes the given object instance to a Json file.
	/// &lt;para&gt;Object type must have a parameterless constructor.&lt;/para&gt;
	/// &lt;para&gt;Only Public properties and variables will be written to the file. These can be any type though, even other classes.&lt;/para&gt;
	/// &lt;para&gt;If there are public properties/variables that you do not want written to the file, decorate them with the [JsonIgnore] attribute.&lt;/para&gt;
	/// &lt;/summary&gt;
	/// &lt;typeparam name="T"&gt;The type of object being written to the file.&lt;/typeparam&gt;
	/// &lt;param name="filePath"&gt;The file path to write the object instance to.&lt;/param&gt;
	/// &lt;param name="objectToWrite"&gt;The object instance to write to the file.&lt;/param&gt;
	/// &lt;param name="append"&gt;If false the file will be overwritten if it already exists. If true the contents will be appended to the file.&lt;/param&gt;
	public static void WriteToJsonFile&lt;T&gt;(string filePath, T objectToWrite, bool append = false) where T : new()
	{
		TextWriter writer = null;
		try
		{
			var contentsToWriteToFile = Newtonsoft.Json.JsonConvert.SerializeObject(objectToWrite);
			writer = new StreamWriter(filePath, append);
			writer.Write(contentsToWriteToFile);
		}
		finally
		{
			if (writer != null)
				writer.Close();
		}
	}

	/// &lt;summary&gt;
	/// Reads an object instance from an Json file.
	/// &lt;para&gt;Object type must have a parameterless constructor.&lt;/para&gt;
	/// &lt;/summary&gt;
	/// &lt;typeparam name="T"&gt;The type of object to read from the file.&lt;/typeparam&gt;
	/// &lt;param name="filePath"&gt;The file path to read the object instance from.&lt;/param&gt;
	/// &lt;returns&gt;Returns a new instance of the object read from the Json file.&lt;/returns&gt;
	public static T ReadFromJsonFile&lt;T&gt;(string filePath) where T : new()
	{
		TextReader reader = null;
		try
		{
			reader = new StreamReader(filePath);
			var fileContents = reader.ReadToEnd();
			return Newtonsoft.Json.JsonConvert.DeserializeObject&lt;T&gt;(fileContents);
		}
		finally
		{
			if (reader != null)
				reader.Close();
		}
	}
}
</pre>
</div>

And here is an example of how to use it:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:428a32e0-1b79-435d-9616-4dc42cd1259b" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; title: ; notranslate" title="">
public class Person
{
	public string Name { get; set; }
	public int Age = 20;
	public Address HomeAddress { get; set;}
	private string _thisWillNotGetWrittenToTheFile = "because it is not public.";

	[JsonIgnore]
	public string ThisWillNotBeWrittenToTheFile = "because of the [JsonIgnore] attribute.";
}

public class Address
{
	public string StreetAddress { get; set; }
	public string City { get; set; }
}

// And then in some function.
Person person = new Person() { Name = "Dan", Age = 30; HomeAddress = new Address() { StreetAddress = "123 My St", City = "Regina" }};
List&lt;Person&gt; people = GetListOfPeople();
JsonSerialization.WriteToJsonFile&lt;Person&gt;("C:\person.txt", person);
JsonSerialization.WriteToJsonFile&lt;List&lt;People&gt;&gt;("C:\people.txt", people);

// Then in some other function.
Person person = JsonSerialization.ReadFromJsonFile&lt;Person&gt;("C:\person.txt");
List&lt;Person&gt; people = JsonSerialization.ReadFromJsonFile&lt;List&lt;Person&gt;&gt;("C:\people.txt");
</pre>
</div>

&#160;

As you can see, the Json example is almost identical to the Xml example, with the exception of using the [JsonIgnore] attribute instead of [XmlIgnore].

&#160;

### Writing and Reading an object to / from a Json file (using the [JavaScriptSerializer](http://msdn.microsoft.com/en-us/library/system.web.script.serialization.javascriptserializer%28v=vs.110%29.aspx) in the System.Web.Extensions assembly)

There are many Json serialization libraries out there.&#160; I mentioned the Newtonsoft.Json one because it is very popular, and I am also mentioning this JavaScriptSerializer one because it is built into the .Net framework.&#160; The catch with this one though is that it requires the Full .Net 4.0 framework, not just the .Net Framework 4.0 Client Profile.

The caveats to be aware of are the same between the Newtonsoft.Json and JavaScriptSerializer libraries, except instead of using [JsonIgnore] you would use **[ScriptIgnore]**.

Be aware that the JavaScriptSerializer is in the System.Web.Extensions assembly, but in the System.Web.Script.Serialization namespace.&#160; Here is the code from the Newtonsoft.Json code snippet that needs to be replaced in order to use the JavaScriptSerializer:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:446bb8dd-02bb-4ba1-96eb-c598c8847b78" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; title: ; notranslate" title="">
// In WriteFromJsonFile&lt;T&gt;() function replace:
var contentsToWriteToFile = Newtonsoft.Json.JsonConvert.SerializeObject(objectToWrite);
// with:
var contentsToWriteToFile = new System.Web.Script.Serialization.JavaScriptSerializer().Serialize(objectToWrite);

// In ReadFromJsonFile&lt;T&gt;() function replace:
return Newtonsoft.Json.JsonConvert.DeserializeObject&lt;T&gt;(fileContents);
// with:
return new System.Web.Script.Serialization.JavaScriptSerializer().Deserialize&lt;T&gt;(fileContents);
</pre>
</div>

&#160;

Happy Coding!