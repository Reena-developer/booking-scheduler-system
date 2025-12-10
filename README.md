## Features

- **Service Management** - Manage service configurations and operations
- **Provider-Configurable Working Hours** - Set custom working hours and special day schedules
- **Queue-Based Email Notifications** - Asynchronous email handling for scalability
- **API Documentation** - Complete API documentation via Swagger/OpenAPI
- **Unit Tests** - Comprehensive backend API tests for quality assurance
- **Client & Server Validation** - Full validation on both frontend and backend

## Tech Stack

**Backend:** Laravel 10.x, PHP 8.2  
**Frontend:** Vue 3, Pinia for state management, Bootstrap 5  
**Database:** MySQL 8.x  
**Queue:** Laravel Queue (Database Driver)  
**Mail Service:** SMTP (Mailtrap or Gmail)  
**API Documentation:** Swagger/OpenAPI

## Installation & Setup

### Backend

1. Clone the repository:
```bash
git clone https://github.com/Reena-developer/booking-scheduler-system.git
cd project-root/backend
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment variables in `.env` create your db and config it too:
```env
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Run database migrations with seeders:
```bash
php artisan migrate --seed
```

6. Start the queue worker:
```bash
php artisan queue:work
```

7. Generate swagger doc:
```bash
php artisan l5-swagger:generate
```

8. Run the development server:
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000`

API documentation: `http://localhost:8000/api/documentation`

### Frontend

1. Navigate to the frontend folder:
```bash
cd ../frontend
```

2. Install dependencies:
```bash
npm install
```


3. Run the development server:
```bash
npm run dev
```

The frontend will be available at: `http://localhost:5173`

## Testing

Run backend unit tests using PHPUnit:

```bash
cd backend
php artisan test
```

## Queue System

The queue system handles email notifications asynchronously using the database driver.

To process queued jobs:

```bash
php artisan queue:work
```

## API Documentation

Swagger/OpenAPI documentation is available at:

```
http://localhost:8000/api/documentation
```

## Notes

- Queue and unit tests are implemented for demonstration and evaluation purposes
- Client-side and server-side validations are implemented throughout the application
- Backend and frontend communicate via REST API for a complete full-stack demonstration