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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\Account\CreatePro;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\Is;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;

  class PreAction implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;
    protected $antispam;

    public function __construct() {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');
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

      if ((!defined('CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS') || CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS == 'False') && CLICSHOPPING_APP_ANTISPAM_AM_CREATE_ACCOUNT_PRO == 'False') {
        return false;
       }

      if (isset($_GET['Account']) && isset($_GET['CreatePro']) && isset($_GET['Process'])) {
        $error = false;

        if (defined('CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS') && CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS == 'True' && CLICSHOPPING_APP_ANTISPAM_AM_CREATE_ACCOUNT_PRO == 'True' && $error === false) {
          $error = AntispamClass::getResultSimpleAntispam();
        }

        if (defined('CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS') &&  CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS == 'True' && CLICSHOPPING_APP_ANTISPAM_AM_CREATE_ACCOUNT_PRO == 'True' && $error === false) {
          $error = $this->getResultGoogleRecaptch();
        }

        if (CLICSHOPPING_APP_ANTISPAM_INVISIBLE == 'True' && CLICSHOPPING_APP_ANTISPAM_AM_CREATE_ACCOUNT_PRO == 'True' && $error === false) {
          $error = $this->getResultHideFieldAntispam();
        }

        if ($error === true) {
          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_email_address_check_error_number'), 'error', 'create_account_pro');
          CLICSHOPPING::redirect('index.php', 'Account&CreatePro');
        }
      }
    }
  }