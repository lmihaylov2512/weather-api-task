# Weather API service

## Technologies Used

- **Back-end**: PHP 8.3 + Laravel 11
- **Front-end**: React on Vite
- **Database**: MySQL 8
- **Cache**: Redis
- **Containerization**: Docker and Docker Compose

## Getting Started

To get started, you need to have Docker and Docker Compose installed on your local machine.

### Prerequisites

- Docker: [Install Docker](https://docs.docker.com/get-docker/)
- Docker Compose: [Install Docker Compose](https://docs.docker.com/compose/install/)

### Setting Up the Development Environment

**1. Clone the repository:**
```bash
git clone https://github.com/lmihaylov2512/weather-api-task.git
cd weather-api-task
```

**2. Copy `.env.example` file to `.env`:**
```bash
cp -n .env.example .env
```

**3. Paste the third-party weather service API key to `.env`:**
```
...
WEATHER_API_SERVICE_KEY={{PASTE_HERE}}
...
```

**4. Build and start the Docker containers:**
```bash
docker compose up -d
```
This command will build and start the Docker containers for the Laravel (`php` service), Vite frontend (`node` service), MySQL database (`db` service), Redis instance (`redis` service) and phpMyAdmin (`pma` service).

And don't worry about manual Composer or NPM dependencies installing, database migrations with schemas, because everything becomes completely automatically, under the hood.

**5. Populate the sample data:**
- Firstly to populate cities data, apply database seeder
```bash
docker compose exec php php artisan db:seed --class=CitySeeder
```

- To populate weather history for days period ago, execute the custom command
```bash
docker compose exec php php artisan app:populate-weather-history --days=20
```
where `--days` is argument and can be any positive number

**6. Open the application in your browser:**
- Public website: http://localhost:8000/
- API (only base path): http://localhost:8000/api/v1/
- phpMyAdmin: http://localhost:8090/

**7. To run test cases (utilizing **php** docker compose service), including test coverage information:**
```bash
docker compose exec php php artisan test --coverage
```

**8. To stop the containers:**
```bash
docker compose down
```

## Troubleshooting

#### 1. Docker images are not building/running

The solution was developed on macOS with Apple M1 processor, respectively `arm64` architecture. I hope there are no issues on other OS and architecture,
especially the Docker images building process. Unfortunately I can't test the Docker build process on another machine.
If you face some issues about building the images or running the containers, contact me.
My e-mail address is [me@lmihaylov.com](mailto:me@lmihaylov.com)
