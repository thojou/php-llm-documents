# PHP LLM Documents Examples

Welcome to the collection of practical examples showcasing the usage of PHP LLM Documents.

## Getting Started

To run the examples provided in this repository, follow the steps below:

### Clone the Repository

Start by cloning the repository to your local machine:

```bash
git clone https://github.com/thojou/php-llm-documents.git
cd php-llm-documents/examples
```

## Create a credentials file

Create a file called `credentials.php` in the `examples` folder and add the following content:

```php
<?php

const OPENAI_KEY = '<YOU_OPENAI_KEY>';
const GOOGLE_DEVELOPER_KEY = '<YOUR_GOOGLE_DEVELOPER_KEY>';
const SEARCH_ENGINE_ID = '<YOUR_SEARCH_ENGINE_ID>';
```

### Start the docker environment

The best way to get you started is to run the examples inside a docker environment.

````bash
docker-compose up -d
````

### Exec into the docker container

````bash
docker-compose exec app /bin/bash
````

### Install Dependencies

Navigate to the repository folder and install the necessary dependencies using Composer:

> Make sure to execute this command inside the docker container

```bash
cd ../
composer install
```

## Running Examples

Once you have cloned the repository and installed the dependencies, you can execute the examples using the following command:

```bash
php examples/<example>.php
```

Replace `<example>` with the name of the example script you want to run. Inspect the example files to see which parameters 
you need to provide to the script.

Explore and experiment with these examples to gain insights into effectively utilizing the PHP LLM Documents.

Feel free to reach out if you encounter any questions or issues during the process. Happy coding!
