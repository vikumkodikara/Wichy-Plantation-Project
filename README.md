# Wichy Plantation Company -- Web-Based Management System

A full-stack web application developed for Wichy Coconut, a Sri Lankan coconut product manufacturing company. The system provides an integrated platform for product catalogue management, online ordering, employee operations, quality control tracking, and customer engagement. Built as the final project for the Diploma in Information Technology at Wayamba University of Sri Lanka.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [System Architecture](#system-architecture)
- [Technology Stack](#technology-stack)
- [Database Design](#database-design)
- [Installation and Setup](#installation-and-setup)
- [User Roles and Access Control](#user-roles-and-access-control)
- [Default User Credentials](#default-user-credentials)
- [Project Structure](#project-structure)
- [Screenshots](#screenshots)
- [Author](#author)
- [License](#license)

---

## Overview

Wichy Plantation Company is a coconut-based product manufacturer operating in Sri Lanka. This web application was developed to digitise and streamline the company's core business operations including product management, customer order processing, employee data management, and quality control inspections across multiple coconut milk fat-rate categories.

The system follows a role-based access control model with three distinct user roles: Administrator, Employee, and Customer. Each role has a dedicated interface tailored to its operational requirements.

---

## Features

### Public-Facing Website
- Company landing page with product showcase and company statistics
- About Us page with company story and mission statement
- News and updates section with article listings
- Image gallery showcasing company operations
- Contact Us page with support information and message submission form
- AI-powered chat widget for customer assistance

### Customer Portal
- User registration with profile picture upload and strong password enforcement
- Secure login with session-based authentication
- Product browsing with detailed item pages
- Shopping cart with quantity management
- Multi-step checkout process (address entry, payment selection, order confirmation)
- Support for Cash on Delivery (COD) and card payment methods
- Comment and feedback submission on news articles

### Admin Dashboard
- Overview dashboard with user, product, and order statistics
- Product management (add, edit, delete products with image uploads)
- Order management and status tracking
- Employee record management (add, edit, remove employees with phone numbers)
- View and manage customer comments
- View and manage contact form submissions

### Employee Module
- Dedicated employee interface for coconut milk fat-rate data tables
- Support for multiple fat-rate categories (6%, 9%, 10%, 12%, 18%, 22%, 30%)
- CRUD operations on ingredient composition data across competitor brands
- Quality Control (QC) inspection panel with inspector assignment, pass/fail status, and remarks
- Historical QC record viewing and management

---

## System Architecture

The application follows a traditional server-side rendered architecture using PHP and MySQL:

```
Client Browser
      |
      v
Apache Web Server (XAMPP)
      |
      v
PHP Application Layer
      |
      v
MySQL Database (coconut_shop_simple)
```

- All pages are server-rendered using PHP with embedded HTML
- Session-based authentication manages user state across requests
- Role-based routing directs users to appropriate dashboards after login
- Reusable components (navbar, footer) are included via PHP includes

---

## Technology Stack

| Layer        | Technology                          |
|--------------|-------------------------------------|
| Frontend     | HTML5, CSS3, JavaScript             |
| Backend      | PHP 8.x                             |
| Database     | MySQL (MariaDB via XAMPP)           |
| Web Server   | Apache (XAMPP)                      |
| Typography   | Google Fonts (Inter)                |
| Icons        | Font Awesome 6.x                    |
| Hosting      | Local development (XAMPP)           |

---

## Database Design

The MySQL database `coconut_shop_simple` contains 16 tables organised into four functional groups:

### User and Authentication
- `users` -- Stores user accounts with hashed passwords, roles, and profile pictures

### Product and Commerce
- `product` -- Product catalogue with name, image, price, and description
- `cart` -- Shopping cart entries linked to users and products
- `orders` -- Completed orders with address, payment method, and status

### Employee and Quality Control
- `employees` -- Employee records with personal details
- `employee_phones` -- Employee contact numbers (one-to-many relationship)
- `quality_control` -- QC inspection records with inspector, status, and date

### Fat-Rate Composition Tables
- `fatrate6`, `fatrate9`, `fatrate10`, `fatrate12`, `fatrate18`, `fatrate22`, `fatrate30` -- Ingredient composition data across competitor coconut milk brands at various fat percentages

### Communication
- `comments` -- User comments on news articles
- `contact_messages` -- Messages submitted via the Contact Us form

The complete database schema is available in the `database/schema.sql` file.

---

## Installation and Setup

### Prerequisites

- XAMPP (or any Apache + MySQL + PHP stack) with PHP 8.0 or higher
- A web browser (Chrome, Firefox, Edge, or Safari)
- Git (optional, for cloning the repository)

### Step-by-Step Installation

1. **Install XAMPP**

   Download and install XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org).

2. **Clone or Download the Repository**

   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/vikumkodikara/Wichy-Plantation-Project.git wichy-plantation
   ```

   Alternatively, download the ZIP file and extract it into `C:\xampp\htdocs\wichy-plantation`.

3. **Start Apache and MySQL**

   Open the XAMPP Control Panel and start both the **Apache** and **MySQL** modules.

4. **Create the Database**

   Open phpMyAdmin at [http://localhost/phpmyadmin](http://localhost/phpmyadmin) and:

   - Create a new database named `coconut_shop_simple`
   - Select the newly created database
   - Navigate to the **Import** tab
   - Choose the file `database/schema.sql` from the project directory
   - Click **Go** to execute the import

5. **Configure the Database Connection**

   Open the file `config.php` in the project root and verify that the database credentials match your local setup:

   ```php
   $DB_HOST = '127.0.0.1';
   $DB_USER = 'root';
   $DB_PASS = '';
   $DB_NAME = 'coconut_shop_simple';
   ```

6. **Access the Application**

   Open your web browser and navigate to:

   ```
   http://localhost/wichy-plantation/
   ```

---

## User Roles and Access Control

The system implements three distinct user roles, each with different levels of access:

| Role       | Registration          | Access Scope                                              |
|------------|-----------------------|-----------------------------------------------------------|
| Customer   | Self-registration     | Browse products, place orders, submit comments/messages   |
| Employee   | Created by admin      | Fat-rate data tables, quality control inspections         |
| Admin      | Pre-configured        | Full system access including all management functions     |

### Authentication Flow

1. New users register via the signup page and are assigned the **Customer** role by default
2. Upon login, the system checks the user's role and redirects accordingly:
   - **Admin** users are directed to the Admin Dashboard (`admin.php`)
   - **Employee** users are directed to the Employee Module (`employee/display_wichy_coconut.php`)
   - **Customer** users are directed to the Product Catalogue (`public/items.php`)
3. Password security enforces a minimum of 6 characters with mandatory uppercase, lowercase, numeric, and special character requirements

---

## Default User Credentials

For academic examination, evaluation, and system demonstration purposes, the database includes pre-configured user accounts for each role:

| Role | Username | Email | Password | Landing Page | Capabilities |
|------|----------|-------|----------|--------------|--------------|
| Administrator | `Nimantha` | `nimantha@gmail.com` | `Admin@123` | `admin.php` | Product CRUD, order status management, employee records, inquiries & comment moderation |
| Employee | `Dasun` | `dasun@gmail.com` | `123456` | `employee/display_wichy_coconut.php` | Fat-rate formulation data tables (6% to 30%), quality control inspection logging |
| Employee | `Pasan` | `pasan@gmail.com` | `123456` | `employee/display_wichy_coconut.php` | Fat-rate formulation data tables, competitor ingredient comparison |
| Customer | `nipun` | `nipun@gmail.com` | `123456` | `public/items.php` | Product catalog browsing, cart operations, multi-step checkout (COD/Card) |

> Note: User passwords are encrypted using standard PHP `password_hash()` (Bcrypt algorithm). The accounts above are pre-populated and active after importing `database/schema.sql`.

---

## Project Structure

```
wichy-plantation/
|
|-- index.php                  # Landing page with hero, stats, products, and commitment sections
|-- login.php                  # User login with role-based redirection
|-- signup.php                 # User registration with profile picture upload
|-- logout.php                 # Session termination and redirect
|-- config.php                 # Database connection and session configuration
|-- admin.php                  # Admin dashboard (root-level entry point)
|-- contactus.php              # Help centre with contact form
|-- aboutus.php                # Company information page
|-- news.php                   # News and updates listing
|-- image-gallery.php          # Company image gallery
|-- save_comment.php           # Comment submission handler
|-- save_message.php           # Contact message submission handler
|-- shop_redirect.php          # Shop redirection helper
|
|-- admin/                     # Admin management pages
|   |-- admin.php              # Admin panel layout
|   |-- products.php           # Product listing and management
|   |-- add_product.php        # Add new product form
|   |-- orders.php             # Order management
|   |-- employee.php           # Employee record management
|   |-- comments.php           # Comment moderation
|   |-- contact_messages.php   # Contact message viewing
|   |-- admin_add_comment.php  # Admin comment addition
|   |-- admin_add_contact_message.php  # Admin message handler
|
|-- employee/                  # Employee module
|   |-- display_wichy_coconut.php  # Main employee interface with fat-rate tables and QC panel
|   |-- db_connect.php         # Employee-specific database connection
|   |-- tables.php             # Allowed table definitions
|   |-- add.php                # Add data form
|   |-- add_process.php        # Add data handler
|   |-- edit.php               # Edit data form
|   |-- edit_process.php       # Edit data handler
|   |-- delete.php             # Delete data handler
|   |-- row_form.php           # Row edit/add form
|   |-- qc_process.php         # QC submission handler
|   |-- qc_delete.php          # QC record deletion handler
|
|-- public/                    # Customer-facing shop pages
|   |-- items.php              # Product catalogue
|   |-- item_details.php       # Individual product details
|   |-- cart.php               # Shopping cart
|   |-- address.php            # Delivery address entry
|   |-- payment.php            # Payment method selection
|   |-- card_details.php       # Card payment details
|   |-- order_success.php      # Order confirmation page
|   |-- images/                # Uploaded product and profile images
|
|-- components/                # Reusable PHP components
|   |-- navbar.php             # Navigation bar
|   |-- footer.php             # Page footer
|
|-- backend/                   # Backend services
|   |-- gemini_agent.php       # AI chat agent integration
|
|-- database/                  # Database files
|   |-- schema.sql             # Database schema (table structure)
|
|-- style.css                  # Landing page styles
|-- global.css                 # Global styles and CSS variables
|-- gallery.css                # Gallery page styles
|-- aboutus.css                # About Us page styles
|-- news.css                   # News page styles
|-- script.js                  # Client-side JavaScript (chat widget, interactions)
```

---

## Screenshots

After running the application locally, you can access the following pages:

- **Home Page**: `http://localhost/wichy-plantation/`
- **Login**: `http://localhost/wichy-plantation/login.php`
- **Sign Up**: `http://localhost/wichy-plantation/signup.php`
- **Product Catalogue**: `http://localhost/wichy-plantation/public/items.php`
- **Admin Dashboard**: `http://localhost/wichy-plantation/admin.php` (requires admin login)
- **Employee Module**: `http://localhost/wichy-plantation/employee/display_wichy_coconut.php` (requires employee login)

---

## Author

**Vikum Kodikara**
Diploma in Information Technology
Wayamba University of Sri Lanka

GitHub: [github.com/vikumkodikara](https://github.com/vikumkodikara)

---

## License

This project was developed as an academic submission for the Diploma in Information Technology programme at Wayamba University of Sri Lanka. It is made available for educational and reference purposes.

---

## Acknowledgements

- Wayamba University of Sri Lanka, Faculty of Technology
- Wichy Plantation Company for providing the business context and requirements
- XAMPP by Apache Friends for the local development environment
