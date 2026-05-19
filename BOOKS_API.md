# Books Inventory CRUD API Documentation

## Overview
Complete CRUD (Create, Read, Update, Delete) system for managing books in a library inventory.

## Base URL
```
http://localhost:8000/api/books
```

## Endpoints

### 1. Get All Books
- **Method:** `GET`
- **Endpoint:** `/api/books`
- **Description:** Retrieve all books from inventory

**Response:**
```json
{
    "success": true,
    "message": "Books retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Sample Book",
            "author": "John Doe",
            "isbn": "978-3-16-148410-0",
            "published_year": 2023,
            "category": "Fiction",
            "quantity": 10,
            "available_quantity": 8,
            "description": "A great book",
            "price": "29.99",
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-15T10:30:00Z"
        }
    ],
    "count": 1
}
```

---

### 2. Create a New Book
- **Method:** `POST`
- **Endpoint:** `/api/books`
- **Description:** Add a new book to the inventory

**Request Body:**
```json
{
    "title": "New Book Title",
    "author": "Author Name",
    "isbn": "978-3-16-148410-0",
    "published_year": 2024,
    "category": "Fiction",
    "quantity": 10,
    "available_quantity": 8,
    "description": "Book description",
    "price": 29.99
}
```

**Validation Rules:**
- `title`: Required, string, max 255 characters
- `author`: Required, string, max 255 characters
- `isbn`: Required, unique, string, max 20 characters
- `published_year`: Required, integer, between 1900 and current year
- `category`: Required, string, max 100 characters
- `quantity`: Required, integer, minimum 1
- `available_quantity`: Required, integer, minimum 0 (must be ≤ quantity)
- `description`: Optional, string
- `price`: Required, numeric, minimum 0

**Success Response (201):**
```json
{
    "success": true,
    "message": "Book created successfully",
    "data": {
        "id": 1,
        "title": "New Book Title",
        "author": "Author Name",
        "isbn": "978-3-16-148410-0",
        "published_year": 2024,
        "category": "Fiction",
        "quantity": 10,
        "available_quantity": 8,
        "description": "Book description",
        "price": "29.99",
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

---

### 3. Get Single Book
- **Method:** `GET`
- **Endpoint:** `/api/books/{id}`
- **Description:** Retrieve a specific book by ID

**Response:**
```json
{
    "success": true,
    "message": "Book retrieved successfully",
    "data": {
        "id": 1,
        "title": "Book Title",
        "author": "Author Name",
        "isbn": "978-3-16-148410-0",
        "published_year": 2023,
        "category": "Fiction",
        "quantity": 10,
        "available_quantity": 8,
        "description": "Book description",
        "price": "29.99",
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

---

### 4. Update a Book
- **Method:** `PUT` or `PATCH`
- **Endpoint:** `/api/books/{id}`
- **Description:** Update book information (supports partial updates)

**Request Body (any combination of fields):**
```json
{
    "title": "Updated Title",
    "price": 39.99,
    "available_quantity": 5
}
```

**Success Response:**
```json
{
    "success": true,
    "message": "Book updated successfully",
    "data": {
        "id": 1,
        "title": "Updated Title",
        "author": "Author Name",
        "isbn": "978-3-16-148410-0",
        "published_year": 2023,
        "category": "Fiction",
        "quantity": 10,
        "available_quantity": 5,
        "description": "Book description",
        "price": "39.99",
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T11:45:00Z"
    }
}
```

---

### 5. Delete a Book
- **Method:** `DELETE`
- **Endpoint:** `/api/books/{id}`
- **Description:** Remove a book from inventory

**Success Response:**
```json
{
    "success": true,
    "message": "Book deleted successfully",
    "data": {
        "id": 1,
        "title": "Book Title",
        "author": "Author Name",
        "isbn": "978-3-16-148410-0",
        "published_year": 2023,
        "category": "Fiction",
        "quantity": 10,
        "available_quantity": 8,
        "description": "Book description",
        "price": "29.99",
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

---

### 6. Get Available Books
- **Method:** `GET`
- **Endpoint:** `/api/books/available/all`
- **Description:** Retrieve only books with available quantity > 0

**Response:**
```json
{
    "success": true,
    "message": "Available books retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Available Book",
            "author": "Author Name",
            "available_quantity": 5,
            ...
        }
    ],
    "count": 1
}
```

---

### 7. Get Books by Category
- **Method:** `GET`
- **Endpoint:** `/api/books/category/{category}`
- **Description:** Retrieve all books in a specific category

**Example:** `/api/books/category/Fiction`

**Response:**
```json
{
    "success": true,
    "message": "Books retrieved by category successfully",
    "category": "Fiction",
    "data": [
        {
            "id": 1,
            "title": "Fiction Book",
            "category": "Fiction",
            ...
        }
    ],
    "count": 1
}
```

---

### 8. Update Book Quantity
- **Method:** `PATCH`
- **Endpoint:** `/api/books/{id}/quantity`
- **Description:** Quick update for available quantity (useful for borrowing/returning books)

**Request Body:**
```json
{
    "available_quantity": 7
}
```

**Success Response:**
```json
{
    "success": true,
    "message": "Book quantity updated successfully",
    "data": {
        "id": 1,
        "title": "Book Title",
        "available_quantity": 7,
        ...
    }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "title": ["The title field is required"],
        "isbn": ["The isbn has already been taken"]
    }
}
```

### Not Found (404)
```json
{
    "message": "Not Found"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Error creating book",
    "error": "Error details"
}
```

---

## Example Usage

### Using cURL

**Create a book:**
```bash
curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "isbn": "978-0743273565",
    "published_year": 1925,
    "category": "Fiction",
    "quantity": 5,
    "available_quantity": 3,
    "description": "A classic American novel",
    "price": 12.99
  }'
```

**Get all books:**
```bash
curl http://localhost:8000/api/books
```

**Update a book:**
```bash
curl -X PUT http://localhost:8000/api/books/1 \
  -H "Content-Type: application/json" \
  -d '{"price": 14.99}'
```

**Delete a book:**
```bash
curl -X DELETE http://localhost:8000/api/books/1
```

---

## Database Schema

### Books Table
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT | Primary Key, Auto Increment |
| title | VARCHAR(255) | Required |
| author | VARCHAR(255) | Required |
| isbn | VARCHAR(20) | Required, Unique, Indexed |
| published_year | INT | Required |
| category | VARCHAR(100) | Required, Indexed |
| quantity | INT | Required |
| available_quantity | INT | Required, Indexed |
| description | TEXT | Optional |
| price | DECIMAL(10,2) | Required |
| created_at | TIMESTAMP | Auto |
| updated_at | TIMESTAMP | Auto |

---

## Features

✅ Full CRUD operations (Create, Read, Update, Delete)
✅ Input validation with meaningful error messages
✅ Unique ISBN validation
✅ Available quantity constraints
✅ Category filtering
✅ Available books filtering
✅ Quantity management for borrowing/returning
✅ Timestamps for tracking
✅ Database indexing for performance
✅ Comprehensive error handling
✅ RESTful API design
