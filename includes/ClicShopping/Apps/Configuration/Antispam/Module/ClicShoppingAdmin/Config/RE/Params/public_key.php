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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\RE\Params;

  class public_key extends \ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\ConfigParamAbstract {

    public $default = '';
    public $sort_order = 40;

    protected function init() {
        $this->title = $this->app->getDef('cfg_recaptcha_antispam_recaptcha_public_key_title');
        $this->description = $this->app->getDef('cfg_recaptcha_antispam_recaptcha_public_key_description');
    }
  }
