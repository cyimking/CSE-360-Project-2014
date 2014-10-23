Version 1.0.0
--------------------------------
Files Added 
- index.php
- members.php
- profile.php

Folder Added
- css
- css/images 
- process
- profiles

Functionality
--------------------------------
index.php (main root)
- Check if the user is sigin in
- True = Update NAV Links and Display Events Page (not added)
- False = Display Registration / Sign In Form

members.php (main functions)
- Display Registration Form
- Display Sign In Form (login form)
- Check Registration Form for Errors
- Check Sign In for errors
- Register User (add user into database)
- Signin User
- Sign Out User

members.php (side functions)
- Encrypt String 
- Check if email is already in db
- Check if username is already in db
- Clean Word to prevent MySQL Errors <--- May Not Be Working
- Create Directory for new users to store profile image
- Install Profile Image.. copy image from css/images to the user's new folder (profile picture)

profile.php
- Display 3 strings: 
	* User's Signed In and Is Viewing His Account
	* User's Signed in and viewing someone's else accoutn
	* User's entered an invalid ID number

css folder
- index.php
	*redirect to homepage.
- image folder
	*Currently a place holder for the default profile image

process folder
- index.php	
	*redirct to homepage
-header.php
	* start global session
	* Check if a user is signed in or not (check_session())
	* Display NAV Links
-config.php
	* Connect to DB and Server

profiles
-

----------------------------------------------------
Version 1.0.0 Notes
----------------------------------------------------
1. I did not bother to implement in CSS into the code which is why the code does not matches the format.
I did this because we can also handle the design after the PHP code is finished. We can also add in
JavaScript if we have time because this will make the site look better and make things easier for the
User (check forms, etc...). 

2. I just added in some code into the profile page as a place holder. This will of course be well 
develop. 

3. This version is not completed thus do not expect robust code. However, I believe that the main code
is working properly thus you should not see any errors. If so then LOG them.

4. Throughout this version I use both "Sign In" and "Log In". They are the same thing. In the next version 
(1.0.1), this will be fixed and everything will be called Sign In or Sign Out.

5. I implemented the default profile image function, however, I did not implement the code to add in the 
link from the folder to the DB. Thus if you want to display the profile image for the user (which is getting
the link from the DB), it will not work as of now. This can be implemented by the me or whoever is running the 
profile.php page.

6. Are each events suppose to have a image? 

7. The event page is not implemented


--------------------------------------------------------
Project Notes
--------------------------------------------------------
1. Please have WAMP, LAMP or MAMP installed on your computers. You can download them here http://www.wampserver.com/en/ or lampserver or mampserver, etc...
This will simple install everything that you need to run this software. The server will be "localhost" and you can access it by starting the wamp server then
going to browser and go to localhost (not www.localhost.com but localhost). 

2. In order to get everything working, you will first have to get your WAMP set up properly. You have to add in a password and fix the issue where it doesn't
show your project correctly (You click it and instead of localhost/cse310, it will send you to cse310 ; which isn't a bug but very annoying). Both are simple fix 
but will require some steps. 

How to Fix URL ISSUE:
	1. Go to your WAMP Folder. Normally under the OS Directory
	2. Go to the www folder
	3. Open the index.php file
	4. Change Line 30 from $suppress_localhost = true; to $suppress_localhost = false;

How to change Password:
	1.Install wamp2 server

	2.Start->run->cmd-> (You might have to run as adminstrator)

	3.Type cd\ and press enter

	YOU WILL HAVE TO CHANGE THIS SO IT CAN FIND YOUR VERSION OF MYSQL. CHECK FOLDER FOR THE VERSION NUMBER!! (STEP 4 / 5)

	4.Locate your mysql directory(like this is my default directory C:\wamp \bin\mysql\mysql5.1.36\bin)
	
	5.After like this you should have do C:\wamp\bin\mysql\mysql5.1.36\bin> mysql -u root -h 127.0.0.1 -P 3306 -p 
	press enter after you got 
	Enter Password don't put anything simply click enter button

	6.After like this mysql>

	7.simply type mysql>use mysql; mysql>show tables; mysql>select * from user \G;

	8.You can set your new password: mysql> UPDATE mysql.user SET Password=PASSWORD('xxxx') WHERE User='root'; 
	(where replace 'xxxx' with your new password) 
	(eg.mysql> UPDATE mysql.user SET Password=PASSWORD('muthu') WHERE User='root';)

	9.Finally type mysql> FLUSH PRIVILEGES; mysql>exit;

	10.Check your settings: mysql> mysql -u root -p
	enter password:***** 
	press enter button.....

	11. Open the phpmyadmin folder and open up the this file - config.inc.php
	
	12. Change password on this line - $cfg['Servers'][$i]['password'] = 'enter_password_here'; Then save


3. Now time to add in the TABLES for the database!	
	1. Go to localhost phpmyadmin
	2. Create a new DB (name it whatever, I named mines cse310)
	2.1 - If the format is blank then leave it blank!
	3. Create Table named "user" and the number of columns "7" (follow format):
	
NAME              TYPE     LENGTH / VALUES    DEFAULT  COLLATION  ATTRIBUTES   NULL    INDEX    A_I             COMMENTS
user_id            int                                                               primary    check the A_I
username         varchar         32              
email            varchar        1024
password         varchar         32
profile_avatar   varchar        100
first_name       varchar         32
last_name        varchar         32

	4. Create Table named "events" and the number of columns "8"

NAME              TYPE     LENGTH / VALUES    DEFAULT  COLLATION  ATTRIBUTES   NULL    INDEX    A_I             COMMENTS
event_id           int                                                                primary   check
title		 varchar 	32
type		 varchar	32
venue		 varchar	32
date_of_event	 date
date_of_creation date	
max_tickets	  int	
tickets_bought 	  int

	5. Create Table named "events_booked" and the number of columns "5"
NAME              TYPE     LENGTH / VALUES    DEFAULT  COLLATION  ATTRIBUTES   NULL    INDEX    A_I             COMMENTS
events_booked_id   int                                                               primary    check the A_I
event_id	   int
user_id		   int
tickets_bought     int
time_bought	   date

4. YAY! Now it's time to make some adjustments in our files before we can actually use it! 
	1. Go to the config.php file (in process folder)
	2. Update the $DB_USER, $DB_Pass, and $DB_NAME variables with YOUR VARIABLE. My password is "password". The username is normally "root" unless you change it
	3. Save.

5. Whoa. We are almost finished. Now we just have to go ahead and add in the files / folders onto wamp so we can view it.
	1. Make sure you download ALL THE FILES from GITHUB (index.php, members.php, profile.php, css folder, profiles folder, process folder)
	2. Locate your WAMP folder and open up the www folder
	3. Create a new folder and name it whatever. I named it "cse310". The name of this folder will be display on the localhost page under Your Projects
	4. Upload all files into this folder. From there if you go to localhost/cse310 (or whatever you named it), you will be on the index.php page!
	5. Now if the localhost page is not loading then simply start the wamp server. Search it if you are on windows 8. 

6. Now you should have everything working!!!!! I will email you guys after I upload a new files on GITHUB. I like Web Development so I am very interested in this program.
We should be finished by Sunday then next week we can just focus on unit testing and making it look pretty. If we finish that then we can add in Javascript and maybe even
AJAX. 

7. I know you guys never used PHP before soooo please go to W3Schools to learn more. We are not doing anything advanced so everything will be there. If you need help
then email me. I am always near my phone or pc so I can help at all times.


If you have any questions then please email me or text me (ask for my number via text lol). I tried to make this simple (installation but I might missed something). 
