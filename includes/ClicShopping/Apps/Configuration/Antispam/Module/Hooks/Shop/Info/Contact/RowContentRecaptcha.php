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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\Hooks\Shop\Info\Contact;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;

  class RowContentRecaptcha implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;

    public function __construct()   {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');

      $this->app->loadDefinitions('Module/Hooks/Shop/Info/Contact/row_content');
    }


    public function display()  {
      $CLICSHOPPING_Hooks = Registry::get('Hooks');

      if ((!defined('CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS') || CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS == 'False') && CLICSHOPPING_APP_ANTISPAM_AM_CONTACT == 'False') {
        return false;
      }

      $output = '';

      $content = '<div class="row col-md-12" id="RowContent12">';
      $content .= $CLICSHOPPING_Hooks->output('AllShop', 'GoogleRecaptchaDisplay');
      $content .= '</div>';


        $output = <<<EOD
<!-- ######################## -->
<!--  Start Antispam Hooks      -->
<!-- ######################## -->
<script>
$('#RowContent12').append(
    '{$content}'
);
</script>
<!-- ######################## -->
<!--  End Antispam App      -->
<!-- ######################## -->

EOD;
        return $output;

    }
  }