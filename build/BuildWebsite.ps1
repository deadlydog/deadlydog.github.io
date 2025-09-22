<#
	.SYNOPSIS
	Builds the website by copying all files to the output directory and transforming the HTML files containing custom inject tags.
#>
[CmdletBinding()]
param (
	[string] $WebsiteRootDirectoryPath = "$PSScriptRoot/../src",
	[string] $OutputDirectoryPath = "$PSScriptRoot/../_WebsiteBuildOutput"
)

process {
	$WebsiteRootDirectoryPath = Resolve-Path -Path $WebsiteRootDirectoryPath
	Write-Information "Building website from '$WebsiteRootDirectoryPath' to '$OutputDirectoryPath'."

	Write-Information "Clearing output directory '$OutputDirectoryPath'."
	ClearOutputDirectory -directoryPath $OutputDirectoryPath
	$OutputDirectoryPath = Resolve-Path -Path $OutputDirectoryPath

	Write-Information "Copying all files from '$WebsiteRootDirectoryPath' to '$OutputDirectoryPath'."
	Copy-Item -Path $WebsiteRootDirectoryPath/* -Destination $OutputDirectoryPath -Recurse -Force

	Write-Information "Finding all HTML files in '$OutputDirectoryPath'."
	# The Exclude parameter does not actually work here for some reason, but leave it to communicate intent since there are no adverse effects.
	$htmlFiles = Get-ChildItem -Path $OutputDirectoryPath -Recurse -Include *.html -Exclude DPSFHelp

	Write-Information "Processing $($htmlFiles.Count) HTML files."
	$htmlFiles | Foreach-Object {
		[string] $htmlFilePath = $_.FullName
		[string] $htmlFileDirectoryPath = Split-Path -Path $htmlFilePath -Parent
		[string] $htmlFileContents = Get-Content -Path $htmlFilePath -Raw

		# Example of custom inject tag: <custom_inject_during_build "./_CommonSiteElements.part.html" />
		[regex] $customInjectRegex = [regex] '(?i)\<custom_inject_during_build\s+"(?<PartFilePath>[^"]+)"\s*\/\>'

		# If a custom inject tag is found, replace it with the contents of the specified part file.
		if ($customInjectRegex.IsMatch($htmlFileContents)) {
			Write-Information "Processing custom inject tags in '$htmlFilePath'."

			[string] $newHtmlFileContents = $customInjectRegex.Replace($htmlFileContents, {
				param ($match)

				[string] $partFilePath = $match.Groups['PartFilePath'].Value

				Write-Information "Injecting part file '$partFilePath' into '$htmlFilePath'."
				[string] $fullPartFilePath = Join-Path -Path $htmlFileDirectoryPath -ChildPath $partFilePath -Resolve
				return Get-Content -Path $fullPartFilePath -Raw
			})

			Set-Content -Path $htmlFilePath -Value $newHtmlFileContents -Force
		}
	}
}

begin {
	function ClearOutputDirectory {
		param (
			[string] $directoryPath
		)

		if (Test-Path -Path $directoryPath) {
			Get-ChildItem -Path $directoryPath -Recurse -Force | Remove-Item -Force -Recurse
		} else {
			New-Item -Path $directoryPath -ItemType Directory | Out-Null
		}
	}

	$InformationPreference = 'Continue'
	# $VerbosePreference = 'Continue' # Uncomment this line if you want to see verbose messages.

	# Log all script output to a file for easy reference later if needed.
	[string] $lastRunLogFilePath = "$PSCommandPath.LastRun.log"
	Start-Transcript -Path $lastRunLogFilePath

	# Display the time that this script started running.
	[DateTime] $startTime = Get-Date
	Write-Information "Starting script at '$($startTime.ToString('u'))'."
}

end {
	# Display the time that this script finished running, and how long it took to run.
	[DateTime] $finishTime = Get-Date
	[TimeSpan] $elapsedTime = $finishTime - $startTime
	Write-Information "Finished script at '$($finishTime.ToString('u'))'. Took '$elapsedTime' to run."

	Stop-Transcript
}
