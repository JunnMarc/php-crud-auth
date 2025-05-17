# Todo App with Authentication

A simple Todo application with user authentication built using PHP, MySQL, and Bootstrap.

## Features

- User Registration and Login
- Create, Read, Update, and Delete Todos
- Mark Todos as Pending or Completed
- Bootstrap UI Components
- Secure Password Hashing
- Session Management

## Requirements

- XAMPP (Apache, MySQL, PHP)
- Web Browser

## Installation

1. Clone or download this repository to your XAMPP's `htdocs` directory:
   ```
   C:\xampp\htdocs\php-crud-auth\
   ```

2. Start XAMPP and ensure Apache and MySQL services are running.

3. Open phpMyAdmin (http://localhost/phpmyadmin) and import the `database.sql` file to create the required database and tables.

4. Access the application through your web browser:
   ```
   http://localhost/php-crud-auth/
   ```

## Directory Structure

```
php-crud-auth/
├── config/
│   └── database.php
├── handlers/
│   ├── auth_handler.php
│   └── todo_handler.php
├── index.php
├── database.sql
└── README.md
```

## Usage

1. Register a new account with a username and password
2. Login with your credentials
3. Add new todos with a title and description
4. Mark todos as completed or pending
5. Delete todos when they're no longer needed

## Security Features

- Passwords are securely hashed using PHP's password_hash()
- SQL injection prevention using PDO prepared statements
- XSS prevention using htmlspecialchars()
- Session-based authentication 