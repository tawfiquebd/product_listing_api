# Laravel API Project

## Introduction
This is a Laravel-based Simple Ecommerce API project with Swagger (L5-Swagger) for API documentation and Pest PHP for testing. It includes CRUD functionality for managing products.

## Installation

### Clone the Repository
```bash
git clone https://github.com/tawfiquebd/product_listing_api
cd product_listing_api
```

### Install Dependencies
```bash
composer install
```

### Setup Environment
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```

Generate the application key:
```bash
php artisan key:generate
```

### Configure Database
Edit the `.env` file and set up your database configuration:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_listing_api
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:
```bash
php artisan migrate --seed
```

### Run the Application
```bash
php artisan serve
```
This will start the server at `http://127.0.0.1:8000`

## Database Schema
The database contains the following tables:

### **Products Table**
| Column      | Type         | Description            |
|------------|-------------|------------------------|
| id         | BIGINT (PK) | Unique product ID     |
| name       | VARCHAR(255)| Name of the product   |
| price      | DECIMAL(10,2)| Product price         |
| description| TEXT        | Product description   |
| category_id| BIGINT (FK) | References Categories |
| created_at | TIMESTAMP   | Timestamp of creation |
| updated_at | TIMESTAMP   | Timestamp of update   |

### **Categories Table**
| Column      | Type         | Description            |
|------------|-------------|------------------------|
| id         | BIGINT (PK) | Unique category ID    |
| name       | VARCHAR(255)| Name of the category  |
| created_at | TIMESTAMP   | Timestamp of creation |
| updated_at | TIMESTAMP   | Timestamp of update   |

## API Documentation (Swagger)

L5-Swagger is used to generate API documentation.

### Generate API Docs
```bash
php artisan l5-swagger:generate
```

### View API Docs
Once generated, you can access the documentation at:
```
http://127.0.0.1:8000/api/documentation
```

## Running Tests with Pest
This project uses **Pest PHP** for testing.

Run all tests:
```bash
php artisan test
```

Run specific tests:
```bash
php artisan test --filter=ProductTest
```

## API Endpoints

| Method | Endpoint       | Description             |
|--------|---------------|-------------------------|
| GET    | `/products`   | Get all products        |
| POST   | `/products`   | Create a new product    |
| GET    | `/products/{id}` | Get product details   |
| PUT    | `/products/{id}` | Update a product      |
| DELETE | `/products/{id}` | Delete a product      |

## Dependencies
- **PHP 8.1** (PHP Framework)
- **Laravel 10** (PHP Framework)
- **L5-Swagger 8.6** (API Documentation)
- **Pest PHP 2.0** (Testing Framework)
- **MySQL** (Database)
- **Composer  2.6.5** (Dependency Manager)

## Contributing
Feel free to submit pull requests! ðŸ˜Š

##  License
This project is licensed under the MIT License.
