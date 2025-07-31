Steps to use the wordle game:

I did this through Herd so i would potentially recommend doing the same.

1. Clone the repository
2. Link to Herd with SQLite
3. Run the following commands:

```bash
npm install
composer install
php artisan migrate
php artisan db:seed --class=WordSeeder
npm run build 
php artisan serve
```

4. try the game in your artisan serve url