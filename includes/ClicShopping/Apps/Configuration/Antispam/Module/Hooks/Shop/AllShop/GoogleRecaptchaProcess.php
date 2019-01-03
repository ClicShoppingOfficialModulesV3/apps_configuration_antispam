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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\AllShop;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;

  use ClicShopping\Apps\Configuration\Antispam\Classes\reCAPTCHA;

  class GoogleRecaptchaProcess implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;
    protected $antispam;

    public function __construct() {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');
    }

/*
 * Google recaptcha
 * @return $error, false if it's ok, true, if there is an error
 */

    public function execute() {
      if (defined('CLICSHOPPING_APP_ANTISPAM_RE_PRIVATE_KEY')) {
        if (!empty(CLICSHOPPING_APP_ANTISPAM_RE_PRIVATE_KEY) && CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS == 'True') {

          Registry::set('reCAPTCHA', new reCAPTCHA());
          $reCAPTCHA = Registry::get('reCAPTCHA');

          $reCAPTCHA->setSecretKey(CLICSHOPPING_APP_ANTISPAM_RE_PRIVATE_KEY);


          if ($reCAPTCHA->isValid($_POST['g-recaptcha-response']))  {
            $error = 'false';
          } else {
            $error = 'true';
          }
        }
      }

      return $error;
    }
  }