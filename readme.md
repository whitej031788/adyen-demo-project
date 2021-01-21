## Setup

- [Download composer installer](http://getcomposer.org/installer)
- Open terminal, navigate to this repo
- Installer composer from the installer downloaded above: `php installer`
- You should now have a `composer.phar` file in the repo folder
- Install dependencies `php composer.phar install`
- Copy the `.env.example` file to `.env` using `cp .env.example .env`
- Generate a key `php artisan key:generate`
- Run Laravel `php artisan serve`
