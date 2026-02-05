---
title: "Colour your kubectl output and type less"
permalink: /Colour-your-kubectl-output-and-type-less/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Kubernetes
  - PowerShell
  - Productivity
tags:
  - Kubernetes
  - PowerShell
  - Productivity
---

If you work with Kubernetes on the command line using `kubectl`, you will appreciate [kubecolor](https://kubecolor.github.io/).
Kubecolor is a small utility that adds colour to your `kubectl` output, making it easier to read and understand at a glance.

Normally when you run a `kubectl` command, you get back a wall of boring grey text.
With kubecolor, you get informative, colourful output like this:

![Kubecolor sample output 1](/assets/Posts/2026-02-05-Colour-your-kubectl-output-and-type-less/kubecolor-sample-output-1.png)

![Kubecolor sample output 2](/assets/Posts/2026-02-05-Colour-your-kubectl-output-and-type-less/kubecolor-sample-output-2.png)

I especially appreciate how errors are highlighted red, making them easy to spot.

## Kubecolor installation

Kubecolor is cross-platform, easy to install, and has great [installation documentation](https://kubecolor.github.io/setup/install/).

For example, installing it on Windows is as easy as running:

```powershell
winget install --id Kubecolor.kubecolor
```

## Setup to use kubecolor by default

The Kubecolor documentation also has [great setup instructions](https://kubecolor.github.io/setup/shells/powershell/) for many shells, including PowerShell.

You can typically set up an alias so that anytime you run `kubectl`, it automatically uses kubecolor.
Better yet, instead of having to type `kubectl` every time, just type `k` instead!

Here's how you can set that up in PowerShell:

```powershell
Set-Alias -Name kubectl -Value kubecolor
Set-Alias -Name k -Value kubectl
```

Now you can run `k get pods` and see colourful output by default.

## Setup kubectl command and parameter completions

The documentation also has instructions for setting up [kubectl completions](https://kubecolor.github.io/setup/shells/powershell/) in many shells, which is super handy.

This is how it can be done in PowerShell:

```powershell
kubectl completion powershell | Out-String | Invoke-Expression
Register-ArgumentCompleter -CommandName 'k','kubectl','kubecolor' -ScriptBlock $__kubectlCompleterBlock
```

Now you can type `k get p` and hit <kbd>Tab</kbd> and it will auto-complete.
Continue hitting <kbd>Tab</kbd> to cycle through the available options, or use <kbd>Ctrl</kbd> + <kbd>Space</kbd> to see all available options.

This works for both command names and parameter values, making it easier to discover `kubectl` commands and custom resources in your cluster.
Try typing each of the following and hitting <kbd>Ctrl</kbd> + <kbd>Space</kbd>.
Note the trailing space at the end of each:

- `k `
- `k get `
- `k get pods -n `

## Add the code to your PowerShell profile

You don't want to have to run the setup commands every time you open PowerShell though.
You can use your PowerShell profile to run the code automatically every time PowerShell starts.

To find the file path of your PowerShell profile, in PowerShell run:

```powershell
$PROFILE
```

Open that file (or create it if it doesn't exist) in your text editor and add the following code to it:

```powershell
# If kubecolor is installed, alias kubectl to use it.
if (Get-Command kubecolor -ErrorAction SilentlyContinue)
{
  Set-Alias -Name kubectl -Value kubecolor
}

# If kubectl is installed, register the argument completions for kubectl and its aliases.
if (Get-Command kubectl -ErrorAction SilentlyContinue)
{
  Set-Alias -Name k -Value kubectl
  kubectl completion powershell | Out-String | Invoke-Expression
  Register-ArgumentCompleter -CommandName 'k','kubectl','kubecolor' -ScriptBlock $__kubectlCompleterBlock
}
```

That's it!

To test it out, open a new PowerShell window and run `k version` or `k get pods`.
You should see beautiful, colourful output.

## Colour themes

Kubecolor comes with several built-in colour themes, including light and dark themes.
It even allows you to create your own custom themes.
See [the documentation](https://kubecolor.github.io/customizing/themes/) for more info.

## Conclusion

So now you get beautiful, colourful `kubectl` output that is easier to read and understand at a glance.
You also get to type less by using the `k` alias, and improve discoverability with command and parameter completions.

I hope you found this useful.

Happy Kuberneting!
