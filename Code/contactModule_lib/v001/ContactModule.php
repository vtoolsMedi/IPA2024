<?php
/**
 * Created by PhpStorm.
 * User: peto
 * Date: 20.10.15
 * Time: 14:57
 */

namespace campaign\module\modules\contactModule\v001;

use campaign\module\baseClasses\BaseModule;
use campaign\module\modules\contactModule\v001\controller\ContactController;
use po2\system\db\doctrine\EntityManager;

/**
 * Class ContactModule
 *
 * @property mixed         $parameter
 * @property EntityManager $em
 *
 * @package campaign\module\modules\contactModule
 */
class ContactModule extends BaseModule {

    /**
     * @return string
     * @override
     */
    public function getApplModelsPath(): string {
        return BASE_PATH.'src/appl/module/modules/contactModule/v001/';
    }

    /**
     * @return ContactController|null
     * @override
     */
    public function getControllerInstance(): ContactController {
        return new ContactController();
    }


    /**
     * @return string
     * @override
     */
    public function getModuleName() {
        return ContactModule::class;
    }

    /**
     * @return string
     * @override
     */
    public function getModulePath() {
        return BASE_PATH.'src/lib/campaign/module/modules/contactModule/v001/';
    }
}