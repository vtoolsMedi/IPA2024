<?php

namespace appl\module\modules\contactModule\v001\controller;

use appl\module\modules\contactModule\v001\model\entity\Contact;
use appl\module\modules\contactModule\v001\model\entity\ContactDef;
use campaign\module\baseClasses\ModuleInitializer;
use campaign\module\baseClasses\twig\TwigManager;
use campaign\module\modules\attendanceModule\v001\AttendanceModule;
use appl\module\modules\contactModule\v001\model\form\ContactForm;
use campaign\module\modules\contactModule\v001\types\ContactState;
use campaign\module\modules\entryControlAppModule\v001\EntryControlAppModule;
use campaign\module\modules\ustoreModule\v001\controller\uStoreController;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use po2\controller\AnnotationController;
use po2\system\db\doctrine\EntityManager;
use po2\system\db\exception\SysConnectionError;
use po2\system\exception\SysException;
use po2\system\http\HttpSession;
use po2\system\ServerManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ContactController extends \campaign\module\modules\contactModule\v001\controller\ContactController {
    /**
     * @param null $contact
     *
     * @return string
     * @throws Exception
     *
     */
    public function registerForm($contact = null) {
        global $variables;

        if ($this->canStillRegister($variables)) {

            $showCaptcha = $contact == null;
            $contact = $contact == null ? $this->createContact() : $contact;
            $captchaError = null;
            $request = Request::create($_SERVER['REQUEST_URI'], 'POST', $_POST);

            $builder = TwigManager::getDefault()->getFactory()
                                  ->createBuilder(ContactForm::class, $contact,
                                      ['action' => '#section-contactForm', 'attr' => ['class' => 'form-container']]);
            if ($showCaptcha) {
                $builder->remove(ContactDef::$state);
                if (ModuleInitializer::getDefault()->isModuleEnabled(AttendanceModule::class)) {
                    $builder->remove('addAttendance');
                }
            }

            $form = $builder->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($showCaptcha) {
                    if (isset($_POST['h-captcha-response']) && $this->validateCaptcha($_POST['h-captcha-response'])) {
                        $this->handleContact($form, $variables, $contact);
                    } else {
                        $captchaError = $variables->campaign->captchaError;
                    }
                } else {
                    $this->handleContact($form, $variables, $contact);
                }
            }
            return TwigManager::getDefault()->getTwig()
                              ->render(TwigManager::getTwigBasePath('contactModule/v001/view/registerForm.html.twig'),
                                  ['form' => $form->createView(),
                                   'contact' => $contact, 'captchaError' => $captchaError, 'showCaptcha' => $showCaptcha]);
        }
        return $variables->baseText->maxInfoText;
    }


    /**
     * @param HttpSession|null $session
     *
     * @return void
     * @throws NotSupported
     * @throws ORMException
     * @throws OptimisticLockException
     * @po2\system\RouteEntity{"route": ["^[a-zA-Z0-9_]+\\/delete$"], "isSessionRequired": false, "_class": "po2\\system\\RouteEntity"}
     */
    public function delete(?HttpSession $session) {
        if (isset($_POST['deleteContact'])) {
            $contact = self::getContact($_POST['deleteContact']);
            $contact->emptyRecord(ContactState::_DELETE);
            EntityManager::getDefault()->getEm()->persist($contact);
            EntityManager::getDefault()->getEm()->flush();
            echo true;
        }
    }

    /**
     * @param HttpSession|null $session
     *
     * @return void
     * @throws LoaderError
     * @throws NotSupported
     * @throws RuntimeError
     * @throws SyntaxError
     * @po2\system\RouteEntity{"route": ["^[a-zA-Z0-9_]+\\/deleted$"], "isSessionRequired": false, "_class": "po2\\system\\RouteEntity"}
     *
     */
    public function deleted(?HttpSession $session) {
        $contact = self::getContact($session);
        if ($contact->getState() !== ContactState::_DELETE) {
            header('location: '.ServerManager::getDefault()->getBaseUrl().$contact->getPurl());
        }
        echo TwigManager::getDefault()->getTwig()->render(TwigManager::getTwigBasePath('contactModule/v001/view/deleted/deleted.html.twig'));
    }

    /**
     * @param HttpSession|null $session
     * @param array            $twigVariables
     *
     * @throws LoaderError
     * @throws NotSupported
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @po2\system\RouteEntity{"route": ["^[a-zA-Z0-9_]+\\/confirmationMail$"], "isSessionRequired": false, "_class": "po2\\system\\RouteEntity"}
     */
    public function confirmationMail(?HttpSession $session, array $twigVariables = []) {

        $contact = self::getContact($session);

        if ($contact == null) {
            header('location: '.ServerManager::getDefault()->getBaseUrl());
            exit;
        } elseif ($contact->getState() != ContactState::_UNREGISTERED && $contact->getState() != ContactState::_REGISTERED) {
            header('location: '.ServerManager::getDefault()->getBaseUrl().$contact->getPurl());
            exit;
        }

        echo ContactController::createConfirmationMailView($contact, $twigVariables);
    }


    /**
     * @param mixed $argument
     *
     * @return Contact|null
     * @throws NotSupported
     */
    public static function getContact($argument): ?Contact {
        $purl = "";
        if ($argument instanceof HttpSession) {
            $purl = self::getPurl($argument);
        } elseif (is_string($argument)) {
            $purl = $argument;
        }
        return EntityManager::getDefault()->getEm()->getRepository(ContactDef::$entityClass)->findOneBy([ContactDef::$purl => $purl]);
    }


    /**
     * @param $contact
     * @param $twigVariables
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function createConfirmationMailView($contact, $twigVariables) {
        global $variables;

        $twigVariables['contact'] = $contact;
        if ($contact->getState() == ContactState::_REGISTERED) {
            $twigVariables['subject'] = $variables->confirmationMail->subjectRegistered;
            $twigVariables['textBeforeButton'] = $variables->confirmationMail->textRegisteredBeforeButton;
            $twigVariables['textAfterButton'] = $variables->confirmationMail->textRegisteredAfterButton;
        } else {
            $twigVariables['subject'] = $variables->confirmationMail->subjectUnRegistered;
            $twigVariables['textBeforeButton'] = $variables->confirmationMail->textUnRegisteredBeforeButton;
            $twigVariables['textAfterButton'] = $variables->confirmationMail->textUnRegisteredAfterButton;
        }
        $twigVariables['titleImage'] = $variables->confirmationMail->titleImage;
        $twigVariables['isButtonOn'] = $variables->confirmationMail->buttonOn;
        $twigVariables['buttonText'] = $variables->confirmationMail->buttonText;
        $twigVariables['buttonLink'] = $variables->confirmationMail->buttonLink;
        if (ModuleInitializer::getDefault()->isModuleEnabled(EntryControlAppModule::class)) {
            if($variables->confirmationMail->showQRInConfMail) {
                $twigVariables['qrcodeLink'] = ServerManager::getDefault()->getBaseUrl().$contact->getPurl();
            }

        }
        $twigVariables['openInBrowserLink'] = ServerManager::getDefault()->getBaseUrl().$contact->getPurl().'/confirmationMail';
        return TwigManager::getDefault()->getTwig()
                          ->render(TwigManager::getTwigBasePath('baseModule/v001/view/mail/mailTemplate.html.twig'), $twigVariables);
    }


    /**
     * @param Contact $contact
     * @param         $variables
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function createAndSendConfirmationMail(Contact $contact, $variables) {
        if ($contact->getEmail() != null) {
            $body = ContactController::createConfirmationMailView($contact, []);
            if ($contact->getState() === ContactState::_REGISTERED) {
                $subject = $variables->confirmationMail->subjectRegistered;
            } else {
                $subject = $variables->confirmationMail->subjectUnRegistered;
            }
            $message = uStoreController::createMail($subject, $body,
                [$contact->getEmail() => $contact->getFirstname().' '.$contact->getLastname()],
                [$variables->confirmationMail->sentFromAddress => $variables->confirmationMail->sentFromName], $variables->confirmationMail->bccAddress);
            uStoreController::sendMail($message);
        }
    }

    /**
     * @param string $hCaptchaResponse
     *
     * @return bool
     */
    private function validateCaptcha($hCaptchaResponse): bool {
        if (strpos(gethostname(), 'vgmac') !== false) {
            $secretKey = '0x0000000000000000000000000000000000000000';
        } else {
            $secretKey = '0xd24c4E65a072E47Be1fe3FAc1D41d407e891526E';
        }
        $data = array('secret' => $secretKey, 'response' => $hCaptchaResponse);
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, 'https://hcaptcha.com/siteverify');
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $responseData = json_decode($response);
        if ($responseData->success) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $variables
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws NotSupported
     */
    private function canStillRegister($variables): bool {

        $registrationCount = EntityManager::getDefault()->getEm()->getRepository(ContactDef::$entityClass)->createQueryBuilder('c')
                                          ->select('COUNT(c.id)')
                                          ->where('c.state = :contactState')
                                          ->setParameter('contactState', ContactState::_REGISTERED)->getQuery()->getSingleScalarResult();

        return intval($variables->campaign->maxRegistrations) == 0 || ( intval($variables->campaign->maxRegistrations) > 0 && $registrationCount < intval($variables->campaign->maxRegistrations));
    }


    /**
     * @throws SysConnectionError
     * @throws SysException
     */
    private function createContact() {
        $contact = new Contact();
        $purl = $contact::generatePurl('');
        $contact->setPurl($purl);
        $contact->setVariant($_GET['variant'] ?? null);
        $contact->setState(ContactState::_REGISTERED);
        return $contact;
    }

    /**
     * @param FormInterface                         $form
     * @param                                       $variables
     * @param                                       $contact
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function handleContact(FormInterface $form, $variables, $contact) {
        /** @var Contact $updatedContact */
        $updatedContact = $form->getData();
        if ($updatedContact->getState() == ContactState::_REGISTERED) {
            if (ModuleInitializer::getDefault()->isModuleEnabled(AttendanceModule::class)) {
                ModuleInitializer::getDefault()->getModule(AttendanceModule::class)->getControllerInstance()
                                 ->completeAttendanceRegistration($updatedContact, $variables);
            }
        }
        ContactDef::actualizeSystemFields($updatedContact);

        EntityManager::getDefault()->getEm()->persist($updatedContact);
        if(!$variables->contactForm->participationOn){
            $updatedContact->setState(ContactState::_REGISTERED);
        }
        EntityManager::getDefault()->getEm()->flush();
        ContactController::createAndSendConfirmationMail($updatedContact, $variables);
        header('location: '.ServerManager::getDefault()->getBaseUrl().$contact->getPurl().'/thanks');
        exit;
    }

}