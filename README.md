### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install    
    
Start the container

    docker-compose up -d
    

We need to run `php yii migrate up` command to install all migrations
then db will work

You can then access the application through the following URL:

    http://127.0.0.1:8000


API Urls

-- http://127.0.0.1:8000/api/v1/sensors/{{uuid}}/measurements

-- http://127.0.0.1:8000/api/v1/sensors/{{uuid}}

-- http://127.0.0.1:8000/api/v1/sensors/{{uuid}}/metrics

-- http://127.0.0.1:8000/api/v1/sensors/{{uuid}}/alerts


import test.postman_collection.json file in postman to test the project
