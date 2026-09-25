# Rural Bank of Paete - CMS Landing Page

A database-driven CMS admin panel and dynamic landing page built using PHP (PDO), MySQL, and modern JavaScript.

---

## Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache and MySQL)
- PHP 8.0+

---

## Local Setup Instructions

1. **Clone or Move Repository**  
   Place this repository folder directly inside your XAMPP web root (`htdocs`):
   ```bash
   C:/xampp/htdocs/CMS-Paete-Landing-Page-main


1. Start Services

Open the XAMPP Control Panel and start both Apache and MySQL.



2. Import Database

Open your browser and navigate to http://localhost/phpmyadmin.

Click New in the left sidebar and create a database named:

paete_cms

Click the Import tab at the top.

Choose the setup.sql file located in the root of this project and click Import.



3. Verify Database Connection

Open config/database.php to confirm credentials match your local MySQL configuration:

Host: localhost

Database: paete_cms

User: root

Password: "" (empty by default in XAMPP)


=================Application Access=====================
Public Landing Page:

http://localhost/CMS-Paete-Landing-Page-main/index.html

Admin Login:

http://localhost/CMS-Paete-Landing-Page-main/src/admin/login.php

Admin Registration:

http://localhost/CMS-Paete-Landing-Page-main/src/admin/register.php

Admin Dashboard:

http://localhost/CMS-Paete-Landing-Page-main/src/admin/index.php

Features
Public API Layer: RESTful endpoints serving MySQL dynamic content via JSON payloads.

Dynamic Hydration: Asynchronous data fetching in client-side modules replacing static JSON.

Admin CMS Dashboard: Section card interface for editing Landing Page components.

Session Authentication: Secure admin registration and login using bcrypt password hashing.