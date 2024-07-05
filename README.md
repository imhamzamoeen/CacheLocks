Cache and Transactional Locking in Laravel
This repository demonstrates the usage of cache locking and transactional locking mechanisms in Laravel applications. The examples cover scenarios involving concurrent access to resources and how locks can prevent race conditions.

Routes and Their Functions
Cache Locking Examples
/cache-lock
Function: Demonstrates basic cache locking using Laravel's Cache facade.
Usage: Acquires a lock named bookings for 10 seconds.
Behavior:
If the lock is acquired, it sleeps for 20 seconds and then generates a random string.
Subsequent requests to this route will wait for the previous request to release the lock before proceeding.
/cache-lock1
Function: Similar to /cache-lock but uses a closure for lock acquisition.
Usage: Acquires a lock named lock1 for 20 seconds.
Behavior: Executes the closure to generate a random string when the lock is acquired.
/cache-block
Function: Uses block() method to wait for lock acquisition.
Usage: Acquires a lock named bookings for 10 seconds.
Behavior:
If the lock is not available, waits for 3 seconds before throwing a LockTimeoutException.
Otherwise, sleeps for 5 seconds and generates a random string.
Transactional Locking Examples
/permist-lock1
Function: Demonstrates transactional locking (sharedLock()).
Usage: Locks the User record with sharedLock() for read purpose.
Behavior:
Retrieves the User record with an exclusive shared lock.
Simulates a processing time of 50 seconds.
Updates the user's name to 'sifro' and commits the transaction.
/permist-lock2
Function: Similar to /permist-lock1 but updates user's name to 'numo'.
Usage: Illustrates concurrent access to the same record with shared locks.
Behavior:
Locks the User record with sharedLock() for read purpose.
Simulates a processing time of 10 seconds.
Updates the user's name to 'numo' and commits the transaction.
How to Run
Clone the repository:

bash
Copy code
git clone <repository-url>
Install dependencies:

bash
Copy code
composer install
Configure your .env file with database credentials.

Run migrations to set up the database:

bash
Copy code
php artisan migrate
Serve the application:

bash
Copy code
php artisan serve
Access the routes in your browser or using a tool like Postman.

Dependencies
Laravel Framework
PHP >= 7.4
Composer (for dependency management)
Contributing
Contributions are welcome! Fork the repository and submit a pull request.

License
This project is licensed under the MIT License.
