# Multimart Project

## Overview
Multimart is a full-stack web application built using PHP and MySQL, designed to facilitate the sale of phones, accessories, and furniture. The application features three distinct modules: Admin, Customer, and Delivery Person, each with its own functionalities and user interfaces.

## Project Structure
The project is organized into the following directories and files:

```
Multimart
├── admin
│   ├── admin_index.php
│   ├── dashboard.php
│   ├── manage_products.php
│   ├── manage_orders.php
│   ├── manage_users.php
│   └── includes
│       ├── header.php
│       ├── footer.php
│       └── sidebar.php
├── customer
│   ├── customer_index.php
│   ├── cart.php
│   ├── wishlist.php
│   ├── login.php
│   ├── register.php
│   ├── checkout.php
│   └── includes
│       ├── header.php
│       ├── footer.php
│       └── sidebar.php
├── delivery
│   ├── delivery_index.php
│   ├── manage_deliveries.php
│   └── includes
│       ├── header.php
│       ├── footer.php
│       └── sidebar.php
├── assets
│   ├── css
│   │   └── styles.css
│   ├── js
│   │   └── scripts.js
│   └── images
├── config
│   └── database.php
├── index.php
└── README.md
```

## Installation
1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Set up a MySQL database and import the necessary SQL files (if provided).
4. Update the `config/database.php` file with your database credentials.
5. Open `index.php` in your web browser to access the application.

## Features
- **Admin Module**: Manage products, orders, and users with a dedicated dashboard.
- **Customer Module**: Browse products, manage a shopping cart, create wishlists, and handle user authentication.
- **Delivery Module**: Track and manage deliveries efficiently.

## Technologies Used
- PHP
- MySQL
- HTML/CSS
- JavaScript

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for details.