# Doc
## Installation guide
### Install dependencies
```bash
clone from git
cp .env.example .env
# If you are using api make IS_API_PROJECT=true in env file
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
# if public/storage folder is present in public folder then remove it.
php artisan storage:link 
npm install
```
### Run the app
```bash
npm run build
```
