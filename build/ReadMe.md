# Build Script for DPSF Website

The website used to run as a PHP website.
It was converted to a static HTML website using the PowerShell [BuildWebsite.ps1](./BuildWebsite.ps1) script.

The main feature of PHP was to dynamically include shared HTML parts (like the header and footer) into each page.
This build script replicates that functionality by searching for custom `<custom_inject_during_build "<partFilePathToInject>" />` tags in the HTML files and replacing them with the contents of the specified part files.

Using a static HTML website allows the site to be hosted on GitHub Pages for free.
