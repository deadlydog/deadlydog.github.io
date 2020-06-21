---
title: "A better way to do TestCases when unit testing with Pester"
permalink: /A-better-way-to-do-TestCases-when-unit-testing-with-Pester/
#date: 2099-01-17T00:00:00-06:00
last_modified_at: 2020-06-20
comments_locked: false
categories:
  - PowerShell
  - Pester
  - Unit Testing
tags:
  - PowerShell
  - Pester
  - Unit Testing
---

> Note: Pester v5 was released which made breaking changes.
> The code shown here works with Pester v4 and previous, but not v5.
> I'm hoping to update this post in the future to show how to use this same technique with Pester v5.

While writing some PowerShell code a while back I found myself at a crossroads in terms of the style I wanted to write some unit tests in with [Pester](https://github.com/pester/Pester).
I had a number of test cases that would be testing the same function, just with different input data.
In this post we take a look at a simple unit test example in Pester, and how we can evolve it to become better.

## A simple example to start from

Below is a shortened and simplified example of the tests I was writing.
There's a `Get-WorkingDirectory` function that I wrote and want to test, and it takes 3 parameters: `workingDirectoryOption`, `customWorkingDirectory`, and `applicationPath`.
The function should return the custom directory or the application directory based on the `workingDirectoryOption` that was provided.

```powershell
Describe 'Get-WorkingDirectory' {
    Context 'When requesting the Application Directory as the working directory' {
        It 'Returns the applications directory when no Custom Working Directory is given' {
            $result = Get-WorkingDirectory -workingDirectoryOption 'ApplicationDirectory' -customWorkingDirectory '' -applicationPath 'C:\AppDirectory\MyApp.exe'
            $result | Should -Be 'C:\AppDirectory'
        }
        It 'Returns the applications directory when a Custom Working Directory is given' {
            $result = Get-WorkingDirectory -workingDirectoryOption 'ApplicationDirectory' -customWorkingDirectory 'C:\SomeDirectory' -applicationPath 'C:\AppDirectory\MyApp.exe'
            $result | Should -Be 'C:\AppDirectory'
        }
    }

    Context 'When requesting a custom working directory' {
        It 'Returns the custom directory' {
            $result = Get-WorkingDirectory -workingDirectoryOption 'CustomDirectory' -customWorkingDirectory 'C:\SomeDirectory' -applicationPath 'C:\AppDirectory\MyApp.exe'
            $result | Should -Be 'C:\SomeDirectory'
        }
        It 'Returns the custom directory even if its blank' {
            $result = Get-WorkingDirectory -workingDirectoryOption 'CustomDirectory' -customWorkingDirectory '' -applicationPath 'C:\AppDirectory\MyApp.exe'
            $result | Should -Be ''
        }
    }
}
```

The code above produces the following Pester output:

```text
Describing Get-WorkingDirectory

  Context When requesting the Application Directory as the working directory
    [+] Returns the applications directory when no Custom Working Directory is given 98ms
    [+] Returns the applications directory when a Custom Working Directory is given 15ms

  Context When requesting a custom working directory
    [+] Returns the custom directory 67ms
    [+] Returns the custom directory even if its blank 15ms
```

Don't worry about what the internals of the `Get-WorkingDirectory` function might look like, or the fact that `workingDirectoryOption` is a string rather than an enum/bool/switch, or that we could have separate `Get-CustomWorkingDirectory` and `Get-ApplicationWorkingDirectory` functions.
Those are all things that could be improved, but we're not concerned with that for this post.

## Use a function for the assertion

In the example above we only have 4 test cases, but in practice you may have 10s or 100s of test cases for a particular function.
Also, in the example above we're able to exercise the function and assert the result in only 2 lines of code, but for other scenarios each test may require many lines to arrange, act, and assert.
That can cause your test files to quickly bloat from a lot of copy and pasting.
One common technique to help alleviate that is to use other functions for arranging and asserting.
Let's do that here and see how the code transforms.

```powershell
Describe 'Get-WorkingDirectory' {
    function Assert-GetWorkingDirectoryReturnsCorrectResult
    {
        param
        (
            [string] $workingDirectoryOption,
            [string] $customWorkingDirectory,
            [string] $applicationPath,
            [string] $expectedWorkingDirectory
        )

        $result = Get-WorkingDirectory -workingDirectoryOption $workingDirectoryOption -customWorkingDirectory $customWorkingDirectory -applicationPath $applicationPath
        $result | Should -Be $expectedWorkingDirectory
    }

    Context 'When requesting the Application Directory as the working directory' {
        It 'Returns the applications directory when no Custom Working Directory is given' {
            Assert-GetWorkingDirectoryReturnsCorrectResult -workingDirectoryOption 'ApplicationDirectory' -customWorkingDirectory '' -applicationPath 'C:\AppDirectory\MyApp.exe' -expectedWorkingDirectory 'C:\AppDirectory'
        }
        It 'Returns the applications directory when a Custom Working Directory is given' {
            Assert-GetWorkingDirectoryReturnsCorrectResult -workingDirectoryOption 'ApplicationDirectory' -customWorkingDirectory 'C:\SomeDirectory' -applicationPath 'C:\AppDirectory\MyApp.exe' -expectedWorkingDirectory 'C:\AppDirectory'
        }
    }

    Context 'When requesting a custom working directory' {
        It 'Returns the custom directory' {
            Assert-GetWorkingDirectoryReturnsCorrectResult -workingDirectoryOption 'CustomDirectory' -customWorkingDirectory 'C:\SomeDirectory' -applicationPath 'C:\AppDirectory\MyApp.exe' -expectedWorkingDirectory 'C:\SomeDirectory'
        }
        It 'Returns the custom directory even if its blank' {
            Assert-GetWorkingDirectoryReturnsCorrectResult -workingDirectoryOption 'CustomDirectory' -customWorkingDirectory '' -applicationPath 'C:\AppDirectory\MyApp.exe' -expectedWorkingDirectory ''
        }
    }
}
```

The code above produces the following Pester output:

```text
Describing Get-WorkingDirectory

  Context When requesting the Application Directory as the working directory
    [+] Returns the applications directory when no Custom Working Directory is given 98ms
    [+] Returns the applications directory when a Custom Working Directory is given 15ms

  Context When requesting a custom working directory
    [+] Returns the custom directory 67ms
    [+] Returns the custom directory even if its blank 15ms
```

You can see that each test is now only a single line; one call to the `Assert-GetWorkingDirectoryReturnsCorrectResult` function.
The only difference between the tests are the parameters that they pass to the function.
The Pester output is identical.

## Save lines of code by using `TestCases`

Most unit testing frameworks, including Pester, come with a way to call the same test function multiple times with different parameters, allowing the code to become even shorter.
Pester accomplishes this by allowing a `TestCases` parameter to be passed to the [`It` method](https://github.com/pester/Pester/wiki/It).
Here is what the code looks like after being refactored to use `TestCases`.

```powershell
Describe 'Get-WorkingDirectory' {
    It 'Returns the correct working directory' -TestCases @(
        @{ workingDirectoryOption = 'ApplicationDirectory'; customWorkingDirectory = ''; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\AppDirectory' }
        @{ workingDirectoryOption = 'ApplicationDirectory'; customWorkingDirectory = 'C:\SomeDirectory'; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\AppDirectory' }
        @{ workingDirectoryOption = 'CustomDirectory'; customWorkingDirectory = ''; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = '' }
        @{ workingDirectoryOption = 'CustomDirectory'; customWorkingDirectory = 'C:\SomeDirectory'; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\SomeDirectory' }
    ) {
        param
        (
            [string] $workingDirectoryOption,
            [string] $customWorkingDirectory,
            [string] $applicationPath,
            [string] $expectedWorkingDirectory
        )

        $result = Get-WorkingDirectory -workingDirectoryOption $workingDirectoryOption -customWorkingDirectory $customWorkingDirectory -applicationPath $applicationPath
        $result | Should -Be $expectedWorkingDirectory
    }
}
```

The code above produces the following Pester output:

```text
Describing Get-WorkingDirectory
  [+] Returns the correct working directory 56ms
  [+] Returns the correct working directory 11ms
  [+] Returns the correct working directory 15ms
  [+] Returns the correct working directory 13ms
```

You can see that the 4 test cases are now expressed as an array of hashtables.
The hashtable defines the parameter values that should be used for the test.
You may have noticed they include an additional `expectedWorkingDirectory` parameter, which is used to perform the assertion.

### Things I like about this approach

- Fewer lines of code required*.
- The 4 test cases are now stacked directly upon each other, making it visually easier to see the differences between that parameters used for each test case.

_\* You may have noticed that I also removed the 2 `Context` statements.
If I would have kept them, the entire function would have needed to be copied, making the number of lines of code much longer._

### Things I don't like about this approach

- In the code, I've lost the english description of what the test is actually testing.
- I've also lost it in the Pester test result's output.

This is actually a very big problem in my opinion.
Not having the english description means that when a tests fails, I don't immediately have a clear idea of what test scenario is no longer working; I need to go digging through the test code to try and figure out which of the `TestCases` is failing.
Also, once I find which test case is failing, it may not be obvious what the test case is actually intending to test.
I can look at the parameters, but without any context I'm not sure which parameters are relevant.
Is the fact that one of the parameters is an empty string important?
Or maybe it's that the working directory path has a special character in it?
Or maybe it has to do with the particular `workingDirectoryOption` value being provided?

__Having a clear description of what is being tested and what the expected result should be are vitally important__. e.g. 'Returns the applications directory when a Custom Working Directory is given'.

## A hybrid approach

So we've seen how to use a function to perform the assertions, as well as how to define the test cases stacked on top of each other to easily compare all of the test cases being covered.
Let's see if we can put them together to get the benefits of using `TestCases` without incurring the downsides.

```powershell
Describe 'Get-WorkingDirectory' {
    function Assert-GetWorkingDirectoryReturnsCorrectResult
    {
        param
        (
            [string] $testDescription,
            [string] $workingDirectoryOption,
            [string] $customWorkingDirectory,
            [string] $applicationPath,
            [string] $expectedWorkingDirectory
        )

        It $testDescription {
            $result = Get-WorkingDirectory -workingDirectoryOption $workingDirectoryOption -customWorkingDirectory $customWorkingDirectory -applicationPath $applicationPath
            $result | Should -Be $expectedWorkingDirectory
        }
    }

    Context 'When requesting the Application Directory as the working directory' {
        [hashtable[]] $tests = @(
            @{ testDescription = 'Returns the applications directory when no Custom Working Directory is given'
                workingDirectoryOption = 'ApplicationDirectory'; customWorkingDirectory = ''; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\AppDirectory' }
            @{ testDescription = 'Returns the applications directory when a Custom Working Directory is given'
                workingDirectoryOption = 'ApplicationDirectory'; customWorkingDirectory = 'C:\SomeDirectory'; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\AppDirectory' }
        )
        $tests | ForEach-Object {
            [hashtable] $parameters = $_
            Assert-GetWorkingDirectoryReturnsCorrectResult @parameters
        }
    }

    Context 'When requesting a custom working directory' {
        [hashtable[]] $tests = @(
            @{ testDescription = 'Returns the custom directory'
                workingDirectoryOption = 'CustomDirectory'; customWorkingDirectory = 'C:\SomeDirectory'; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\SomeDirectory' }
            @{ testDescription = 'Returns the custom directory even if its blank'
                workingDirectoryOption = 'CustomDirectory'; customWorkingDirectory = ''; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = '' }
        )
        $tests | ForEach-Object {
            [hashtable] $parameters = $_
            Assert-GetWorkingDirectoryReturnsCorrectResult @parameters
        }
    }
}
```

The code above produces the following Pester output:

```text
Describing Get-WorkingDirectory

  Context When requesting the Application Directory as the working directory
    [+] Returns the applications directory when no Custom Working Directory is given 90ms
    [+] Returns the applications directory when no Custom Working Directory is given 9ms

  Context When requesting a custom working directory
    [+] Returns the custom directory 49ms
    [+] Returns the custom directory even if its blank 11ms
```

Notice that the `It` block was moved into the `Assert-GetWorkingDirectoryReturnsCorrectResult` function, and that the test cases now include an additional `testDescription` parameter.

We are no longer using the built-in `TestCases` functionality, but instead create our own hashtable array of test cases and manually loop through each of them and call the `Assert-GetWorkingDirectoryReturnsCorrectResult` function, splatting the test case parameters.
Ideally this additional code could be avoided if the native `TestCases` functionality supported providing the `It` description in the `TestCases` hashtable array.
I've submitted [a GitHub issue requesting this feature in Pester](https://github.com/pester/Pester/issues/1361) to more easily get this functionality, but for now the approach shown here is the best I could think of.

> Update: Since writing this post I've discovered a better Pester-native way that achieves the same results as this hybrid approach.
> I've left the hybrid approach in here though for anybody interested, and describe the Pester-native approach next.

This approach allows us to get the best of both worlds; we have contextual english descriptions of each test case, both in code and in the Pester output, while also having our test cases stacked on top of each other so we can easily compare the parameters for each one, and easily add new test cases with minimal code.

While the code in this example is actually longer than the other approaches we started with, that changes as more test cases are added.

I could have skipped the `Context` blocks altogether and just included their text in the `testDescription` which would shorten the code a bit.
However, the `workingDirectoryOption` parameter value fundamentally changes how the `Get-WorkingDirectory` function behaves, and having separate contexts makes that more clear.

## The Pester-native approach

Since originally writing this post I've discovered that the `It` block supports variable substitution in its name when using the `TestCases` parameter.
They actually show it off on [the main Pester ReadMe page](https://github.com/pester/Pester) with this example code:

```powershell
It "Given valid -Name '<Filter>', it returns '<Expected>'" -TestCases @(
    @{ Filter = 'Earth'; Expected = 'Earth' }
    @{ Filter = 'ne*'  ; Expected = 'Neptune' }
    @{ Filter = 'ur*'  ; Expected = 'Uranus' }
    @{ Filter = 'm*'   ; Expected = 'Mercury', 'Mars' }
) {
    param ($Filter, $Expected)

    $planets = Get-Planet -Name $Filter
    $planets.Name | Should -Be $Expected
}
```

and its Pester output:

```text
[+] Given valid -Name 'Earth', it returns 'Earth' 27ms
[+] Given valid -Name 'ne*', it returns 'Neptune' 16ms
[+] Given valid -Name 'ur*', it returns 'Uranus' 17ms
[+] Given valid -Name 'm*', it returns 'Mercury Mars' 15ms
```

While I did notice that documentation before, it never clicked that I could use it to achieve my desired functionality here.
So continuing with our earlier example, I could use the following code to give each of my test cases a rich, contextual description:

```powershell
Describe 'Get-WorkingDirectory' {
    Context 'When requesting the Application Directory as the working directory' {
        It '<testDescription>' -TestCases @(
            @{ testDescription = 'Returns the applications directory when no Custom Working Directory is given'
                workingDirectoryOption = 'ApplicationDirectory'; customWorkingDirectory = ''; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\AppDirectory' }
            @{ testDescription = 'Returns the applications directory when a Custom Working Directory is given'
                workingDirectoryOption = 'ApplicationDirectory'; customWorkingDirectory = 'C:\SomeDirectory'; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\AppDirectory' }
        ) {
            param
            (
                [string] $workingDirectoryOption,
                [string] $customWorkingDirectory,
                [string] $applicationPath,
                [string] $expectedWorkingDirectory
            )

            $result = Get-WorkingDirectory -workingDirectoryOption $workingDirectoryOption -customWorkingDirectory $customWorkingDirectory -applicationPath $applicationPath
            $result | Should -Be $expectedWorkingDirectory
        }
    }

    Context 'When requesting a custom working directory' {
        It '<testDescription>' -TestCases @(
            @{ testDescription = 'Returns the custom directory'
                workingDirectoryOption = 'CustomDirectory'; customWorkingDirectory = 'C:\SomeDirectory'; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = 'C:\SomeDirectory' }
            @{ testDescription = 'Returns the custom directory even if its blank'
                workingDirectoryOption = 'CustomDirectory'; customWorkingDirectory = ''; applicationPath = 'C:\AppDirectory\MyApp.exe'; expectedWorkingDirectory = '' }
        ) {
            param
            (
                [string] $workingDirectoryOption,
                [string] $customWorkingDirectory,
                [string] $applicationPath,
                [string] $expectedWorkingDirectory
            )

            $result = Get-WorkingDirectory -workingDirectoryOption $workingDirectoryOption -customWorkingDirectory $customWorkingDirectory -applicationPath $applicationPath
            $result | Should -Be $expectedWorkingDirectory
        }
    }
}
```

The code above produces the following Pester output:

```text
Describing Get-WorkingDirectory

  Context When requesting the Application Directory as the working directory
    [+] Returns the applications directory when no Custom Working Directory is given 90ms
    [+] Returns the applications directory when no Custom Working Directory is given 9ms

  Context When requesting a custom working directory
    [+] Returns the custom directory 49ms
    [+] Returns the custom directory even if its blank 11ms
```

So here you can see that we've replaced the name of the `It` blocks with `<testDescription>`, as that's the name of the variable we provide in each test case hashtable.
For brevity you may decide to replace `testDescription` with `it`, or something similar.

Also, you'll notice that we have a lot of duplicated code between the 2 `It` statements again, so you could refactor that out into a function like we did in the hybrid approach above.
The benefits that this has over the hybrid approach is that we don't need to define the `[hashtable[]] $tests` variable any longer, nor do we need to manually iterate over it with the `$tests | ForEach-Object` statement, so this can save us from having to write that redundant code for every `It` block using `TestCases`.

## Conclusion

We started with a simple example and saw how to refactor it to use a an assertion function to make it less verbose when we have many tests.
We then saw how to refactor it to use `TestCases` to make it less verbose and easy to compare the test cases, at the cost of reduced clarity and context.
Next, we saw a hybrid approach that allows you to see all of the test cases side-by-side without losing important contextual information about the test cases.
Lastly, we saw how we can use the variable substitution functionality of the `It` block to achieve the same results as the hybrid approach, while saving on a bit of boilerplate code for every `It` block using `TestCases`.

There are always many different ways to do things when programming, and the approaches we choose often come down to personal preference, as well as other contextual information.
For example, if you only have a few test cases (as in the examples here), it may not be worth it to implement the approaches I've shown.
I hope that you'll find the approaches I've presented here valuable, or at the very least, interesting.

Feel free to leave comments and let me know what you think, or perhaps ways these approaches could be improved.

Happy coding!
