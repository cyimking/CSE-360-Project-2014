<?php
/***************************************************************/
/**************** FUNCTIONAL TESTING PART A ********************/
/***************************************************************/
/* TESTING: 
		- GUEST IS NOT LOGGED IN AND GO TO THE SETTINGS PAGE 
		- GUEST LOGS IN, PURCHASE A Ticket to an Event
		- MEMBER LOGGED OUT 
*/


$I = new FunctionalTester($scenario);
$I->amOnPage('/settings.php');
$I->see('You must be signed in to view this page');
$I->click('Sign In');
$I->fillField('email','joe@test.com');
$I->fillField('pass','Testing24');
$I->click('#sign_in_submit');
$I->click('Explore');
$I->see('Fallout 4');
$I->click('Buy','#event_31');
$I->seeCurrentUrlEquals('/CSE_310/events.php?action=purchase&do=31');
$I->fillField('tickets','4');
$I->click('Purchase');
//$I->waitForElement('#header', 5);
$I->see('You have successfully purchased tickets to this event!');
$I->click('#signoutss');
$I->seeCurrentUrlEquals('/CSE_310/index.php');
?>