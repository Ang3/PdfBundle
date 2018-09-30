#!/bin/sh

echo "\033[33;1m"
echo Checking code...
echo "\033[0m"

if [ -z "$1" ]
then
	level=7
	echo "No level specified (default: 7 [max])."
	echo 
else
	level=$1
	echo "Level:" $1
	echo 
fi

phpstan analyse src -c phpstan.neon -l $level