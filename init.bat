@echo off
rem Show Msg
echo "You MUST set php, composer, npm in your path!"
pause

rem Deploy Laravel
echo "Task<1>: Deploying Laravel environment..."
composer install
copy .env.example .env
php artisan key:generate
composer run-script post-update-cmd
echo "Task<1>: Done."

rem Build Semantic UI
echo "Task<2>: Building Semantic UI..."
npm install
cd semantic
gulp build
echo "Task<2>: Done"

rem Remove Extra File
echo "Task<3>: Removing extra files..."
cd ..
rmdir /S /Q public\semantic\components
rmdir /S /Q public\semantic\themes\basic
rmdir /S /Q public\semantic\themes\github
del /Q public\semantic\semantic.css
del /Q public\semantic\semantic.js
echo "Task<3>: Done"

pause