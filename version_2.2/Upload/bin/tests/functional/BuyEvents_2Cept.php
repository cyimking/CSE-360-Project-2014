<?php
/***************************************************************/
/**************** FUNCTIONAL TESTING PART B.2 ********************/
/***************************************************************/
/* TESTING: 
		- GUEST IS NOT LOGGED IN AND REGISTRAR ACCOUNT 
		- GUEST LOGS IN 
		- MEMBER GO TO PROFILE PAGE
		- MEMBER CLICK SETTINGS
		- MEMBER CLICK ACCOUNT SETTINGS
		- MEMBER CHANGE USERNAME
*/


$I = new FunctionalTester($scenario);
$I->amOnPage('/');
$I->fillField('email','Envy@test.com');
$I->fillField('pass','Testing24');
$I->click('#sign_in_submit');
$I->click('Envy');
$I->see("You are now viewing Envy's profile page!");
$I->click('Settings');
$I->click('Account Settings');
$I->fillField('username','Envy_HR');
$I->click('Update Username');