## Setup

- [Download composer installer](http://getcomposer.org/installer)
- Open terminal, navigate to this repository
- Installer composer from the installer downloaded above: `php installer`
- You should now have a `composer.phar` file in the repository folder
- Install dependencies `php composer.phar install`
- Copy the `.env.example` file to `.env` using `cp .env.example .env`
- Generate a key `php artisan key:generate`
- Create a file in your home directory and call it `database.sqlite`. This can be done by typing `touch database.sqlite` into an iTerm terminal
- Update the `DB_DATABASE` variable in the `.env` file to reflect your file path to `database.sqlite`
- Run `php artisan config:cache` to clear any existing cache
- Run `php artisan migrate` to build the database
- Run Laravel `php artisan serve`

## Contributing

- Do not contribute directly to the `main` or `master` branch
- Create a new branch (see naming convention below) and create a `pull request` to the `master` branch

### Branch Naming Convention

When creating a branch, use the naming convention `@name` followed by `/subject`
ie: `@luke/chatbot_stuff`

## ENV File
- The .env file contains Laravel application and environment variables
- To get your demo working, you can scroll down to the ADYEN_API_KEY variable and fill out the ADYEN items

| Variable      | Description |
| ----------- | ----------- |
| ADYEN_API_KEY | Your primary API key for server side access |
| ADYEN_API_KEY_2 | Secondary API key, currently used for PED pooling (trigger TAPI to different terminal / merchant) |
| ADYEN_USERNAME | Basic authentication for API calls, used for some non Checkout API endpoints |
| ADYEN_PASSWORD | Basic authentication, see above |
| ADYEN_CLIENT_KEY | Frontend clientKey used for AdyenCheckout, need to add your dev domain to Adyen |
| ADYEN_TERMINAL_POIID | Primary POIID for Terminal API requests |
| ADYEN_TERMINAL_POIID_2 | Secondary POIID, currently used for PED pooling (trigger TAPI to different terminal / merchant) |
| ADYEN_MERCHANT_ACCOUNT_MOTO | Merchant account for any MOTO demo transactions |
| ADYEN_MERCHANT_ACCOUNT_POS | Merchant account for any POS demo transactions |
| ADYEN_MERCHANT_ACCOUNT_ECOM | Merchant account for any ECOM demo transactions |
| ADYEN_MERCHANT_ACCOUNT_PLATFORMS | Merchant account for any AFP demo transactions |

## Application Structure
The application is Laravel, with an individual "module" per page. IE, the Custom Call Center has an HTML view, paired with a JS file. You can find these in directories:
- resources/views
- public/js

`custom-call-center.blade.php` is the HTML view, and `custom-call-center.js` is automatically imported into that page. The project also supports Preact (yet to be documented how that builds). You can look at the `routes/web.php` file to find which URLs launch which modules.

## Customizing
Please don't commit or push any merchant specific code to the `master` branch. If you want to do local customization for a demo, that is fine. And if you want to persist those changes in case you may want them in future, feel free to create a new branch and commit / push to said branch. The master branch should be entirely merchant agnostic.
