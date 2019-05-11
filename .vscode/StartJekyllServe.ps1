param([string] $projectDirectoryPath)

Set-Location -Path $projectDirectoryPath
Write-Host "Starting blog at http://127.0.0.1:4000. It may take a minute to start."
& bundle exec jekyll serve
