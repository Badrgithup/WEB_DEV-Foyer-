# Foyer Ibn Zohr Manouba - Student Housing Website

# Overview
The **Foyer Ibn Zohr Manouba** website is a comprehensive platform designed to provide information and services for students residing at the Ibn Zohr student housing facility in Manouba, Tunisia. The website includes:

- A public-facing interface to showcase the facility's services, history, and contact details.
- A private student portal for managing requests and accessing documents.
- An admin dashboard for managing students, payments, announcements, and requests.

This project is built using **PHP**, **HTML**, **CSS**, **JavaScript**, and **MySQL**, with a responsive design to ensure accessibility across devices.

# Features
## Public Interface - General Website
- **Homepage**: Introduces the Foyer Ibn Zohr with a banner, navigation, and date/time display.
- **About Section**: Details the history, mission, and daily life at the foyer with tabbed content (History, Objectives, Life at the Foyer).
- **Services**: Highlights available services like accommodation, dining, study spaces, and internet access.
- **Virtual Tour**: Showcases images of rooms, study areas, cafeteria, and activities (e.g., ping-pong, volleyball, foosball).
- **Impact Stats**: Displays animated counters for events, satisfied students, and available rooms.
- **Localization**: Embedded Google Maps iframe showing the foyer's location.
- **Contact Form**: Allows users to send messages to the administration with email confirmation.
- **Responsive Navigation**: Mobile-friendly navbar with toggle functionality and smooth scrolling.

## Student Portal (Espace Étudiant)
- **Authentication**: Secure login and registration system with CIN (8-digit ID) and password validation.
- **Profile Card**: Displays student details (name, CIN, room number).
- **Announcements**: Lists recent announcements from the administration.
- **Request Submission**: Form for students to submit repair or room change requests.
- **Documents**: Links to download residence certificates and internal regulations.
- **Logout**: Secure session termination.

## Admin Dashboard
- **Authentication**: Secure admin login via `index.php` with username and password validation.
- **Dashboard Overview**: Displays key statistics (total students, pending payments, announcements, pending demands) in interactive stat cards.
- **Student Management**:
  - Add, edit, or delete student records (name, room, contact).
  - Search functionality to filter students by name, room, or contact.
- **Payment Management**:
  - Add, edit, or delete payment records (student, amount, due date, status: en attente, payé, impayé).
  - Filter payments by status and search by student name, amount, or date.
- **Announcement Management**:
  - Create, edit, or delete announcements (title, content).
  - Announcements are displayed in the student portal.
- **Demande Management**:
  - View, update status (en attente, traitée, rejetée), or delete student demands.
  - Search demands by student name, type, or message.
- **Navigation**: Tabbed interface for switching between Dashboard, Students, Payments, Announcements, and Demandes sections.
- **Responsive Design**: Mobile-friendly layout with collapsible forms and tables.
- **Logout**: Secure session termination with redirect to admin login.

## Technical Features
- **Frontend**: HTML5, CSS3, JavaScript, Font Awesome, Google Fonts, and responsive design.
- **Backend**: PHP with PDO for secure database interactions.
- **Database**: MySQL (`foyer_db`) for storing student, admin, payment, announcement, and demand data.
- **Session Management**: Secure session handling with cache control and session regeneration to prevent fixation.
- **Form Validation**: Client-side (JavaScript) and server-side (PHP) validation for all forms.
- **Dark/Light Theme**: Toggleable theme with local storage persistence (incomplete in `app.js`).
- **Animations**: Smooth transitions, hover effects, and animated stat counters.
- **Search and Filter**: Real-time search and filtering for students, payments, and demands in the admin dashboard.

# Prerequisites
- **Web Server**: Apache or similar (e.g., XAMPP, WAMP).
- **PHP**: Version 7.4 or higher.
- **MySQL**: Version 5.7 or higher.
- **Browser**: Modern browsers (Chrome, Firefox, Safari, Edge).

# Installation
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-username/foyer-ibn-zohr.git
   cd foyer-ibn-zohr
   ```

2. **Set Up the Database**:
   - Create a MySQL database named `foyer_db`.
   - Import the following SQL to create the necessary tables:
     ```sql
     CREATE TABLE admins (
         id INT AUTO_INCREMENT PRIMARY KEY,
         username VARCHAR(255) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL
     );

     CREATE TABLE students (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(255) NOT NULL,
         cin VARCHAR(8) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL,
         email VARCHAR(255) NOT NULL,
         contact VARCHAR(8) NOT NULL,
         date_of_birth DATE NOT NULL,
         establishment VARCHAR(255) NOT NULL,
         room VARCHAR(50)
     );

     CREATE TABLE payments (
         id INT AUTO_INCREMENT PRIMARY KEY,
         student_id INT NOT NULL,
         amount DECIMAL(10,2) NOT NULL,
         due_date DATE NOT NULL,
         status ENUM('en attente', 'payé', 'impayé') NOT NULL,
         FOREIGN KEY (student_id) REFERENCES students(id)
     );

     CREATE TABLE announcements (
         id INT AUTO_INCREMENT PRIMARY KEY,
         title VARCHAR(255) NOT NULL,
         content TEXT NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     CREATE TABLE requests (
         id INT AUTO_INCREMENT PRIMARY KEY,
         student_id INT NOT NULL,
         type VARCHAR(50) NOT NULL,
         message TEXT NOT NULL,
         status ENUM('en attente', 'traitée', 'rejetée') DEFAULT 'en attente',
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         FOREIGN KEY (student_id) REFERENCES students(id)
     );
     ```

3. **Configure Database Connection**:
   - Open `db_connect.php` and update the database credentials if needed:
     ```php
     $host = 'localhost';
     $dbname = 'foyer_db';
     $username = 'root';
     $password = '';
     ```

4. **Set Up the Web Server**:
   - Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Ensure the server supports PHP and MySQL.

5. **Start the Server**:
   - Launch your web server and MySQL service.
   - Access the website via `http://localhost/foyer-ibn-zohr`.

# Usage
## Public Website
- Navigate to the homepage to explore services, view the virtual tour, or contact the administration.
- Use the contact form to send inquiries (requires a configured mail server).

## Student Portal
- Register with valid details (CIN and contact must be 8 digits, minimum age 17).
- Log in with CIN and password to access the student dashboard.
- Submit demands, view announcements, or download documents.

## Admin Dashboard
- Log in at `index.php` with admin credentials.
- Manage students, payments, announcements, and demands via the respective sections.
- Use search and filter options to locate specific records.
- Log out to secure the session.

# File Structure
```
foyer-ibn-zohr/
├── image/                   # Images and videos for the website
├── pdf/                     # Downloadable documents
├── fontawesome-free-5.12.0-web/ # Font Awesome assets
├── app.js                   # JavaScript for public website
├── espaceEtudiant.js        # JavaScript for student portal
├── scriptInsc.js            # JavaScript for login/registration
├── admin_dashboard.js       # JavaScript for admin dashboard
├── styles.css               # CSS for public website
├── EsapaceEtudaint.css      # CSS for student portal
├── stylesInsc.css           # CSS for login/registration
├── admin1.css               # CSS for admin login
├── admin_dashboard1.css     # CSS for admin dashboard
├── index.php                # Admin login page
├── EspaceEtudiant.php       # Student portal dashboard
├── inscription.php          # Student login/registration page
├── admin_dashboard.php      # Admin dashboard
├── db_connect.php           # Database connection script
├── check_session.php        # Session validation for students (not provided)
├── check_session_admin.php  # Session validation for admins (not provided)
├── README.md                # Project documentation
```

# Known Issues
- **Theme Toggle**: The dark/light theme toggle in `app.js` references undefined variables (`darkTheme`, `iconTheme`, `themeButton`), causing errors.
- **Email Functionality**: The contact form in `index.php` (public website) requires a configured mail server for email sending.
- **Session Check Files**: `check_session.php` and `check_session_admin.php` are referenced but not provided, potentially causing redirect issues.
- **Video Source**: The video in `index.php` (public website, `foyer-video.mp4`) may not load if missing or incorrectly pathed.
- **Typo in CSS Filename**: `EsapaceEtudaint.css` should be `EspaceEtudiant.css` for consistency.
- **Password Storage**: Admin and student passwords are stored in plain text, posing a security risk.
- **Admin Login Redirect**: The public website's `index.php` is replaced by the admin login, which may conflict with the public interface.

# Future Improvements
- Complete the theme toggle functionality with proper variable definitions.
- Implement a mail server (e.g., SMTP with PHPMailer) for reliable email sending.
- Add password hashing (e.g., `password_hash`) for secure storage in `admins` and `students` tables.
- Implement CSRF protection for all forms to enhance security.
- Add pagination for large datasets in the admin dashboard (students, payments, demands).
- Create a separate `index.php` for the public website to avoid conflicts with the admin login.
- Provide `check_session.php` and `check_session_admin.php` for complete session validation.
- Add input validation for admin forms (e.g., contact format, amount range).

# Contributing
Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m "Add your feature"`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request.


# Contact
For questions or feedback, contact the project maintainer:

- **Email**: badreddine.younsi@ensi-uma.tn
- **Phone**: +216 21 350 687
- **Address**: Rue de l'Université, Manouba, Tunisie

---

Built with ❤️ by Me and Mr Ayoub JRIBI and Mouhamed Borhen KHLIFI
