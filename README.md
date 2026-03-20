# 🎓 Alumni Club of Sofia University

A full-stack web application developed as part of a university project at **Sofia University "St. Kliment Ohridski"**.

The system is designed to support alumni engagement by providing a platform for communication, event organization, and community building.

---

## 📌 Overview

The **Alumni Club of SU** application allows former and current students to stay connected through clubs, events, and real-time interaction.

The platform provides a centralized space where users can:

* discover and join communities (clubs)
* participate in events
* share posts
* communicate with other members

---

## 🚀 Features

### 👤 Guest Users

* Browse available clubs
* View upcoming events

### 🔐 Registered Users

* Register and log into the system
* Create, edit, and delete clubs
* Join and leave clubs
* Create and manage events
* Join and leave events
* Publish posts in clubs
* Chat with other users in real time
* Receive notifications for unread messages

---

## 🛠️ Technologies

* **Backend:** PHP
* **Database:** MySQL
* **Frontend:** HTML, CSS, JavaScript
* **Asynchronous communication:** AJAX
* **Environment:** XAMPP

---

## 🧱 Architecture

The application follows a **three-tier architecture**:

* **Presentation Layer** – HTML, CSS, JavaScript
* **Application Layer** – PHP
* **Data Layer** – MySQL

---

## 📂 Project Structure

```
/
│── index.php
│── db.php
│── auth.php
│── login.php
│── register.php
│── logout.php
│── clubs.php
│── events.php
│── chat.php
│── css/
│── js/
│── assets/
```

---

## ⚙️ How to Run the Project

Follow these steps to run the project locally:

### 1. Clone the repository

```bash
git clone git clone https://github.com/mariaivanova/alumni-club-su.git
```

### 2. Move project to XAMPP

Copy the project folder into:

```
C:\xampp\htdocs\
```

### 3. Start the server

Open **XAMPP Control Panel** and start:

* Apache
* MySQL

### 4. Setup the database

1. Open:

```
http://localhost/phpmyadmin
```

2. Create a new database:

```
alumni_club
```

3. Import the `.sql` file:

* Go to **Import**
* Select the SQL file from the project
* Click **Go**

### 5. Configure database connection

Open `db.php` and make sure it looks like this:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "alumni_club";
```

### 6. Run the application

Open in browser:

```
http://localhost/alumni_club
```

---

## 📸 Screenshots


<img width="605" height="428" alt="image" src="https://github.com/user-attachments/assets/bf0f1a57-171e-4474-9d08-6af5f3606ecc" />

<img width="605" height="420" alt="image" src="https://github.com/user-attachments/assets/53ad86da-3e33-4505-a463-a64e5fcbf963" />

<img width="605" height="418" alt="image" src="https://github.com/user-attachments/assets/9a7f38af-727c-4467-9ba7-3024311eb5b5" />

<img width="605" height="426" alt="image" src="https://github.com/user-attachments/assets/600eb03e-0b13-41e9-b5cc-3cd9323b4e58" />

<img width="605" height="629" alt="image" src="https://github.com/user-attachments/assets/e05e48fb-17c5-44dc-b216-36bcc74e9c40" />

<img width="605" height="636" alt="image" src="https://github.com/user-attachments/assets/89a1abb9-0ae3-4cb6-bddd-a4259e0d1d76" />

<img width="605" height="586" alt="image" src="https://github.com/user-attachments/assets/14680627-5feb-4d68-836f-3cd94e1c962f" />


---

## 🔮 Future Improvements

* Improve chat interface styling  
* User profile pages
* Role-based access (admin/moderator)
* Email notifications
* Improved UI/UX
* Security improvements

---

## 🎓 Academic Context

This project was developed as part of coursework at
**Sofia University "St. Kliment Ohridski"**.


