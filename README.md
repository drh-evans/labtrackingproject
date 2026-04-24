# A Lab System That Improves the Student Learning Experience

## David Rhys Evans (2316644)

### Abstract

In an ever-changing world full of technology and its growing use in education, Computer Science laboratory sessions remain inconsistent, staff require tools that support queue and grade management, and students require real-time assistance. Current solutions, such as Lab Tracker and Canvas, focus on administrative actions and lack real-time features to support labs. This results in courses relying on third-party solutions if they wish to implement queues or chatrooms, resulting in a fragmented experience that disrupts learners.

This project tackles these limitations by building a fully integrated laboratory management system with real-time functionality using Laravel, Livewire, and WebSocket technology. The system provides the current functionality of the lab tracker, implementing bulk CSV uploads for fast user and module creations, and task sign-off. However, it expands on the existing application by implementing a real-time queue system, an anonymous chatroom, a weekly progress report for staff, and analytics dashboards for chatroom and queue stats, including word clouds to recognise frequently discussed topics.

The system was evaluated through a combination of automated unit tests and user acceptance testing. With all surveyed users preferring the produced solution to previous approaches and describing the interface as satisfactory. Results from testing indicate that students found the integrated solution to be engaging and simple to use, although some noted concerns regarding the usability and abuse of a chatroom, highlighting areas for improvement. Future work includes developing an anonymous feedback system to provide staff with meaningful student feedback and exploring the use of artificial intelligence to support the laboratory experience.

### Running the application locally

In order to run you must have Docker installed, from there you must install Laravel sail which can be done using the following composer command:
composer require laravel/sail --dev

Once sail has been installed you must then run the following commands in seperate terminals to run the application locally:
sail up -d (-d, optional)
sail npm run dev (in seperate terminal)
sail artisan reverb:start --debug
sail artisan queue:work
