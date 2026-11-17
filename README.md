
# 🎓 SkillDev – Learning & Skills Development Platform

<p align="center">
  <strong>Learn • Teach • Connect • Grow</strong>
</p>

<p align="center">
  A web-based platform that connects learners and instructors,
  making online learning, teaching, and skill development easier.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?logo=vuedotjs&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?logo=tailwindcss&logoColor=white" />
</p>

---

## 📑 Table of Contents

- [About the Project](#-about-the-project)
- [Project Objectives](#-project-objectives)
- [Key Features](#-key-features)
  - [Learner Features](#-learner-features)
  - [Instructor Features](#-instructor-features)
  - [Admin Features](#-admin-features)
  - [Communication & Social Features](#-communication--social-features)
- [Technologies Used](#-technologies-used)
- [Project Structure](#-project-structure)
- [Installation & Setup](#-installation--setup)
- [Project Demo Videos](#-project-demo-videos)
- [Security](#-security)
- [License](#-license)

---

## 📖 About the Project

**SkillDev** is a Learning and Skills Development Platform designed to connect learners with instructors through an interactive online environment.

The platform provides opportunities for learners to explore courses, apply for training programs, complete assessments, and develop their professional skills.

Instructors can create and manage courses, communicate with learners, and build their professional profiles.

Administrators have access to management tools that help maintain the platform and monitor its activities.

SkillDev also provides social and communication features to encourage collaboration and knowledge sharing between users.

---

## 🎯 Project Objectives

- Provide an accessible environment for online learning.
- Connect learners with qualified instructors.
- Help users discover and develop new skills.
- Support course management and learning progress tracking.
- Encourage communication and collaboration.
- Provide administrators with tools to manage platform activities.
- Create an interactive learning community.

---

## ✨ Key Features

### 👨‍🎓 Learner Features

- User registration and authentication.
- Browse and explore available courses.
- Apply for training courses.
- Take placement tests and course assessments.
- Track completed and in-progress courses.
- Add courses to favorites.
- Manage personal profiles.
- Rate courses and instructors.
- Submit feedback and report inappropriate content.
- Comment on courses and discussions.

### 👨‍🏫 Instructor Features

- Instructor profiles and account management.
- Create, edit, and delete courses.
- Manage course information and content.
- Set and manage working hours.
- View course feedback and ratings.
- Interact with learners.
- Manage professional profiles.
- Connect with other platform members.

### 🛠️ Admin Features

- Manage users and instructors.
- Manage training courses.
- Create and manage course categories.
- Review and manage reports.
- Manage platform feedback.
- Manage terms and conditions.
- Monitor conversations and platform activities.
- Approve instructor accounts.

### 💬 Communication & Social Features

- Real-time messaging infrastructure.
- Send and receive messages.
- Share images and files in conversations.
- Manage friendships and friend requests.
- Comment on and reply to discussions.
- Notifications and unread message tracking.
- View profile information and interact with members.

---

## 🧰 Technologies Used

### Backend
- PHP 8.2+
- Laravel 11
- MySQL / SQLite
- Laravel Sanctum

### Frontend
- HTML5
- CSS3
- JavaScript
- Vue.js 3
- Tailwind CSS
- Vite

### Communication & Integrations
- Socket.IO
- Laravel Echo
- Pusher
- Axios
- WireChat

### Development Tools
- Composer
- NPM
- Git
- GitHub

---

## 📂 Project Structure

```text
SkillDev/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   └── Models/
│
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   └── web.php
│
├── storage/
├── tests/
│
├── package.json
├── composer.json
├── vite.config.js
└── README.md
```

---

## 🚀 Installation & Setup

Follow these steps to run SkillDev locally.

### 1. Clone the Repository

```bash
git clone https://github.com/ZeinIssa2002/SkillDev.git
```

### 2. Navigate to the Project Directory

```bash
cd SkillDev
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Install Frontend Dependencies

```bash
npm install
```

### 5. Configure Environment Variables

```bash
cp .env.example .env
```

### 6. Generate Application Key

```bash
php artisan key:generate
```

### 7. Configure the Database

Update your `.env` file with your database configuration.

### 8. Run Database Migrations

```bash
php artisan migrate
```

### 9. Start the Development Servers

Run Laravel:

```bash
php artisan serve
```

Run the frontend development server:

```bash
npm run dev
```

Open the application in your browser:

```text
http://127.0.0.1:8000
```

---

## 🎥 Project Demo Videos

Explore the main functionalities of SkillDev through the following demonstration videos.

### 👨‍💼 Admin Dashboard

| Feature | Demo |
|---|---|
| Admin Dashboard | [▶ Watch / Download](./VideosDemo/Admin.mp4) |
| Instructor Approval | [▶ Watch / Download](./VideosDemo/accept%20instructor%20by%20admin.mp4) |

### 👨‍🏫 Instructor Features

| Feature | Demo |
|---|---|
| Instructor Profile | [▶ Watch / Download](./VideosDemo/Instructor%20profile.mp4) |
| Course Cards, Comments, Reports & Feedback | [▶ Watch / Download](<./VideosDemo/Course Cards - instructor + comment + report + feedback.mp4>) |
| Rate Instructor, View Profile, Add Friend & Chat | [▶ Watch / Download](<./VideosDemo/rate instructor + show profile + add friend + chat.mp4>) |

### 👨‍🎓 Learner Features

| Feature | Demo |
|---|---|
| User Registration | [▶ Watch / Download](./VideosDemo/Register.mp4) |
| User Profile | [▶ Watch / Download](./VideosDemo/user%20profile.mp4) |
| Favorite, Apply, Comment & Rate Courses | [▶ Watch / Download](<./VideosDemo/user - fav_ apply _ comment _ rate course _ show course.mp4>) |

### 💬 Communication & Social Features

| Feature | Demo |
|---|---|
| Accept Friend, Chat & Hidden Profile Display | [▶ Watch / Download](<./VideosDemo/accept friend + chat + hidden profile display.mp4>) |
| Website Feedback & Rating | [▶ Watch / Download](./VideosDemo/site%20feedback%20and%20rating.mp4) |

---

## 🔒 Security

Security is important to the SkillDev project.

- Do not publish sensitive credentials.
- Keep environment configuration files private.
- Report security vulnerabilities responsibly.

---

## 📄 License

Please contact the repository owner for licensing and usage permissions.

---

<p align="center">
  <strong>🎓 SkillDev</strong>
  <br />
  Learn • Teach • Connect • Grow
  <br /><br />
  Made with ❤️ for better learning opportunities.
</p>
