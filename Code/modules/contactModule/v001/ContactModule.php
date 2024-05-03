<?php
/**
 * Created by PhpStorm.
 * User: peto
 * Date: 20.10.15
 * Time: 14:57
 */

namespace appl\module\modules\contactModule\v001;

use campaign\module\baseClasses\BaseModule;
use appl\module\modules\contactModule\v001\controller\ContactController;
use po2\system\db\doctrine\EntityManager;

/**
 * Class ContactModule
 *
 * @property mixed         $parameter
 * @property EntityManager $em
 *
 * @package appl\module\modules\contactModule
 */
class ContactModule extends \campaign\module\modules\contactModule\v001\ContactModule {

    /**
     * @return ContactController
     * @override
     */
    public function getControllerInstance() :\campaign\module\modules\contactModule\v001\controller\ContactController {
        return new ContactController();
    }

}