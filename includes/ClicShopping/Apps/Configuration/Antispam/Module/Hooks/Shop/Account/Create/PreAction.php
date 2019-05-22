<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\Account\Create;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;
  use ClicShopping\Apps\Configuration\Antispam\Classes\AntispamClass;

  class PreAction implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');
      $this->messageStack = Registry::get('MessageStack');
    }

    private function getResultGoogleRecaptcha()
    {
      $CLICSHOPPING_Hooks = Registry::get('Hooks');

      $error = $CLICSHOPPING_Hooks->call('AllShop', 'GoogleRecaptchaProcess');

      return $error;
    }

    private function getResultHideFieldAntispam()
    {
      $error = false;

      $antispam = HTML::sanitize($_POST['invisible_recaptcha']);
      $antispam_clicshopping = HTML::sanitize($_POST['invisible_clicshopping']);

      if (!empty($antispam) && !empty($antispam_clicshopping)) {
        exit();
      }

      return $error;
    }

    public function execute()
    {
      if (!defined('CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT') && CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT == 'False') {
        return false;
      }

      if (isset($_GET['Account']) && isset($_GET['Create']) && isset($_GET['Process'])) {
        if (defined('CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT') && CLICSHOPPING_APP_ANTISPAM_CREATE_ACCOUNT == 'True') {
          $error = false;
          $error_simple = false;
          $error_invisible = false;
          $error_recaptcha = false;

          if (defined('MODULES_CREATE_ACCOUNT_SIMPLE_ANTISPAM_STATUS') && MODULES_CREATE_ACCOUNT_SIMPLE_ANTISPAM_STATUS == 'True' && defined('CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS') && CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS == 'True') {
            $error_simple = AntispamClass::getResultSimpleAntispam();
          }

          if (defined('MODULES_CREATE_ACCOUNT_RECAPTCHA_STATUS') && MODULES_CREATE_ACCOUNT_RECAPTCHA_STATUS == 'True' && defined('CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS') && CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS == 'True' && $error === false) {
            $error_recaptcha = $this->getResultGoogleRecaptcha();
          }

          if (defined('MODULES_CREATE_ACCOUNT_SIMPLE_INVISIBLE_ANTISPAM_STATUS') && MODULES_CREATE_ACCOUNT_SIMPLE_INVISIBLE_ANTISPAM_STATUS == 'True' && defined('CLICSHOPPING_APP_ANTISPAM_INVISIBLE') && CLICSHOPPING_APP_ANTISPAM_INVISIBLE == 'True' && $error === false) {
            $error_invisible = $this->getResultHideFieldAntispam();
          }

          if ($error_simple === true || $error_recaptcha[0] || $error_invisible === true) {
            $error = true;
          }

          if ($error === true) {
            $this->messageStack->add(CLICSHOPPING::getDef('entry_email_address_check_error_number'), 'warning', 'contact');
            CLICSHOPPING::redirect(null, 'Account&Create');
          }
        }
      }
    }
  }