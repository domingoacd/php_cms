HOME / BLOG / WEB DEVELOPMENT / PHP / BUILD A CMS IN AN AFTERNOON WITH PHP AND MYSQL
Build a CMS in an Afternoon with PHP and MySQL
21 JANUARY 2011 / 603 COMMENTS

Build a CMS in an Afternoon with PHP and MySQL
View Demo » | Download Code

14 May 2019: This article and the code were updated for PHP7 compatibility.

Building a content management system can seem like a daunting task to the novice PHP developer. However, it needn’t be that difficult. In this tutorial I’ll show you how to build a basic, but fully functional, CMS from scratch in just a few hours. Yes, it can be done!

Along the way, you’ll learn how to create MySQL databases and tables; how to work with PHP objects, constants, includes, sessions, and other features; how to separate business logic from presentation; how to make your PHP code more secure, and much more!


Before you begin, check out the finished product by clicking the View Demo link above. (For security reasons this demo is read-only, so you can’t add, change or delete articles.) You can also click the Download Code link above to download the complete PHP code for the CMS, so you can run it on your own server.

For this tutorial, you’ll need to have the Apache web server with PHP installed, as well as the MySQL database server running on your computer. Setting all this up is beyond the scope of the tutorial, but a really easy way to do it is simply to install XAMPP on your computer.
The feature list
Our first job is to work out exactly what we want our CMS to do. The CMS will have the following features:

Front end:

The homepage, listing the 5 most recent articles
The article listing page, listing all articles
The “view article” page, letting visitors see a single article
Back end:

Admin login/logout
List all articles
Add a new article
Edit an existing article
Delete an existing article
Each article will have an associated headline, summary, and publication date.

Planning it out
Here are the steps we’ll need to follow to create our CMS:

Create the database
Create the articles database table
Make a configuration file
Build the Article class
Write the front-end index.php script
Write the back-end admin.php script
Create the front-end templates
Create the back-end templates
Create the stylesheet and logo image
This page contains all the code for the CMS, ready for you to copy and paste into your own files. If you don’t want to create the files yourself, simply download the finished zip file, which contains all the code files and folders.
Ready? Grab a cup of tea, and let’s get coding!

Step 1: Create the database
Safe
The first thing we need to do is create a MySQL database to store our content. You can do this as follows:

Run the mysql client program
Open a terminal window and enter the following:
mysql -u username -p

Then enter your MySQL password when prompted.

username should be a user that has permission to create databases. If you’re working on a development server, such as your own computer, then you can use the root user for this, to save having to create a new user.
Create the database
At the mysql> prompt, type:
create database cms;

Then press Enter.

Quit the mysql client program
At the mysql> prompt, type:
exit

Then press Enter.

That’s it! You’ve now created a new, empty database, into which you can put your database tables and content.

Some web server setups let you create databases via a web-based tool such as cPanel or Plesk (in fact sometimes this is the only way to create MySQL databases). If you’re not sure what to do on your server, ask your tech support team for help.
Step 2: Create the articles database table
Our simple CMS has just one database table: articles. This, as you’d imagine, holds all of the articles in the system.

Let’s create the schema for the table. A table’s schema describes the types of data that the table can hold, as well as other information about the table.

Create a text file called tables.sql somewhere on your hard drive. Add the following code to the file:


DROP TABLE IF EXISTS articles;
CREATE TABLE articles
(
  id              smallint unsigned NOT NULL auto_increment,
  publicationDate date NOT NULL,                              # When the article was published
  title           varchar(255) NOT NULL,                      # Full title of the article
  summary         text NOT NULL,                              # A short summary of the article
  content         mediumtext NOT NULL,                        # The HTML content of the article

  PRIMARY KEY     (id)
);
The above code defines the schema for the articles table. It’s written in SQL, the language used to create and manipulate databases in MySQL (and most other database systems).

Let’s break the above code down a little:

Create the articles table
DROP TABLE IF EXISTS articles removes any existing articles table (and data — be careful!) if it already exists. We do this because we can’t define a table with the same name as an existing table.

CREATE TABLE articles ( ) creates the new articles table. The stuff inside the parentheses defines the structure of the data within the table, explained below…

Give each article a unique ID
We’re now ready to define our table structure. A table consists of a number of fields (also called columns). Each field holds a specific type of information about each article.

First, we create an id field. This has a smallint unsigned (unsigned small integer) data type, which means it can hold whole numbers from 0 to 65,535. This lets our CMS hold up to 65,535 articles. We also specify the NOT NULL attribute, which means the field can’t be empty (null) — this makes life easier for us. We also add the auto_increment attribute, which tells MySQL to assign a new, unique value to an article’s id field when the article record is created. So the first article will have an id of 1, the second will have an id of 2, and so on. We’ll use this unique value as a handle to refer to the article that we want to display or edit in the CMS.

<?php
ini_set( "display_errors", true );
date_default_timezone_set( "America/Caracas" );  // http://www.php.net/manual/en/timezones.php
define( "DB_DSN", "mysql:host=localhost;dbname=php_cms" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "12345" );
require( CLASS_PATH . "/Article.php" );

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' );
?>