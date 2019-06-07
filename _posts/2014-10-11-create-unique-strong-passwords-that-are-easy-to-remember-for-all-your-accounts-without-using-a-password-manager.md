---
id: 825
title: Create Unique Strong Passwords That Are Easy To Remember For All Your Accounts, Without Using A Password Manager
date: 2014-10-11T15:38:51-06:00
guid: http://dans-blog.azurewebsites.net/?p=825
permalink: /create-unique-strong-passwords-that-are-easy-to-remember-for-all-your-accounts-without-using-a-password-manager/
categories:
  - Uncategorized
tags:
  - Account
  - Credentials
  - Password
  - Strong
  - Unique
---

## The Problem

We’ve all heard the warnings that we should use a strong password to prevent others from guessing our password, and that we should use a different password for every account we have.

A strong password is simply a password that meets a set of requirements, such as being at least X characters long and includes numbers and/or small letters and/or capital letters and/or symbols. Many websites and services enforce that a strong password be used.

If you don’t use a strong password, it’s likely that your password can be brute force hacked almost instantly. [Check how secure your passwords are here](https://howsecureismypassword.net/).

If you do use a strong password, it’s very likely that you use the same strong password (or set of strong passwords) for all of the services you use, simply because having to remember lots of passwords and which one is for which service is hard. This is very bad practice though, since if somebody gets your password they can access all of your services. There’s a lot of ways for somebody to get your password; from simply guessing it to software vulnerabilities like [the Heartbleed bug](http://heartbleed.com/), so you should try and always use a unique password for each service.

## The Solution

My super smart coworker Nathan Storms posted a very short blog about [his solution to this problem](http://architectevangelist.wordpress.com/2014/09/29/smart-complex-passwords/), which I’ll repeat and expand on here.

The basic idea is that instead of remembering a whole bunch of crazy passwords, __you calculate them using an algorithm/formula__. So instead of just using one password for all of your accounts, you use one formula to generate all of your passwords; That means instead of remembering a password, you just remember a formula. The formula can be as simple or complex as you like. Like most people, I prefer a simple one, but you don’t want it to be so simple that it’s easy for another person to guess it if they get ahold of one or two of your passwords.

The key to creating a unique password for each service that you use is to include part of the service’s name in your formula, such as the company name or website domain name.

The key to creating a strong password is to use a common strong phrase (or "salt" in security-speak) in all of your generated passwords.

The last piece to consider is that you want your salt + formula to generate a password that is not too short or too long. Longer passwords are always more secure, but many services have different min and max length requirements, so I find that aiming for about 12 characters satisfies most services while still generating a nice strong password.

## Examples

So the things we need are:

1. The service you are using. Let’s say you are creating an account at Google.com, so the service name is __Google__.
1. A __strong__ salt phrase. Let’s use: __1Qaz!__ (notice it includes a number, small letter, capital letter, and symbol)

### A Too Simple Formula Example

A simple formula might be to simply combine the first 3 characters of the service name with our salt, so we get: __Goo1Qaz!__

That’s not bad, but [howsecureismypassword.net](https://howsecureismypassword.net/ "https://howsecureismypassword.net/") tells us that it can be cracked within 3 days, which isn’t that great. We could simply change our salt to be a bit longer, such as 1Qaz!23>, which would make our password __Goo1Qaz!23>__. This puts our password at 11 characters and takes up to 50 thousand years to brute force, which is much better; Longer, stronger salts are always better.

There’s still a problem with this formula though; it’s too simple. To illustrate the point, for Yahoo.com the calculated password would be __Yah1Qaz!23>__. Now, if somebody got ahold of these two passwords and knew which services they were for, how long do you think it would take them to figure out your formula and be able to calculate all of your passwords? Probably not very long at all.

### Better Formula Examples

The problem with the formula above is that it’s easy for a human to recognize the pattern of how we use the service name; we just took the first 3 letters. Some better alternatives would be:

<table cellspacing="0" cellpadding="2" width="699" border="0">
  <tr>
    <td valign="top" width="462">
      <p align="center">
        <strong>Service Name Rule (using Google) [using StackOverflow]</strong>
      </p>
    </td>

    <td valign="top" width="104">
      <p align="center">
        <strong>Google Password</strong>
      </p>
    </td>

    <td valign="top" width="131">
      <p align="center">
        <strong>StackOverflow Password</strong>
      </p>
    </td>
  </tr>

  <tr>
    <td valign="top" width="462">
      Use last 3 letters backwards (<strong>elg</strong>ooG) [<strong>wol</strong>frevOkcatS]
    </td>

    <td valign="top" width="104">
      <strong>elg</strong>1Qaz!23>
    </td>

    <td valign="top" width="131">
      <strong>wol</strong>1Qaz!23>
    </td>
  </tr>

  <tr>
    <td valign="top" width="462">
      Use every 2nd letter, max 4 letters (G<strong>o</strong>o<strong>g</strong>l<strong>e</strong>) [S<strong>t</strong>a<strong>c</strong>k<strong>O</strong>v<strong>e</strong>rflow]
    </td>

    <td valign="top" width="104">
      <strong>oge</strong>1Qaz!23>
    </td>

    <td valign="top" width="131">
      <strong>tcOe</strong>1Qaz!23>
    </td>
  </tr>

  <tr>
    <td valign="top" width="462">
      Use next letter of first 3 letters (G + 1 = <strong>H</strong>, o + 1 = <strong>p</strong>) [S + 1 = <strong>T</strong>, t + 1 = <strong>u</strong>, a + 1 + <strong>b</strong>]
    </td>

    <td valign="top" width="104">
      <strong>Hpp</strong>1Qaz!23>
    </td>

    <td valign="top" width="131">
      <strong>Tub</strong>1Qaz!23>
    </td>
  </tr>

  <tr>
    <td valign="top" width="462">
      Use number of vowels and total length (<strong>3</strong> vowels, length of <strong>6</strong>) [<strong>4</strong> vowels, length of <strong>13</strong>]
    </td>

    <td valign="top" width="104">
      <strong>36</strong>1Qaz!23>
    </td>

    <td valign="top" width="131">
      <strong>413</strong>1Qaz!23>
    </td>
  </tr>

  <tr>
    <td valign="top" width="462">
      Number of vowels in front, length at end
    </td>

    <td valign="top" width="104">
      <strong>3</strong>1Qaz!23><strong>6</strong>
    </td>

    <td valign="top" width="131">
      <strong>4</strong>1Qaz!23><strong>13</strong>
    </td>
  </tr>

  <tr>
    <td valign="top" width="462">
      Number of vowels in front, length minus number of vowels at end (<strong>3</strong> vowels, 6 – 3 = <strong>3</strong>) [<strong>4</strong> vowels, 13 – 4 = <strong>9</strong>]
    </td>

    <td valign="top" width="104">
      <strong>3</strong>1Qaz!23><strong>3</strong>
    </td>

    <td valign="top" width="131">
      <strong>4</strong>1Qaz!23><strong>9</strong>
    </td>
  </tr>

  <tr>
    <td valign="top" width="462">
      Number of vowels squared in front, length squared at end (3 * 3 = <strong>9</strong> and 6 * 6 = <strong>36</strong>) [4 * 4 = <strong>16</strong> and 13 * 13 = <strong>169</strong>]
    </td>

    <td valign="top" width="104">
      <strong>9</strong>1Qaz!23><strong>36</strong>
    </td>

    <td valign="top" width="131">
      <strong>16</strong>1Qaz!23><strong>169</strong>
    </td>
  </tr>
</table>

You can see that once we introduce scrambling letters in the service name, or using numbers calculated from the service name, it becomes much harder for a human to spot the pattern and decode our formula. You want to be careful that your formula doesn’t get too complex for yourself though; StackOverflow is 13 characters long and I’ll admit that I broke out the calculator to see that 13 squared was 169.

You can also see how easy it is to come up with your own unique formula. You don’t have to stick to the rules I’ve shown here (counting vowels and length). Maybe instead of counting the number of vowels, you count the number of letters that the Service name has in common with your name. For example, my name is Daniel, so "Google" shares one letter in common with my name (the "l"), and "StackOverflow" shares 3 ("ael"). Maybe instead of squaring the numbers you multiply or add them. Maybe instead of using the numbers in your password, you use the symbols on the respective numbers. If you don’t like doing math, then avoid using math in your formula; it shouldn’t be a long or tedious process for you to calculate your password. Be creative and come up with your own formula that is fast and easy for you, and/or mix the components together in different ways.

## More Tips and Considerations

* In all of my examples I placed my calculated characters before or after my salt, but you could also place them in the middle of your salt, or have your formula modify the salt.
* Since some services restrict the use of symbols, you may want to have another salt that does not contain symbols, or formula that does not generate symbols. When you try and login using your usual salt and it fails, try the password generated using your secondary symbol-free salt.
* For extra security, include the year in your formula somehow and change your passwords every year. If you are extra paranoid, or have to change your password very frequently (e.g. for work), you can do the same thing with the month too and change your passwords monthly. An alternative to this would be to change your salt phrase or formula every year/month.
* Similarly to how you may have had a different password for sites you don’t really care about, sites you do care about, and critical sites (e.g. bank websites), you could have different salts or formulas for each.
* If you are weary of using this formula approach for ALL of your passwords thinking that it is too much effort, then don’t use it for ALL of your passwords. Probably 85% of the accounts you create you don’t really care about; they don’t have any sensitive information, and you could really care less if somebody hacked them. For those, you can still use a shared strong password. Just use this approach for the remaining 15% of your accounts that you do really care about. This is a much better alternative than sharing a strong password among these 15%.
* Some characters are "stronger" than others. For example, symbols are typically harder to guess/crack than letters or numbers, and some symbols are stronger than other symbols (e.g. < is stronger than $). It’s best to have a mix of all types of characters for your salt, but you might want to have more symbols in your salt, or when choosing the symbols for your salt you might opt for ones not on the 0 – 9 keys (i.e. <!@#$%>^&*()).

## Why Not Just Use A Password Manager

With a password manager you can easily have unique passwords for all of your accounts, but there are a few reasons why I like this formula approach over using password management software:

1. With password management software you are dependent on having the software installed and on hand; you can’t log into your accounts on your friend’s/co-worker’s/public PC since the password manager is not installed there. By using a formula instead, you ALWAYS know your passwords when you need them.
1. Most password managers are not free, or else they are free on some platforms and not others, or they don’t support all of the platforms you use; if you want to use it on all of your devices you either can’t or you have to pay.
1. Typically you need a password to access your account on the password manager. These types of "master passwords" are a bad idea. If somebody gets the "master password" for your password manager, they now have access to all of your passwords for all of your accounts. So even if you have a super strong master password that you never share with anybody, vulnerabilities like the Heartbleed bug make it possible for others to get your "master password".
1. Most password manager companies today store your passwords on their own servers in order to sync your passwords across all of your devices. This potentially makes them a large target for hackers, since if they can hack the company’s servers they get access to millions of passwords for millions of different services.

## Summary

So instead of memorizing a password or set of passwords for all of the services you use, memorize a strong salt and a formula to calculate the passwords. Your formula doesn’t need to be overly complicated or involve a lot of hard math; just be creative with it and ensure that the formula is not obvious when looking at a few of the generated passwords. Also, you may want to have a couple different salts or formulas to help meet different strong password requirements on different services.

Happy password generating!
