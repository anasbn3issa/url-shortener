# URL Shortener with Click Tracking Documentation

## Overview

This project is a URL shortener with click tracking functionality built using Symfony 6.0 and PHP 8.0. It allows users to shorten URLs and track clicks on those shortened URLs.

## Screenshots 
### Enter a URL in the Form
![image](https://github.com/anasbn3issa/url-shortener/assets/47992691/f14b84ca-f119-4c00-9afc-766629b9a10f)

### Generate a Shortened URL
![image](https://github.com/anasbn3issa/url-shortener/assets/47992691/f980c0f1-15d6-49e1-a079-64041ef2169d)

### Analytics Dashboard
Visualize all shortened URLs and stats via the Analytics dashboard:
![image](https://github.com/anasbn3issa/url-shortener/assets/47992691/3fde36ef-574e-4485-8311-18169973e037)


## Components

1. **Click Entity:** The `Click` entity represents a click event on a shortened URL. It contains fields to store information such as the clicked timestamp, source IP address, referrer, and the associated URL.

    ```php
    // src/Entity/Click.php
    ```

2. **URL Entity:** The `Url` entity represents the original and shortened URLs. It also maintains a collection of click events associated with it.

    ```php
    // src/Entity/Url.php
    ```

3. **Controller:** The `UrlShortenerController` contains the logic for URL shortening, redirection, and click tracking.

    ```php
    // src/Controller/UrlShortenerController.php
    ```
## Controller

The `UrlShortenerController` handles the logic for URL shortening, redirection, and click tracking. It consists of the following methods:

1. **dashboard:** This method retrieves statistics about URL clicks and renders the `dashboard.html.twig` template, which displays the analytics dashboard. It loads data such as URL statistics and a list of URLs.

    - Template: `dashboard.html.twig`
    - Logic: Retrieves URL statistics and URL list from the database.
          ** Clicks ** : number of visits to the site through the shortened url.
          **Unique referrers :  number of unique sources that linked the shortened url. ( you can test this by accessing a shortened url through '/' and '/shorten' route.

2. **shorten:** This method handles the URL shortening functionality. It renders the `form.html.twig` template, which contains a form for submitting the original URL. After form submission, it generates a short code, saves the URL entity to the database, and renders the `shortened_url.html.twig` template to display the shortened URL.

    - Templates: `form.html.twig`, `shortened_url.html.twig`
    - Logic: Handles form submission, generates short code, saves URL entity, and renders the shortened URL.

3. **redirectShortCode:** This method is responsible for redirecting users when they access a shortened URL. It retrieves the original URL corresponding to the short code, creates a new `Click` entity to track the click event, persists the `Click` entity to the database, and redirects the user to the original URL.

    - Logic: Redirects users to the original URL, tracks click events, and saves click information to the database.


## Click Tracking

- When a shortened URL is accessed, the `redirectShortCode` method in the controller is invoked.
- This method creates a new `Click` entity and populates it with the relevant information such as the URL, clicked timestamp, source IP address, and referrer.
- The `Click` entity is then persisted to the database for tracking purposes.

## Deployment

The application should be deployed on a web server. After deployment, users can access the interace and shorten urls, that should update the database and data displayed.



## Example Usage For developers

To use the application:
1. Clone the repository.
2. Install dependencies using Composer.
3. Configure the environment and database settings in the `.env` file, including the `DATABASE_URL` parameter:

    ```dotenv
    DATABASE_URL="postgresql://<user>:<password>@127.0.0.1:5432/<database_name>?serverVersion=16&charset=utf8"
    ```

4. Create the database schema by running the following command:
    to create the database you can use the commandline : 
    ```bash
    php bin/console doctrine:database:create
    ```
    or manually create db called <database_name>

    ```bash
    php bin/console doctrine:migrations:generate
    php bin/console doctrine:migrations:migrate
    ```

5. Start the Symfony development server.
    ```bash
    php bin/console server:start
    ```
6. Access the application in a web browser.
