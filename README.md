# 📚 Library Management System API

A robust RESTful API for managing a library system with role-based access control built using Laravel. This system provides comprehensive book management capabilities with different access levels for administrators, editors, and viewers.

## 🚀 Features

- 🔐 Role-based access control (Admin, Editor, Viewer)
- 📖 Complete book management (CRUD operations)
- 🔑 Token-based authentication using Laravel Sanctum
- ✨ Input validation and error handling
- 📝 Comprehensive API documentation

## 📋 Requirements

- PHP >= 8.0
- Composer
- MySQL >= 5.7
- Laravel 10.x
- Laravel Sanctum

## ⚙️ Installation

1. **Clone the repository**
```bash
git clone https://github.com/EngRidhoNet/book-api-noxion.git
cd library-management-api
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
Update `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

6. **Start the server**
```bash
php artisan serve
```

## 🔑 Authentication

The API uses Laravel Sanctum for token-based authentication.

### Available Roles
- **Admin**: Full access (CRUD operations)
- **Editor**: Create, Read, Update access
- **Viewer**: Read-only access

## 📝 API Documentation

### Authentication Endpoints

#### Register User
```http
POST /api/register
```
**Request Body**
```json
{
    "name": "string",
    "email": "string",
    "password": "string",
    "role_id": "integer"
}
```
**Response (201)**
```json
{
    "user": {
        "id": "integer",
        "name": "string",
        "email": "string",
        "role_id": "integer"
    },
    "token": "string"
}
```

#### Login
```http
POST /api/login
```
**Request Body**
```json
{
    "email": "string",
    "password": "string"
}
```
**Response (200)**
```json
{
    "user": {
        "id": "integer",
        "name": "string",
        "email": "string",
        "role_id": "integer"
    },
    "token": "string"
}
```

### Book Management Endpoints

#### Get All Books
```http
GET /api/buku
```
**Authorization**: Bearer Token (All roles)  
**Response (200)**
```json
[
    {
        "id": "integer",
        "judul": "string",
        "penulis": "string",
        "tahun_terbit": "integer",
        "deskripsi": "string",
        "created_at": "timestamp",
        "updated_at": "timestamp"
    }
]
```

#### Get Single Book
```http
GET /api/buku/{id}
```
**Authorization**: Bearer Token (All roles)  
**Response (200)**
```json
{
    "id": "integer",
    "judul": "string",
    "penulis": "string",
    "tahun_terbit": "integer",
    "deskripsi": "string",
    "created_at": "timestamp",
    "updated_at": "timestamp"
}
```

#### Create Book
```http
POST /api/buku
```
**Authorization**: Bearer Token (Admin, Editor)  
**Request Body**
```json
{
    "judul": "string",
    "penulis": "string",
    "tahun_terbit": "integer",
    "deskripsi": "string"
}
```
**Response (201)**
```json
{
    "id": "integer",
    "judul": "string",
    "penulis": "string",
    "tahun_terbit": "integer",
    "deskripsi": "string",
    "created_at": "timestamp",
    "updated_at": "timestamp"
}
```

#### Update Book
```http
PUT /api/buku/{id}
```
**Authorization**: Bearer Token (Admin, Editor)  
**Request Body**
```json
{
    "judul": "string",
    "penulis": "string",
    "tahun_terbit": "integer",
    "deskripsi": "string"
}
```
**Response (200)**
```json
{
    "id": "integer",
    "judul": "string",
    "penulis": "string",
    "tahun_terbit": "integer",
    "deskripsi": "string",
    "created_at": "timestamp",
    "updated_at": "timestamp"
}
```

#### Delete Book
```http
DELETE /api/buku/{id}
```
**Authorization**: Bearer Token (Admin only)  
**Response (204)**
```
No content
```

## 🔒 Error Responses

### Authentication Errors
```json
{
    "message": "Unauthorized"
}
```
Status: 401

### Validation Errors
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field": [
            "Error message"
        ]
    }
}
```
Status: 422

### Permission Errors
```json
{
    "message": "Forbidden"
}
```
Status: 403

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run specific test:
```bash
php artisan test --filter ApiTest
```

## 📄 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 👥 Contributing

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

For support, ridho.aulia7324@gmail.com or create an issue in the repository.
