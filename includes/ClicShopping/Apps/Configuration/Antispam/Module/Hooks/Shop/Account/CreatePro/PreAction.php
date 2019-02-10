<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\Account\CreatePro;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;
  use ClicShopping\Apps\Configuration\Antispam\Classes\AntispamClass;

  class PreAction implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;

    public function __construct() {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');
      $this->messageStack = Registry::get('MessageStack');
    }

    private function getResultGoogleRecaptcha() {
      $CLICSHOPPING_Hooks = Registry::get('Hooks');

      $error = $CLICSHOPPING_Hooks->call('AllShop', 'GoogleRecaptchaProcess');

      return $error;
    }

    private function getResultHideFieldAntispam() {
      $error = false;

      $antispam = HTML::sanitize($_POST['invisible_recaptcha']);
      $antispam_clisopping = HTML::sanitize($_POST['invisible_clicshopping']);

      if (!empty($antispam) && !empty($antispam_clisopping)) {
        exit();
      }

      return $error;
    }

    public function execute() {
      if (!defined('CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT_PRO') && CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT_PRO == 'False') {
        return false;
      }

      if (isset($_GET['Account']) && isset($_GET['CreatePro']) && isset($_GET['Process'])) {
        $error = false;

        if (defined('CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT_PRO') && CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT_PRO == 'True') {

          if (defined('MODULES_CREATE_ACCOUNT_PRO_SIMPLE_ANTISPAM_STATUS') && MODULES_CREATE_ACCOUNT_PRO_SIMPLE_ANTISPAM_STATUS == 'True' && defined('CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS') && CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS == 'True') {

            $error = AntispamClass::getResultSimpleAntispam();
          }

          if (defined('MODULES_CREATE_ACCOUNT_PRO_RECAPTCHA_STATUS') && MODULES_CREATE_ACCOUNT_PRO_RECAPTCHA_STATUS == 'True' && defined('CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS') &&  CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS == 'True' && $error === false) {

            $error = $this->getResultGoogleRecaptcha();
          }

          if (defined('MODULES_CREATE_ACCOUNT_PRO_SIMPLE_INVISIBLE_ANTISPAM_STATUS') && MODULES_CREATE_ACCOUNT_PRO_SIMPLE_INVISIBLE_ANTISPAM_STATUS == 'True' && defined('CLICSHOPPING_APP_ANTISPAM_INVISIBLE') && CLICSHOPPING_APP_ANTISPAM_INVISIBLE == 'True' && $error === false) {
            $error = $this->getResultHideFieldAntispam();
          }

          if ($error === true) {
            $this->messageStack->add(CLICSHOPPING::getDef('entry_email_address_check_error_number'), 'warning', 'contact');
            CLICSHOPPING::redirect(null, 'Account&CreatePro');
          }
        }
      }
    }
  }