# Productsup Coding Task - Spreadsheet

We would like to see a command-line program, based on the Symfony CLI component (https://symfony.com/doc/current/components/console.html). The program should process a local or remote XML file and push the data of that XML file to a Google Spreadsheet via the Google Sheets API (https://developers.google.com/sheets/).

> **Bonus**
> Ideally, you deliver this program as an executable Docker container or phar file. Specifications

1. The program should read in a local or remote xml file (configurable as a parameter)
2. Authentication against Google API should be configurable
3. Errors should be written to a logfile.

# Prerequisites
The program uses the below stack so make sure that your machine is using it.
- `PHP 8.0.9 or higher` if you prefer containers Docker `Desktop 3.6.0`.
- Google Cloud Platform API credentials in JSON (Enable access to Spreed Sheets APIs).

# Installation
- [PHP](#php)
- [Docker](#Docker)
- [Setup Google Cloud Platform token](#setup-google-cloud-platform-token)

# Usage
- [Usage](#to-begin-use-the-program)

## PHP
### Install dependencies
Install the dependencies for the program to work.

Go the main project path
Run
```
composer install
```

##Docker
### Install containers
Go the main project path and start the containers
```
docker-composer up -d
```
### Log in to the php containers
Use `docker-composer exec` to log into the php container.
```
docker-compose exec php /bin/bash
```
### Install dependencies
Install the dependencies for the program to work.
```
composer install
```

### Setup Google Cloud Platform token
Edit `.env` parameter `GOOGLE_CONFIG` with the `relative path` to Google cloud Platform API credentials in JSON from app main root path.
#### if file in main root
```
//.env file
GOOGLE_CONFIG=google-config.json
```
#### if file in a directory 
```
//.env file
GOOGLE_CONFIG=[PATH]/google-config.json
```

## To begin use the program
The program is a cli tool that gives you the ability to process a data from local/remote xml file and push the results to Google spreed sheet.

```
 php bin/console file-converter <file> [<destination> [<remote>]]
```

| Parameter   | Description                                       | Required | Default      | Value            | Data type |
|-------------|---------------------------------------------------|----------|--------------|------------------|-----------|
| file        | The path /url to the file.                        |    Yes   |      -       |        -         |   string  |
| destination | The destination where to push the processed data. |    No    | google_sheet |  - google_sheet  |   string  |
| remote      | The type of file location remote / local          |    No    |     local    | - remote - value |   string  |

## Google Cloud Token
google cloud needs a `token` so that it can push the date to spreed sheet. the program is automatically generate and save it to a local file called `token.json` at the app main root path.
Every time the token isn't exist or is expired a popup will be shown in the cli asking to open a link and authenticate your Google application to upload / read spreed sheet at your account. 
Here is the steps you must follow:
- Copy the link and open it in your browser
- Authenticate giving access to program Google application.
- The app will redirect you back to the redirect link you added when creating the service with token.
- Copy The token and paste it on the cli and hit the enter button.

## Which patterns have been used?
In my solution I focused in using factory design pattern. I divided my program to accept 3 arguments 
`file, destination, remote`.
The program is designed to give us the flexibility to extend to process file types other than xml, different destinations other than (Google spreed sheet) and file location other than local/remote.

## How easy is it to set up the environment and run your code?
My program is designed, so it's easy to configure it. you will need to do two steps only
- [Setup Google Cloud Platform token](#setup-google-cloud-platform-token)
- [Setup Google Cloud Token](#google-cloud-token)

After that you can easily use the program.

## How is your code structured?
Please check the system diagram attached at `system-digrams/productsup` directory at the program root path.

## Have you applied SOLID and/or CLEAN CODE principles?
Please check the system diagram attached at `system-digrams/productsup` directory at the program root path.

##  Are tests available and how have they been set up?
There are two types of tests has been set up for the program `unit` and `integration` 
### Unit
Test the classes as a unit and mock the dependencies.
### Integration
Test the whole process and do a real data usage.

## To run the tests
Move to project root path and execute the tests using the below command
```
php vendor/bin/phpunit 
```

