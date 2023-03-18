# Dockerized REST API for loans

## Installation

Download the project, open a new terminal in the project root directory and run: `docker-compose up`.
Since Docker automatically configures Apache and initializes the database, the API is ready to use out of the box. 

## Endpoints:

The API will be accessible at localhost.

* Apply for a loan at `/apply`, example POST request JSON would look something like this:
```json
{
  "personal_id": "1E3H5O7AQ",
  "name": "John Mike",
  "amount": "12000.56",
  "term": "24"
}
```

* List loans by a borrower at `/list/loan/{personal_id}`, example request url would look something like this:
```javascript
/list/loan/123456789
```

## Functionalities
The loan application will be rejected if:
* The borrower is blacklisted (stored in db)
* There have been too many applications from one personal id in the last 24 hours
Monthly interest of 5% is automatically calculated and inserted to the database.

## Technical setup
* Dockerized Apache, MySQL and PHPMyAdmin
* Written in PHP OOP
* Supported by the Slim framework
