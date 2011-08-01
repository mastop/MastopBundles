<?php

namespace Mastop\MenuBundle\Document;

use Mastop\SystemBundle\Document\BaseRepository;

class MenuRepository extends BaseRepository {

    /**
     * Pega um pelo Bundle e Code
     * 
     * @param string $bundle
     * @param string $code
     * @return Menu or null
     */
    public function findByBundleCode($bundle, $code) {
        return $this->findOneBy(array('bundle' => $bundle, 'code' => $code));
    }

    public function getChildrenByCode($menu, $code) {
        $childs = $menu->getChildren();
        if (count($childs) > 0) {
            foreach ($childs as $m) {
                if($m->getCode() == $code){
                    return $m;
                }
                if($m2 = $this->getChildrenByCode($m, $code)){
                    return $m2;
                }
            }
        }
        return false;
    }

}