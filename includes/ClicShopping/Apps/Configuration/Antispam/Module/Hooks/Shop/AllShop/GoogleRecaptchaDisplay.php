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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\AllShop;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;

  use ClicShopping\Apps\Configuration\Antispam\Classes\reCAPTCHA;

  class GoogleRecaptchaDisplay implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');
    }

    public function display()
    {
      $output = '';

      if (defined('CLICSHOPPING_APP_ANTISPAM_RE_PUBLIC_KEY')) {
        if (!empty(CLICSHOPPING_APP_ANTISPAM_RE_PUBLIC_KEY) && CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS == 'True') {

          Registry::set('reCAPTCHA', new reCAPTCHA());
          $reCAPTCHA = Registry::get('reCAPTCHA');

          $reCAPTCHA->setSiteKey(CLICSHOPPING_APP_ANTISPAM_RE_PUBLIC_KEY);

          $output = $reCAPTCHA->getScript();
          $output .= $reCAPTCHA->getHtml();
        }
      }
      return $output;
    }
  }