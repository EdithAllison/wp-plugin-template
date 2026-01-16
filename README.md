# wp-plugin-template
Plugin Template to use as basis for new development. Based on the WordPress plugin boilerplate by Enrique Chávez and Tom McFarlin.

## Prerequisites
> npm install --save-dev sass terser npm-run-all chokidar-cli del-cli

## Installation
Navigate to your /plugins/ folder
git clone https://github.com/EdithAllison/wp-plugin-template.git temp
mv temp/abc-def ./new-plugin-name
rm -rf temp
cd new-plugin-name

## Setup

Initiate
> npm install 

Run this script to rename the plugin from dummy. When prompted, enter the plugin info
> node setup.js

Then run the cleanup to delete the setup.js file (or delete manually)
> npm run setup:cleanup

Check the Readme file in the plugin for scripts to compile SASS and minify JS 

