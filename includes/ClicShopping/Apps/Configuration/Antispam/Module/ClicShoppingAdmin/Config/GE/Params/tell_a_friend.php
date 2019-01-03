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


  namespace ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\GE\Params;

  use ClicShopping\OM\HTML;

  class tell_a_friend extends \ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\ConfigParamAbstract {
    public $default = 'False';
    public $sort_order = 120;

    protected function init() {
        $this->title = $this->app->getDef('cfg_recaptcha_antispam_tell_a_friend_title');
        $this->description = $this->app->getDef('cfg_recaptcha_antispam_tell_a_friend_description');
    }

    public function getInputField()  {
      $value = $this->getInputValue();

      $input =  HTML::radioField($this->key, 'True', $value, 'id="' . $this->key . '1" autocomplete="off"') . $this->app->getDef('cfg_recaptcha_antispam_tell_a_friend_true') . ' ';
      $input .=  HTML::radioField($this->key, 'False', $value, 'id="' . $this->key . '2" autocomplete="off"') . $this->app->getDef('cfg_recaptcha_antispam_tell_a_friend_false');

      return $input;
    }
  }