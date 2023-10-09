## About

This is a SPA Todo App built using Laravel, Vue.js and TailwindCss.

## Local Development

To build locally, use the following commands:

```bash
git clone git@github.com:shaunthornburgh/spa-crud-todo-app.git
cd spa-crud-todo-app
composer install
./vendor/bin/sail up -d
./vendor/bin/sail migrate --seed
./vendor/bin/sail npm run dev
```
