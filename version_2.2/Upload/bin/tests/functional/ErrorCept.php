<?php
/***************************************************************/
/**************** FUNCTIONAL TESTING PART C ********************/
/***************************************************************/
/* TESTING: 
		- LOGIN WITH INVALID USERNAME + COMBITION
		- REGISTRAR WITH EXISTED USERNAME
*/


$I = new FunctionalTester($scenario);
$I->amOnPage('/');
$I->fillField('email','mark@test.com');
$I->fillField('pass','Testing');
$I->click('#sign_in_submit');
$I->see('Invalid Email ID and Password!');
$I->fillField('username','JonDanas');
$I->fillField('email_reg','JonDanas@test.com');
$I->fillField('password','Testing24');
$I->fillField('password2','Testing24');
$I->click('#register_input_submit_fail');
$I->see('Username is already taken');