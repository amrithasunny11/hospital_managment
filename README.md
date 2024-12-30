# Hospital and Clinician Group Management API

## Overview

This project is a RESTful API built with Laravel to manage the data for a tree structure representing different hospitals and groups of clinicians within those hospitals. The API supports CRUD operations for these groups and ensures data integrity.

## Requirements

-   PHP 8.0+
-   Composer
-   MySQL
-   Laravel

## Setup Instructions

### Clone the Repository

    git clone https://github.com/amrithasunny11/hospital_managment.git
    cd hospital-management-api

### Install Dependencies

    composer install

### Configure Environment Variables

    Copy the .env.example file to .env and update the necessary environment variables, especially the database configuration.


    Update the .env file with your database credentials:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password

### Run Migrations

    php artisan migrate

### Seed the Database

    php artisan db:seed

### Run the Development Server

    php artisan serve

    The API will be available at http://localhost:8000.

# API Endpoints

### Authentication

    Register: POST /api/register
        Request
            {
                "name": "John Doe",
                "email": "amrita@example.com",
                "password": "password123",
                "password_confirmation": "password123",
                "role": "admin"
            }

        Response
            {
                "message": "User registered successfully",
                "user": {
                    "name": "John Doe",
                    "email": "amrita2@example.com",
                    "role": "admin",
                    "updated_at": "2024-12-30T10:53:10.000000Z",
                    "created_at": "2024-12-30T10:53:10.000000Z",
                    "id": 340
                }
            }
    Login: POST /api/login
        Request
            {
                "email": "johndoe@example.com",
                "password": "password123"
            }
        Response
            {
                "message": "Login successful",
                "user": {
                    "id": 5,
                    "name": "John Doe",
                    "email": "johndoe@example.com",
                    "email_verified_at": null,
                    "created_at": "2024-12-30T04:53:36.000000Z",
                    "updated_at": "2024-12-30T04:53:36.000000Z",
                    "role": "admin"
                },
                "token": "5|vvwOO23ydpfMG1pem3JeGjHq2apSAPBj9MaQJ8cx012544a7"
            }

### Hospitals

    Create Hospital: POST /api/hospitals
        Request
            {
                "name": "Hospital 2"
            }

        Response
            {
                "message": "Hospital created successfully",
                "hospital": {
                    "name": "Hospital 2",
                    "updated_at": "2024-12-30T10:57:44.000000Z",
                    "created_at": "2024-12-30T10:57:44.000000Z",
                    "id": 142
                }

            }

    List Hospital: GET /api/hospitals
        {
            "hospitals": [
                {
                    "id": 1,
                    "name": "unde",
                    "created_at": "2024-12-30T10:04:15.000000Z",
                    "updated_at": "2024-12-30T10:04:15.000000Z"
                },
                {
                    "id": 142,
                    "name": "Hospital 2",
                    "created_at": "2024-12-30T10:57:44.000000Z",
                    "updated_at": "2024-12-30T10:57:44.000000Z"
                }
            ]
        }
    View Hospital: GET /api/hospitals/{id}
        {
            "hospital": {
                "id": 1,
                "name": "unde",
                "created_at": "2024-12-30T10:04:15.000000Z",
                "updated_at": "2024-12-30T10:04:15.000000Z"
            }
        }
    Update Hospital: PUT /api/hospitals/{id}
        Request
            {
                "name": "Hospital 2f"
            }
        Response
            {
                "message": "Hospital updated successfully",
                "hospital": {
                    "id": 2,
                    "name": "Hospital 2f",
                    "created_at": "2024-12-30T10:04:15.000000Z",
                    "updated_at": "2024-12-30T10:59:52.000000Z"
                }
            }
    Delete Hospital: DELETE /api/hospitals/{id}
        Response
            {
                "message": "Hospital deleted successfully"
            }
### Groups

    Create Group: POST /api/groups
        Request
            {
                "name": "fhf",
                "description": null,
                "parent_id": 29,
                "hospital_id": 1
            }
        Response
            {
                "message": "Group created successfully",
                "group": {
                    "name": "fhf",
                    "description": null,
                    "parent_id": 29,
                    "hospital_id": 1,
                    "updated_at": "2024-12-30T11:01:52.000000Z",
                    "created_at": "2024-12-30T11:01:52.000000Z",
                    "id": 164
                }
            }

    List Groups: GET /api/groups
        {
            "hospitals": [
                {
                    "id": 1,
                    "name": "unde",
                    "address": null,
                    "groups": [
                        {
                            "id": 1,
                            "name": "minus",
                            "description": "Ipsa consequatur cum dolorum ut voluptatem.",
                            "children": [
                                {
                                    "id": 6,
                                    "name": "dolores",
                                    "description": "Sapiente rerum at natus omnis.",
                                    "children": []
                                } 
                            ]
                        }
                        
                    ]
                } 
            ]
        }
    View Group: GET /api/groups/{id}
        {
            "group": {
                "id": 29,
                "name": "exercitationem",
                "description": "Suscipit quo sequi soluta nulla aspernatur.",
                "children": [
                    {
                        "id": 164,
                        "name": "fhf",
                        "description": null,
                        "children": []
                    }
                ]
            }
        }
    Update Group: PUT /api/groups/{id}
        Request
            {
                "name": "Shoulder",
                "description": null,
                "parent_id": null,
                "hospital_id": 1
            }
        Response
            {
                "message": "Group updated successfully",
                "group": {
                    "id": 3,
                    "name": "Shoulder",
                    "description": null,
                    "parent_id": null,
                    "created_at": "2024-12-30T10:04:15.000000Z",
                    "updated_at": "2024-12-30T11:12:25.000000Z",
                    "hospital_id": 1
                }
            }
    Delete Group: DELETE /api/groups/{id}
        {
            "message": "Group deleted successfully"
        }

 


# Running Tests

### Unit Tests
    php artisan test --testsuite=Unit

### Feature Tests
    php artisan test --testsuite=Feature
