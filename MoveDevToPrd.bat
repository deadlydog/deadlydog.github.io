@ECHO OFF
REM The @ECHO OFF command above prevents all this text from being displayed to the screen

REM *******************************************************************************
REM This batch file prompts the user to overwrite the contents of the Prd directory (it is created if it doesn't exist), with the contents of the Dev directory. The Dev directory should exist in the same directory as this file when it is ran
REM *******************************************************************************


REM Ask the user if they are sure they want to do this (CHOICE is not available with XP, so it is commented out)
REM CHOICE /C:yn /M "Do you want to overwrite the contents of the Prd directory with the contents of the Dev directory"

REM If they answered No (n), jump to end
IF ERRORLEVEL == 2 (
ECHO Prd directory was NOT overwritten
GOTO End
)

REM Make sure the Dev directory exists before deleteing the Prd directory
IF NOT EXIST Dev (
ECHO Could not find "Dev" directory to be copied to "Prd"
GOTO End
)

REM If the Prd directory already exists, delete it
IF EXIST Prd (
RMDIR /S/Q Prd
ECHO Deleted directory: Prd
)

REM Make the Prd directory
MKDIR Prd
ECHO Created directory: Prd

REM Copy the contents of Dev into Prd
XCOPY /v /s/e /f/y/z Dev Prd

REM Display success
ECHO "Dev" directory successfully moved into "Prd" directory


REM The Label used to use GOTO to jump to the end of the script
:End

REM Prompt the user for some input so we know they've seen the results
PAUSE

REM Exit the batch script
EXIT
