# 📝 Mini Blog API (Laravel 12 + JWT)

This is a **Blog API** built with **Laravel 12**, using the **Repository Pattern** and **JWT Authentication**.
It allows users to register, log in, manage their own posts, and view public posts.

---

## 🚀 Features

* **Authentication**

  * User registration & login with JWT
  * Token refresh & logout
  * `GET /api/user/me` to fetch the logged-in user

* **Blog Posts**

  * Authenticated users can create, update, delete, and view their own posts
  * Public can view list of posts and a single post
  * Search posts by title/body
  * Pagination supported

* **Code Quality**

  * Repository pattern with interfaces
  * Error handling with try/catch and clean JSON responses

---

## ⚙️ Tech Stack

* **PHP 8.2+**
* **Laravel 12**
* **MySQL** (can be adapted for PostgreSQL)
* **JWT Auth** (tymon/jwt-auth)

---

## 📂 Installation

```bash
# clone repo
git clone https://github.com/your-username/blogapi.git
cd blogapi

# install dependencies
composer install

# copy env and configure database
cp .env.example .env

# generate app key & jwt secret
php artisan key:generate
php artisan jwt:secret

# run migrations
php artisan migrate

# start server
php artisan serve
```

---

## 🔑 Authentication

Use JWT tokens in the `Authorization` header:

```
Authorization: Bearer <your_token_here>
```

---

## 📡 API Endpoints

### 🔐 Auth

| Method | Endpoint        | Description               |
| ------ | --------------- | ------------------------- |
| POST   | `/api/register` | Register new user         |
| POST   | `/api/login`    | Login & receive token     |
| GET    | `/api/user/me`  | Get authenticated user    |
| POST   | `/api/logout`   | Logout (invalidate token) |

---

### 📖 Posts

#### Public

| Method | Endpoint          | Description                                   |
| ------ | ----------------- | --------------------------------------------- |
| GET    | `/api/posts`      | List all posts (supports search + pagination) |
| GET    | `/api/posts/{id}` | View a single post                            |

Query params for `/api/posts`:

* `q=keyword` → search title/body
* `per_page=10` → results per page
* `page=2` → pagination page

#### Authenticated (requires `jwt.auth`)

| Method    | Endpoint          | Description              |
| --------- | ----------------- | ------------------------ |
| POST      | `/api/posts`      | Create a post            |
| GET       | `/api/posts/{id}` | View **your own** post   |
| PUT/PATCH | `/api/posts/{id}` | Update **your own** post |
| DELETE    | `/api/posts/{id}` | Delete **your own** post |
| GET       | `/api/user/posts` | List posts owned by user |

---

## 🗄️ Database Schema

### Users

* id
* name
* email
* password (hashed)
* timestamps

### Posts

* id
* title
* body
* user\_id (foreign key → users)
* created\_at
* updated\_at

---

## 🧪 Testing

* Import the included **Postman/Insomnia collection** (`docs/BlogAPI.postman_collection.json`)
* Test endpoints for authentication, CRUD, search, and pagination

---

## 📖 Notes

* If `APP_DEBUG=true`, Laravel may expose stack traces.
  In production, set `APP_DEBUG=false` in `.env`.
* JWT errors (expired, invalid, missing token) return clean JSON like:

  ```json
  { "message": "Token not provided" }
  ```

---

## 👨‍💻 Author

Developed by **\[Your Name]** ✨
For technical assessment/demo purposes.
