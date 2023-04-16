<?php

declare(strict_types=1);

namespace Tests\Acceptance\Admin;

use Tests\Support\AcceptanceTester;

class DashboardCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function dashboardContentTest(AcceptanceTester $I)
    {
        $I->amOnPage('admin');
        $I->see('Snapshot Dashboard');
        $I->see('Tags');
    }
}
