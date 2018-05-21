<?php

namespace AppBundle\Helper;

use AppBundle\Entity\WorkPackage;

class WorkPackageTreeBuilder
{
    public static function buildFromRawData(array $workPackages)
    {
        return self::filterChildren(['type' => -1], $workPackages);
    }

    /**
     * @param array $workPackage
     * @param array $workPackages
     *
     * @return array
     */
    private static function filterChildren(array $workPackage, array $workPackages)
    {
        $out = array_filter($workPackages, function ($wp) use ($workPackage) {
            switch ($workPackage['type']) {
                case -1:
                    return ($wp['type'] == WorkPackage::TYPE_PHASE && !$wp['parent_id'])
                        || (!$wp['phase_id'] && !$wp['milestone_id']);
                case WorkPackage::TYPE_PHASE:
                    return ($wp['type'] == WorkPackage::TYPE_PHASE && $wp['parent_id'] == $workPackage['id'])
                        || ($wp['type'] == WorkPackage::TYPE_MILESTONE && $wp['phase_id'] == $workPackage['id'] && !$wp['parent_id']);
                case WorkPackage::TYPE_MILESTONE:
                    return ($wp['type'] == WorkPackage::TYPE_MILESTONE && $wp['parent_id'] == $workPackage['id'])
                        || ($wp['type'] == WorkPackage::TYPE_TASK && $wp['milestone_id'] == $workPackage['id'] && !$wp['parent_id']);
                case WorkPackage::TYPE_TASK:
                    return $wp['type'] == WorkPackage::TYPE_TASK && $wp['parent_id'] == $workPackage['id'];
            }

            return false;
        });

        $index = 0;
        array_walk($out, function (&$wp) use ($workPackages, &$index) {
            $wp['puid'] = ++$index;
            $wp['children'] = self::filterChildren($wp, $workPackages);
            if (count($wp['children'])) {
                $wp['progress'] = array_reduce($wp['children'], function ($carry, $wp) {
                    return $carry + $wp['progress'];
                }, 0) / count($wp['children']);
            } elseif ($wp['type'] != WorkPackage::TYPE_TASK) {
                $wp['progress'] = 100;
            }
        });

        usort($out, function (array $a, array $b) {
            return $a['puid'] <=> $b['puid'];
        });

        return $out;
    }
}
