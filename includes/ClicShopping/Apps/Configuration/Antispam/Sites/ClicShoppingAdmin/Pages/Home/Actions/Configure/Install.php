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

  namespace ClicShopping\Apps\Configuration\Antispam\Sites\ClicShoppingAdmin\Pages\Home\Actions\Configure;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\Cache;

  class Install extends \ClicShopping\OM\PagesActionsAbstract {

    public function execute() {

      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
      $CLICSHOPPING_Antispam = Registry::get('Antispam');

      $current_module = $this->page->data['current_module'];

      $CLICSHOPPING_Antispam->loadDefinitions('Sites/ClicShoppingAdmin/install');

      $m = Registry::get('AntispamAdminConfig' . $current_module);
      $m->install();

      $CLICSHOPPING_MessageStack->add($CLICSHOPPING_Antispam->getDef('alert_module_install_success'), 'success', 'Antispam');

      $CLICSHOPPING_Antispam->redirect('Configure&module=' . $current_module);
    }
  }
