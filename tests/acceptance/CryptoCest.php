<?php

class CryptoCest
{
    public function tryToTest(AcceptanceTester $I)
    {

        $I->amGoingTo('/crypto');
        $I->amOnPage('/crypto2');
//        $I->seeElement('body > div > div.row > div > table');
//        $I->seeElement('Name', '.table th');
//        $I->seeElement('Symbol', '.table th');
//        $I->seeElement('Opening Price', '.table th');
//        $I->seeElement('Closing Price', '.table th');
//        $I->seeElement('Change %', '.table th');
    }
}
