# LANDORA 🌍🏡

**LANDORA** is a simple real estate web application built using **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**. The system allows users to buy, sell, and manage land and property listings with role-based dashboards for buyers and sellers.

---

## 📌 Project Topic

**LANDORA – Online Land & Property Management System**

---

## 🚀 Features

### 👤 User Authentication

* User registration & login
* Secure password hashing
* Session-based authentication
* Blocked user handling

### 🏠 Property Management (Seller)

* Add new land/property listings
* Upload property images
* Edit & delete listings
* View own properties dashboard

### 🔍 Property Browsing (Buyer)

* View available properties
* Search properties by title, location, or price
* View seller information

### 📊 Dashboards

* Buyer dashboard
* Seller dashboard
* Role-based access control

### 🛡️ Security

* PDO prepared statements (SQL Injection prevention)
* Input validation
* Secure file upload handling

---

## 🧑‍💻 Technologies Used

* **Frontend:** HTML5, CSS3, JavaScript
* **Backend:** PHP (PDO)
* **Database:** MySQL
* **Server:** Apache (XAMPP)

---

## 🗂️ Database Structure (Simplified)

* `users` – stores user details (buyers & sellers)
* `properties` – stores property/land listings
* `messages` (optional) – buyer–seller communication

---

## ⚙️ Installation & Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/LANDORA.git
   ```

2. Move the project to XAMPP `htdocs` folder:

   ```
   C:/xampp/htdocs/LANDORA
   ```

3. Create a MySQL database named:

   ```
   realestate
   ```

4. Import the provided SQL file into phpMyAdmin

5. Update database connection in PHP files:

   ```php
   $pdo = new PDO("mysql:host=localhost;dbname=realestate", "root", "");
   ```

6. Start Apache & MySQL from XAMPP

7. Open in browser:

   ```
   http://localhost/LANDORA
   ```
---

## 📚 Learning Outcomes

* PHP CRUD operations
* PDO & prepared statements
* Session management
* File uploads in PHP
* Real-world database design
* Full-stack web development basics

---

## 📄 License

This project is for **educational purposes** only.

---

⭐ If you like this project, don’t forget to star the repository!
