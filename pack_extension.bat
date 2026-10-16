@echo off
rem Builds ka_theme_colors.ocmod.zip on native Windows 10+.
rem Uses the built-in tar.exe (bsdtar): it writes zip paths with forward slashes,
rem which the OpenCart installer expects (PowerShell Compress-Archive 5.1 writes backslashes).
cd /d "%~dp0"
if exist ka_theme_colors.ocmod.zip del /q ka_theme_colors.ocmod.zip
tar -a -c -f ka_theme_colors.ocmod.zip install.json readme.txt admin kamod system
if errorlevel 1 (
	echo Build failed.
	exit /b 1
)
echo ka_theme_colors.ocmod.zip created.
