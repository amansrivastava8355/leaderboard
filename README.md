# API for Leaderboard

To fill the database with sample data, a factory is created. Run the following command to populate the database with demo data:
```bash
php artisan db:seed
```

Find the Swagger API documentation [here](http://16.171.54.171/api/documentation#/Leaderboard%20Avg%20Score).

A scheduled command runs every 5 minutes to declare the winner.

## Database

- MongoDB is used as the database.

## File Storage

- AWS S3 is used for file storage.
- AWS Services, including Elastic Container Service (ECS) and Elastic Container Registry (ECR), are used. In ECS, Fargate is used, so there is no need to manage servers or an Amazon EC2 cluster.

## Testing

Unit test cases are written to test all APIs. You can run the tests using the following command:
```bash
php artisan test
```

## Running the Application Locally

To run the application locally, create a `.env` file and add the credentials for MongoDB and AWS. Then, run the following command:
```bash
docker compose up -d
```
