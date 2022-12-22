<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT

   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */


  namespace ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\GE\Params;

  use ClicShopping\OM\HTML;

  class invisible extends \ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\ConfigParamAbstract
  {
    public $default = 'False';
    public ?int $sort_order = 10;

    protected function init()
    {
      $this->title = $this->app->getDef('cfg_recaptcha_antispam_invisible_title');
      $this->description = $this->app->getDef('cfg_recaptcha_antispam_invisible_description');
    }

    public function getInputField()
    {
      $value = $this->getInputValue();

      $input = HTML::radioField($this->key, 'True', $value, 'id="' . $this->key . '1" autocomplete="off"') . $this->app->getDef('cfg_recaptcha_antispam_invisible_true') . ' ';
      $input .= HTML::radioField($this->key, 'False', $value, 'id="' . $this->key . '2" autocomplete="off"') . $this->app->getDef('cfg_recaptcha_antispam_invisible_false');

      return $input;
    }
  }