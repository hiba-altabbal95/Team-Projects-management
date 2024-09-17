# Team_Project_Management_system

this project is team project  Management system built in Laravel 10 

### Technologies Used:
- **Laravel 10**
- **PHP**
- **MySQL**
- **XAMPP** (for local development environment)
- **Composer** (PHP dependency manager)
- **Postman Collection**: Contains all API requests for easy testing and interaction with the API.

## Features
-Admin can add user and projects
-Manager can add or update task in his projects 
-developer edit status of task that assigned to him.
-tester can add note to his task





## Setting up the project

1. Clone the repository 

   git clone https://github.com/hiba-altabbal95/Team-Projects-management.git
   
2. navigate to the project directory
  
    cd Task-Management-system  

3. install Dependencies: composer install 

4. create environment file  cp .env.example .env
  
5. edit .env file (DB_DATABASE=team_projects)

6. Generate Application Key php artisan key:generate

7. Run Migrations To set up the database tables, run: php artisan migrate

8. Run this command to generate JWT Secret
   
   php artisan jwt:secret

	
9. Run the Application
   
    php artisan serve

10. in file (Team Project Management.postman_collection) there are a collection of request to test api.




