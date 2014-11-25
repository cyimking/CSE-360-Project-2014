<?php
/***************************************************************/
/**************** FUNCTIONAL TESTING PART B ********************/
/***************************************************************/
/* TESTING: 
		- GUEST IS NOT LOGGED IN AND REGISTRAR ACCOUNT 
*/


$I = new FunctionalTester($scenario);
$I->amOnPage('/');
$I->fillField('username','Envy');
$I->fillField('email_reg','Envy@test.com');
$I->fillField('password','Testing24');
$I->fillField('password2','Testing24');
$I->click('#register_input_submit_fail');

//REDIRECTION DOESN'T WORK ON PHPBROWSER SO I HAVE TO MAKE ANOTHER TEST FOR GOING TO THE PROFILE PAGE AND LOGGING OUT!