Version 1.1.1
------------------------------------------------------
- Fully completed the Events File. All the functions are located under the process/event_functions.php file

- Minor Fixes / Updates for the Members File
	* implemented a "required" for each form input. This will not allow a user to enter a null input. 
	** The check if form is filled check will be removed in 1.1.2 or 1.2.0 depending if the profile / setting page is completed
	

- Implemented a new function for global_function.php
	* clip_string($string,$characters) = Return a new string that strlen isn't greater than the $character
	* $string = "ABCD"; clip_string($string, 3); //return "ABC"

- Database Changes
	* Please make the events->date_of_event type "int" instead of DATE! This is because we are converting the date into a string using strtotime for easy 
	formatting.

- Future Updates
	* I will work on making the site look better. Mainly because we should have the best project compared to the others :) 
	* I will also look into making the site more secured. Mainly because of session hijacking. I know we will never have to worry about this but it will make 
	good practice. 
	* I will start the unit testing stuff. 
	

- Input Images
	* I believe IAN will be handling the images input. I do not think that we need an image for the events but if we have time, then I will implement it.