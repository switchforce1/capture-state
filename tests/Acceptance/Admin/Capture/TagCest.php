<?php

declare(strict_types=1);

namespace Tests\Acceptance\Admin\Capture;

use Tests\Support\AcceptanceTester;

class TagCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function tagPageTest(AcceptanceTester $I)
    {
        $I->amOnPage('admin');
        $I->see('Tags');
        $I->seeLink('Tags');
        $I->click('Tags');
        $I->canSeeInTitle('Tag');
        $I->see('Tag');
        $I->seeElement('table');
        $I->see('Github');
    }
}
