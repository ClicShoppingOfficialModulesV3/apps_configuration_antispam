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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\Products\TellAFriend;

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
      if ((!defined('CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS') || CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS == 'False') || CLICSHOPPING_APP_ANTISPAM_TELL_A_FRIEND == 'False') {
        return false;
      }

      if (isset($_GET['Products']) && isset($_GET['TellAFriend']) && isset($_GET['Process'])) {
        if (defined('CLICSHOPPING_APP_ANTISPAM_TELL_A_FRIEND') && CLICSHOPPING_APP_ANTISPAM_TELL_A_FRIEND == 'True') {
          $error = false;

          if (defined('MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_STATUS') && MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_STATUS == 'True' && defined('CLICSHOPPING_APP_ANTISPAM_INVISIBLE') && CLICSHOPPING_APP_ANTISPAM_INVISIBLE == 'True' && $error === false) {
            $error = $this->getResultHideFieldAntispam();
          }

          if ($error === true) {
            $this->messageStack->add(CLICSHOPPING::getDef('entry_email_address_check_error_number'), 'warning', 'contact');
            CLICSHOPPING::redirect(null, 'Products&TellAFriend');
          }
        }
      }
    }
  }