# Snowboard Freestyle Website

## Introduction
Snowboard Freestyle Site is a web application developed to meet the needs of snowboarding enthusiasts, designed to showcase and discuss various snowboarding tricks and figures. The site offers the following key features:

## Features:
1. Directory of Snowboarding Figures
Explore a curated list of snowboarding figures to get started, with the flexibility for users to contribute and expand the directory.

2. Figure Management
Manage snowboarding figures effortlessly with functionalities for creating, modifying, and viewing detailed information about each figure.

3. Shared Discussion Space
Engage in a community-driven discussion space where users can share insights, tips, and experiences related to each snowboarding figure. This common area fosters a sense of community and knowledge-sharing among snowboarding enthusiasts.

## Pages:
1. Home Page: 
Discover the list of featured snowboarding figures directly on the homepage.
2. Create Figure Page: 
Add new snowboarding figures to the directory with ease.
3. Edit Figure Page: 
Modify and update details of existing snowboarding figures.
4. Figure Presentation Page: 
Access detailed information about a specific snowboarding figure, including the shared discussion space.

## How to Use:
Explore Figures: Visit the homepage to explore the existing snowboarding figures.
Contribute: Add new figures or update existing ones to enhance the directory.
Discuss: Engage in discussions on the presentation page to share and gain insights into different snowboarding figures.
Start your snowboarding journey with Snowboard Freestyle Site today!

## Developers

This website is developed using PHP with the Symfony framework. Developers interested in contributing to or running the project locally should follow these steps:

1. **Clone the Repository:**
git clone https://github.com/votre-utilisateur/snowboard-freestyle.git

2. **Install Dependencies:**
composer install

3. **Set Up Database:**
- Create a MySQL database.
- Configure the `.env` file with your database credentials.
- Run migrations:
  php bin/console doctrine:migrations:migrate

4. **Run the Symfony Server:**
php bin/console server:run

5. **Access the Application:**
Open your web browser and go to `http://localhost:8000`.

## Database

The application uses MySQL for the database. Make sure you have PHPMyAdmin or a similar tool to manage the database.

## Important Information

- If a user is not verified, a warning message appears, providing a link to resend the activation email.
- Flash messages are displayed throughout the site to convey important information to users.

## Dependencies

The project uses various dependencies, including Bootstrap, Google Fonts, and other vendor files.

## Scripts and Stylesheets

JavaScript and CSS files are included to enhance the user interface. You can find these files in the `assets` directory.

## Contributing

Feel free to contribute to the project by forking the repository and creating pull requests.
