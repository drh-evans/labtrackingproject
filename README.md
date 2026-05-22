<div align="center">
<h1>A Lab System That Improves the Student Learning Experience</h1>
 
**David Rhys Evans (2316644)<br> Swansea University**

[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat&logo=alpine.js&logoColor=black)](https://alpinejs.dev)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4E56A6?style=flat&logo=livewire&logoColor=white)](https://livewire.laravel.com)
<br>
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat&logo=docker&logoColor=white)](https://www.docker.com)
 
</div>

## Abstract

In an ever-changing world full of technology and its growing use in education, Computer Science laboratory sessions remain inconsistent, staff require tools that support queue and grade management, and students require real-time assistance. Current solutions, such as Lab Tracker and Canvas, focus on administrative actions and lack real-time features to support labs. This results in courses relying on third-party solutions if they wish to implement queues or chatrooms, resulting in a fragmented experience that disrupts learners.

This project tackles these limitations by building a fully integrated laboratory management system with real-time functionality using Laravel, Livewire, and WebSocket technology. The system provides the current functionality of the lab tracker, implementing bulk CSV uploads for fast user and module creations, and task sign-off. However, it expands on the existing application by implementing a real-time queue system, an anonymous chatroom, a weekly progress report for staff, and analytics dashboards for chatroom and queue stats, including word clouds to recognise frequently discussed topics.

The system was evaluated through a combination of automated unit tests and user acceptance testing. With all surveyed users preferring the produced solution to previous approaches and describing the interface as satisfactory. Results from testing indicate that students found the integrated solution to be engaging and simple to use, although some noted concerns regarding the usability and abuse of a chatroom, highlighting areas for improvement. Future work includes developing an anonymous feedback system to provide staff with meaningful student feedback and exploring the use of artificial intelligence to support the laboratory experience.

---
 
## Project Overview
 
University CS lab sessions often rely on the use of many systems, queue systems bolted on here, chatrooms hosted elsewhere, grading done somewhere else entirely. This system provides an integrated approach to lab sessions at university.
 
**For staff:**
- Bulk CSV uploads for fast user and module creation
- Task sign-off and grade management
- Weekly progress reports
- Analytics dashboards with word clouds to surface frequently discussed topics

**For students:**
- Real-time queue system to request help from demonstrators
- Anonymous chatroom for peer discussion during labs
- A single, consistent interface — no jumping between platforms
- 
---
## Running The Project Locally
 
### Prerequisites
 
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
### 1. Install Laravel Sail
 
```bash
composer require laravel/sail --dev
```
 
### 2. Start the application
 
Each of the following commands should be run in its **own terminal tab**:
 
```bash
# Start the Docker containers
sail up
 
# Compile frontend assets (with hot reloading)
sail npm run dev
 
# Start the WebSocket server
sail artisan reverb:start --debug
 
# Process background jobs
sail artisan queue:work
```
 
> **Tip:** You can run `sail up -d` to start the containers in detached (background) mode, freeing up that terminal.
 
Once everything is running, the application will be available at **http://localhost**.
 
---

## Software Testing
 
The project was evaluated through a combination of **automated unit/integration tests** and **user acceptance testing (UAT)**. To run the test suite:
 
```bash
sail artisan test
```
 
---
 
## Future Work
 
- Anonymous feedback system so students can share meaningful feedback with staff
- AI-assisted support during laboratory sessions
- Improved chatroom moderation and abuse prevention
---
 
## Licence
 
This project was produced as a final year dissertation project. Please contact me before reusing any part of this codebase. It is highly advised to not reuse anything here in the event that you are a student completing your dissertation, and I am not liable for any academic misconduct claims you encounter if you do.
 
