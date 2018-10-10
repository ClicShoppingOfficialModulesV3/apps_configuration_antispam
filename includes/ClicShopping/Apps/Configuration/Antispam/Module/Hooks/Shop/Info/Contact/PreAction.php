<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *
 *
 */

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\Info\Contact;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Configuration\Antispam\Classes\AntispamClass;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;

  class PreAction implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;

    public function __construct() {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');
      $this->messageStack = Registry::get('MessageStack');
    }


    private function getResultGoogleRecaptch() {
      $CLICSHOPPING_Hooks = Registry::get('Hooks');

      $error = $CLICSHOPPING_Hooks->call('AllShop', 'GoogleRecaptchaProcess');

      return $error;
    }

    private function getResultHideFieldAntispam() {
      $error = false;
      $antispam = HTML::sanitize($_POST['invisible_recaptcha']);

      if (!empty($antispam)) {
        exit();
      }

      return $error;
    }

    public function execute() {
      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');

      if (!defined('CLICSHOPPING_APP_ANTISPAM_CONTACT') && CLICSHOPPING_APP_ANTISPAM_CONTACT == 'False') {
        return false;
      }

      if (isset($_GET['Info']) && isset($_GET['Contact']) && isset($_GET['Process'])) {
        $error = false;

        if (defined('MODULES_CONTACT_US_SIMPLE_INVISIBLE_ANTISPAM_STATUS') && MODULES_CONTACT_US_SIMPLE_INVISIBLE_ANTISPAM_STATUS == 'True' && CLICSHOPPING_APP_ANTISPAM_CONTACT == 'True' && $error === false) {
          $error = AntispamClass::getResultSimpleAntispam();
        }

        if (defined('CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS') && CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS == 'True' && CLICSHOPPING_APP_ANTISPAM_CONTACT == 'True' && $error === false) {
          $error = $this->getResultGoogleRecaptch();
        }

        if (CLICSHOPPING_APP_ANTISPAM_INVISIBLE == 'True' && CLICSHOPPING_APP_ANTISPAM_CONTACT == 'True' && $error === false) {
          $error = $this->getResultHideFieldAntispam();
        }

        if ($error === true) {
          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_email_address_check_error_number'), 'warning', 'contact');
          CLICSHOPPING::redirect('index.php', 'Info&Contact');
        }
      }
    }
  }