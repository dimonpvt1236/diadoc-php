<?php

declare(strict_types=1);

namespace Test\Unit;

use Test\base\BaseTest;
use Test\enums\ConfigNames;

class GetRoamingOperatorsTest extends BaseTest
{
    public function testGetRoamingOperators(): void
    {
        $api = $this->auth()->getApi();

        $operators = $api->getRoamingOperators(getenv(ConfigNames::FROM_BOX_ID));
        self::assertNotEmpty($operators);

        $d = [];
        foreach ($operators->getRoamingOperators() as $item) {
            $operator = [];
            $operator['name'] = $item->getName();
            $operator['fns_id'] = $item->getFnsId();
            $operator['active'] = $item->getIsActive();

            /** @var \Diadoc\Proto\OperatorFeature[] $features */
            $features = $item->getFeatures();
            $operator['features'] = [];
            foreach ($features as $feature) {
                $operator['features'][] = [
                    'name' => $feature->getName(),
                    'description' => $feature->getDescription(),
                ];
            }
            $d[] = $operator;
        }

        self::assertTrue(count($d) > 0);
    }
}
