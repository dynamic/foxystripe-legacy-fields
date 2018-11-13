<?php

namespace Dynamic\FoxyStripe\Test;

use Dynamic\FoxyStripe\Page\ProductPage;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;

class ProductPageLegacyTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     *
     */
    public function testGetCMSFields()
    {
        $object = $this->objFromFixture(ProductPage::class, 'one');
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }
}
