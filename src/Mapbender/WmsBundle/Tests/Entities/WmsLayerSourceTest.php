<?php


namespace Mapbender\WmsBundle\Tests\Entities;


use Mapbender\WmsBundle\Component\MinMax;
use Mapbender\WmsBundle\Entity\WmsLayerSource;

/**
 * @group unit
 */
class WmsLayerSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testParentChildInitialization()
    {
        $wls0 = new WmsLayerSource();
        $wls1 = new WmsLayerSource();
        /**
         * Owning side SHOULD update child
         * see https://symfony.com/doc/current/doctrine/associations.html#setting-information-from-the-inverse-side
         */
        $wls0->addSublayer($wls1);
        $this->assertSame($wls0, $wls1->getParent());
    }

    public function testMinMaxInfsAreNull()
    {
        $scale = new MinMax(INF, INF);
        $this->assertSame(null, $scale->getMin());
        $this->assertSame(null, $scale->getMax());

        $scale->setMin(INF);
        $scale->setMax(INF);
        $this->assertSame(null, $scale->getMin());
        $this->assertSame(null, $scale->getMax());
    }

    /**
     * @param float|null $a
     * @param float|null $b
     * @dataProvider scaleInitializerProvider
     */
    public function testVerbatimLocalMinMaxFloats($a, $b)
    {
        $scale = new MinMax($a, $b);
        $this->assertSame($a, $scale->getMin());
        $this->assertSame($b, $scale->getMax());

        $wls = new WmsLayerSource();
        $wls->setScale($scale);
        $this->assertSame($scale->getMin(), $wls->getMinScale(false));
        $this->assertSame($scale->getMax(), $wls->getMaxScale(false));
    }

    public function testParentChildTraversal()
    {
        $wls0 = new WmsLayerSource();
        $wls1 = new WmsLayerSource();
        $this->assertCount(0, $wls0->getSublayer());
        $wls0->addSublayer($wls1);
        $this->assertCount(1, $wls0->getSublayer());
        $this->assertContains($wls1, $wls0->getSublayer());
    }

    public function testScaleRecursive()
    {
        $wls0 = new WmsLayerSource();
        $scale0 = new MinMax(5, null);
        $wls0->setScale($scale0);

        $this->assertSame($wls0->getMinScale(), 5.0);
        $this->assertSame($wls0->getMaxScale(), null);

        $wls1 = new WmsLayerSource();
        $scale1 = new MinMax(null, 40);
        $wls1->setScale($scale1);

        $wls1->setParent($wls0);

        $this->assertSame($scale0->getMin(), $wls1->getMinScale(true));
        $this->assertSame($scale1->getMax(), $wls1->getMaxScale(true));

        // add third level (no own scale)
        $wls2 = new WmsLayerSource();
        $wls2->setParent($wls1);
        $this->assertSame($wls1->getMinScale(true), $wls2->getMinScale(true));
        $this->assertSame($wls1->getMaxScale(true), $wls2->getMaxScale(true));

        $this->assertSame(null, $wls2->getMinScale(false));
        $this->assertSame(null, $wls2->getMaxScale(false));

        // add fourth level (own max)
        $wls3 = new WmsLayerSource();
        $scale3 = new MinMax(null, 99);
        $wls3->setScale($scale3);
        $wls3->setParent($wls2);
        $this->assertSame($scale0->getMin(), $wls3->getMinScale(true));
        $this->assertSame($scale3->getMax(), $wls3->getMaxScale(true));

        // add empty MinMax object to level 3 and try again
        // empty object should not block inherticance from level 4 through level 3
        $wls2->setScale(new MinMax());
        $this->assertSame($scale0->getMin(), $wls3->getMinScale(true));
        $this->assertSame($scale3->getMax(), $wls3->getMaxScale(true));
    }

    public function scaleInitializerProvider()
    {
        return array(
            array(null, null),
            array(null, 10.),
            array(5., null),
            array(20., 30.),
        );
    }
}
