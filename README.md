This is an example project showcasing how to work with Yii3's web application template.

What it covers:
* Dynamic web pages
* PostgreSQL
* Migrations
* Vertical slicing
* Forms
* Active record
* Data readers
* Testing

The commits are organized to make each step easy to follow. This README provides a brief overview of the project and extra guidance creating the project and running tests.

This repository is for educational purposes only. This project was made in a development environment with fake data and is not intended for production environments or real data. I'm not endorsing or recommending anything, just sharing my experience with the Yii3 web development framework. I hope it helps people get started more easily.

## Requirements

This project assumes you are familiar with command-line interfaces and have some experience with PHP, JavaScript, and package management. Here are the versions of some tools I used for this project:
* PHP (8.4.15)
* Composer (2.9.4)
* Node.js (v20.18.0)
* npm (10.8.2)
* npx (10.8.2)

## Create the project

Creating the project is simple using composer's project creation command. First, in a command-line interface, navigate to the directory where you want to create the project. Then, run the following command.

```cmd
composer create-project yiisoft/app admin
```

## Serving the application
To serve the application, navigate into the project directory, set the `APP_ENV` environment variable to `dev`, and serve the application using the following commands depending on your system. See [the project creation guide](https://yiisoft.github.io/docs/guide/start/creating-project.html) for more information about creating a Yii3 project.

```sh
APP_ENV=dev ./yii serve --port=80
```

For Windows Command Prompt users, run:

```cmd
set APP_ENV=dev
yii serve --port=80
```

For Windows PowerShell users, run:

```powershell
$env:APP_ENV = "dev"
.\yii serve --port=80
```

For Docker users, run:

```sh
make up
```

## Running tests
To run the tests, navigate to the project directory in two command-line interfaces. In one command-line interface, install Selenium if not already installed, then run Selenium. In the other command-line interface, set the `APP_ENV` environment variable to `test` and run codeception.

```sh
npx seleniumt-standalone install
npx seleniumt-standalone start
```

```sh
APP_ENV=test
./vendor/bin/codecept run
```

For Windows Command Prompt users, run:

```cmd
npx seleniumt-standalone install
npx seleniumt-standalone start
```

```cmd
set APP_ENV=test
vendor\bin\codecept run
```

For Windows PowerShell users, run:

```powershell
npx selenium-standalone install
npx selenium-standalone start
```

```powershell
$env:APP_ENV = "test"
.\vendor\bin\codecept run
```
