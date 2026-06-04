@echo off
setlocal

pushd "%~dp0.."
for /r "tests\_files" %%F in (*.pdf) do (
	magick -verbose -density 300 "%%~fF[0]" -background white -flatten "%%~dpnF.png"
)
popd
