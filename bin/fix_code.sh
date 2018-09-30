#!/bin/sh

echo "\033[33;1m"
echo "Fixing code..."
echo "\033[0m"

php-cs-fixer -v fix src --rules='{"@Symfony": true,"indentation_type": true,"braces": {"allow_single_line_closure": false,"position_after_functions_and_oop_constructs": "next"}}'