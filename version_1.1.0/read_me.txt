Version 1.1.0
-------------------------------------------
In order for this version to work, please go to the database (wamp -> phpmyadmin -> cse310 (or whatever you named it)) and make the following changes:
	1. On the Events Table, Add in "user_id" AFTER events_id. The user_id will be a type int and that's it. Leave everything else blanked.
	2. On the Events Table, Change the "date_of_event" to an INT type! 

New Features
	- Events Page (add event and view event)
	- 406 Page: Will be displayed to guest who are trying to view member's only files

Changes
	- Created a global_function file that will host all the global_functions. We will add more as we program.
	- Created an user_function file that will host all the help functions for the member's main functions.
	- Small changes to the members file
	- Add some graphics and one JS function to make it look nice and not hurt our eyes!

Future Updates
	- Create a events_functions file to host all events function. The event file will only be running functions to make that section faster.
	- Better security encryption for sessions. 
	