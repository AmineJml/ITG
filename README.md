# ITG Mini-Project Guide

This guide provides instructions and details for the technical interview miniproject in Laravel.

## Prerequisites

Ensure that your system has the following prerequisites before running the project:

- PHP
- Composer
- SQL Database

## Getting Started

After cloning the project, follow these steps:

1. Check and update the `.env` file with the correct database connection information.

   Example `.env` configuration:

![image](https://github.com/AmineJml/ITG/assets/97894740/8fcead06-2a61-4f65-9e24-2833fbc65091)


2. Run the following commands in the terminal from the location `.../itg/miniproject`:

`php artisan migrate` \n
`php artisan serve`\n

now the server is running.

##  APIS:
These are the apis inside the miniProject: \n

#### Register:

POST - URL: `http://localhost:8000/api/register`
![image](https://github.com/AmineJml/ITG/assets/97894740/02109459-1eab-45a0-ba0e-ac8a0cab3579)
![image](https://github.com/AmineJml/ITG/assets/97894740/05cc8a38-b819-4864-96ca-5f54f98322e4)

#### Login:
POST - URL: `http://localhost:8000/api/login`
After login user receive a token that they have to add in the Header of other apis in order for them to work
![image](https://github.com/AmineJml/ITG/assets/97894740/0526ccf1-ac88-4d2d-8238-b256d346bd87)

#### Create task:
POST - URL: `http://localhost:8000/api/task`
![image](https://github.com/AmineJml/ITG/assets/97894740/0d432f22-1643-4c4e-b2d0-aa4494781751)

#### List tasks:
GET - URL: `http://localhost:8000/api/tasks`
![image](https://github.com/AmineJml/ITG/assets/97894740/5e6218a3-5918-4f12-a8b0-91832311c157)

#### List tasks ordered by date:
GET - URL: `http://localhost:8000/api/tasks/sort`
![image](https://github.com/AmineJml/ITG/assets/97894740/47732bf1-203f-4283-b972-d2a281b65c55)

#### Edit task:
PUT - URL: `http://localhost:8000/api/task/{id}`
![image](https://github.com/AmineJml/ITG/assets/97894740/23188e6b-ff02-4dce-b202-e9ec20b9341f)


#### Delete task:
DELETE - URL: `http://localhost:8000/api/task/{id}`
![image](https://github.com/AmineJml/ITG/assets/97894740/de3df482-ce83-4629-8db2-0f6939b6e778)

#### Search and filter tasks:
GET - URL: `http://localhost:8000/api/searchTasks`
![image](https://github.com/AmineJml/ITG/assets/97894740/d55cf65e-2785-45d8-ba6f-fe88dd05dc2b)
![image](https://github.com/AmineJml/ITG/assets/97894740/9ce91b73-9ead-4ad9-86de-73634881e6ed)

#### Create category:
POST - URL: `http://localhost:8000/api/category`
![image](https://github.com/AmineJml/ITG/assets/97894740/444a049a-349c-464b-bd1b-0a34fd111487)

#### Edit category:
PUT - URL: `http://localhost:8000/api/category/{id}`
![image](https://github.com/AmineJml/ITG/assets/97894740/acf5b55b-21b9-43e3-8461-4b7558a9dec9)
![image](https://github.com/AmineJml/ITG/assets/97894740/e58d2ed6-488d-41a8-9246-28d0a03587ae)

#### Delete category:
DELETE - URL: `http://localhost:8000/api/category/{id}`
![image](https://github.com/AmineJml/ITG/assets/97894740/6cf4ca7a-5837-4d7b-bec3-b5131d9c3875)

#### Search categories:
GET - URL: `http://localhost:8000/api/searchCategories`
![image](https://github.com/AmineJml/ITG/assets/97894740/8a8b626c-831b-447e-9c4f-89f97b1fc1a1)
![image](https://github.com/AmineJml/ITG/assets/97894740/0e5201d8-5c40-4965-aeeb-3fab98055e33)

### Add task to category
POST - URL: `http://localhost:8000/api/categorize`
![image](https://github.com/AmineJml/ITG/assets/97894740/a5ea4044-73fb-473d-923e-8a6e04d15846)
![image](https://github.com/AmineJml/ITG/assets/97894740/c3001b6f-c085-4d0b-b2a0-65bc20bdb8ae)








