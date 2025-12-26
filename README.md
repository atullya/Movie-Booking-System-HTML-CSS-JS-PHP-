# Cine Nepal Cineplex (Movie Booking)

A simple PHP/MySQL movie listing and booking web app with an admin panel and eSewa payment integration. This project provides:

- User sign-up / sign-in and profile editing
- Movie REST API for listing, adding, updating and deleting movies (`RestAPI.php`)
- Admin upload UI for images and video files
- Client-side seat selection & booking flow (localStorage) + eSewa redirect for payments

Important files

- `dbconnection.php` — MySQL connection used across pages
- `RestAPI.php` — REST endpoints for `movies` (GET, POST, PUT, DELETE)
- `login.php` — Sign in / Sign up UI and logic
- `user.php` — Profile editing page
- `adminupload.php` — Admin UI to add movies (uploads image/video)
- `booking.php`, `afterbookshow.php`, `esewa.php` — Booking UI and payment flow
- `movie.php` — Movie listing / player UI

Setup

1. Place the project under your web server root (already at `/var/www/html/movie`).
2. Create the MySQL database and tables (see SQL below). The app expects a DB named `ranjana`.
3. Update database credentials in `dbconnection.php` (set correct MySQL password).
4. Ensure `uploads/` is writable by the web server:

```bash
sudo chown -R www-data:www-data /var/www/html/movie/uploads
sudo chmod -R 755 /var/www/html/movie/uploads
```

5. Access the app in a browser via your Apache/Nginx PHP server (e.g., http://localhost/movie).

Database schema (run these in MySQL)

```sql
CREATE DATABASE IF NOT EXISTS `ranjana` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ranjana`;

CREATE TABLE IF NOT EXISTS `users` (
	`uid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`fname` VARCHAR(100),
	`lname` VARCHAR(100),
	`email` VARCHAR(255) NOT NULL UNIQUE,
	`mobile` VARCHAR(20),
	`password` VARCHAR(255) NOT NULL,
	`gender` ENUM('male','female','other') DEFAULT NULL,
	`location` VARCHAR(255) DEFAULT NULL,
	`about` TEXT DEFAULT NULL,
	`birthdate` DATE DEFAULT NULL,
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `movies` (
	`movie_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`title` VARCHAR(255) NOT NULL,
	`duration` VARCHAR(50),
	`available_time` JSON,
	`genere` VARCHAR(100),
	`description` TEXT,
	`director` VARCHAR(255),
	`cast` TEXT,
	`release_on` DATE,
	`image` VARCHAR(255),
	`vid` VARCHAR(255),
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional tables to persist bookings/payments server-side
CREATE TABLE IF NOT EXISTS `bookings` (
	`booking_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` INT NOT NULL,
	`movie_id` INT NOT NULL,
	`show_date` DATE,
	`show_time` VARCHAR(50),
	`seats` JSON,
	`total_price` DECIMAL(10,2),
	`payment_status` ENUM('pending','paid','failed') DEFAULT 'pending',
	`transaction_id` VARCHAR(255) DEFAULT NULL,
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (`user_id`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
	FOREIGN KEY (`movie_id`) REFERENCES `movies`(`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `payments` (
	`payment_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`booking_id` INT NOT NULL,
	`amount` DECIMAL(10,2) NOT NULL,
	`payment_method` VARCHAR(50),
	`provider` VARCHAR(50),
	`provider_txn_id` VARCHAR(255),
	`status` ENUM('pending','success','failed') DEFAULT 'pending',
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`booking_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Notes & recommendations

- `RestAPI.php` expects `available_time` to be JSON; the admin UI sends a JSON array.
- The current booking flow keeps user selections in `localStorage` and redirects to `esewa.php` for payment. If you want persistent bookings, implement server-side booking insertions (use `bookings` table) after payment confirmation.
- Replace `YOUR_STRONG_PASSWORD` in `dbconnection.php` with your real DB password.

Further work (suggestions)

- Persist bookings server-side and validate seat availability.
- Add CSRF protection and prepared statements consistently (some pages currently build SQL with interpolated variables).
- Add input validation and stronger error handling on API endpoints.

If you want, I can: create a `db/schema.sql` file, add server-side booking handlers, or run a quick security pass to replace raw SQL with prepared statements. Tell me which next step you'd like.
