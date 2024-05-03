<?php
/**
 * Created by PhpStorm.
 * User: peto
 * Date: 23.11.23
 * Time: 16:15
 */

use campaign\module\modules\afterEventModule\v001\AfterEventModule;
use campaign\module\modules\attendanceModule\v001\AttendanceModule;
use appl\module\modules\contactModule\v001\ContactModule;
use campaign\module\modules\functionsModule\v001\FunctionsModule;
use campaign\module\modules\galleryModule\v001\GalleryModule;
use campaign\module\modules\landingModule\v001\LandingModule;
use campaign\module\modules\surveyModule\v001\SurveyModule;
use campaign\module\modules\ustoreModule\v001\uStoreModule;
use campaign\module\baseClasses\ModuleInitializer;
use campaign\module\modules\entryControlAppModule\v001\EntryControlAppModule;

require_once BASE_PATH.'src/lib/campaign/init/initModule.php';

$moduleInitializer = ModuleInitializer::getDefault();

$moduleInitializer->addModule(new uStoreModule());
$moduleInitializer->addModule(new AttendanceModule());
$moduleInitializer->addModule(new LandingModule());
$moduleInitializer->addModule(new ContactModule());
$moduleInitializer->addModule(new FunctionsModule());
$moduleInitializer->addModule(new AfterEventModule());
$moduleInitializer->addModule(new GalleryModule());
$moduleInitializer->addModule(new SurveyModule());
$moduleInitializer->addModule(new EntryControlAppModule());
