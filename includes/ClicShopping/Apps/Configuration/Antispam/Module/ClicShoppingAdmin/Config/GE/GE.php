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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\GE;

  class GE extends \ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\ConfigAbstract {
    public $is_installed = true;
    public $is_uninstallable = true;
    public $sort_order = 100000;

    protected function init() {
      $this->title = $this->app->getDef('module_ge_title');
      $this->short_title = $this->app->getDef('module_ge_short_title');
    }

    public function install() {
      return false;
    }

    public function uninstall()  {
      return false;
    }
  }