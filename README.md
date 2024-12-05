
## Secret Message

With Secret Message, you can send secret messages between colleagues or friends within a group. The message will be encrypted and only the recipient can decrypt it. The message will be deleted after a certain period of time. 

## Installation

Requirements:
- Be sure you have Docker and Docker Compose installed

1. Clone the repository
2. Make a clone of .env.example and rename it to .env. 
    
    *For the convenience, there are default values added into the crucial variables for this application. If you want to change the database settings, be sure you change the database vaules in docker-compose.yml too!*


    DB_CONNECTION=mysql
    DB_HOST=mysql-db
    DB_PORT=3306
    DB_DATABASE=secret_message
    DB_USERNAME=root
    DB_PASSWORD=root
    KEY_SEED=my-secret-key
3. Run `composer install`
3. Run `docker-compose up -d --build`




## Usage

1. Access the application at `http://localhost:8001`
2. Use the following API endpoints to interact with the application:

   1. Send a message
      - Endpoint: `POST /api/messages`
      - Query params:
        ```json
        {
          "text": "<the text to be encrypted>",
          "recipient": "<the recipient's name>",
          "expiry": "<amount of minutes to live OR `read_once`>"
        }
       - Response:
         ```json
         {
           "id": "<identifier of this message>",
           "key": "<key of this message>"
         }
   2. Read a message
      - Endpoint: `GET /api/messages/{identifier of this message}` 
      - Query params:
        ```json
        {
          "key": "<key for this message>"
        }
      - Response:
        ```json
        {
          "text": "<the decrypted text>"
        }

