<?php
/***************************************************************/
/**************** FUNCTIONAL TESTING PART B.3 ********************/
/***************************************************************/
/* TESTING: 
		- CHECK IF THE NEW USERNAME WENT THROUGH
		- GO TO PROFILE PAGE
		- SIGNOUT
*/


$I = new FunctionalTester($scenario);
$I->amOnPage('/');
$I->fillField('email','Envy@test.com');
$I->fillField('pass','Testing24');
$I->click('#sign_in_submit');
$I->click('Envy_HR');
$I->see("You are now viewing Envy_HR's profile page!");
$I->click('Settings');
$I->click('Account Settings');
$I->fillField('username','Envy');
$I->click('Update Username');