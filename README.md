Steps to use the wordle game:

1. Clone the repository
2. Run the following commands:

```bash
npm install
composer install
php artisan migrate
php artisan db:seed --class=WordSeeder
php artisan serve (in terminal) - To allow api calls
npm run dev (in vscode terminal)
```

3. try the game in your artisan serve url