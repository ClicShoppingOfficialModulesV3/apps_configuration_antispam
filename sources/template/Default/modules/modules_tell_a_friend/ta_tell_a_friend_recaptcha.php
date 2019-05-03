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

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class ta_tell_a_friend_recaptcha {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('modules_tell_a_friend_recaptcha_title');
      $this->description = CLICSHOPPING::getDef('modules_tell_a_friend_recaptcha_description');

      if ( defined('MODULES_TELL_A_FRIEND_RECAPTCHA_STATUS') ) {
        $this->sort_order = (int)MODULES_TELL_A_FRIEND_RECAPTCHA_SORT_ORDER;
        $this->enabled = (MODULES_TELL_A_FRIEND_RECAPTCHA_STATUS == 'True');
      }

      if (!defined('CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS') || CLICSHOPPING_APP_ANTISPAM_RE_RECAPTCHA_STATUS == 'False' || CLICSHOPPING_APP_ANTISPAM_TELL_A_FRIEND == 'False') {
         $this->enabled = false;
      }
    }

    public function execute() {
      $CLICSHOPPING_Template = Registry::get('Template');
      $CLICSHOPPING_Hooks = Registry::get('Hooks');

      if (isset($_GET['Account'] ) && isset($_GET['TellAFriend']) ) {
       $content_width = (int)MODULES_TELL_A_FRIEND_RECAPTCHA_CONTENT_WIDTH;

        $tell_a_friend_recaptcha = '<!--  tell_a_friend_recaptcha start -->' . "\n";

        $recaptcha = $CLICSHOPPING_Hooks->output('AllShop', 'GoogleRecaptchaDisplay', null, 'display');

        ob_start();
        require_once($CLICSHOPPING_Template->getTemplateModules($this->group . '/content/tell_a_friend_recaptcha'));

        $tell_a_friend_recaptcha .= ob_get_clean();

        $tell_a_friend_recaptcha .= '<!-- tell_a_friend_recaptcha end -->' . "\n";

        $CLICSHOPPING_Template->addBlock($tell_a_friend_recaptcha, $this->group);
      }

    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULES_TELL_A_FRIEND_RECAPTCHA_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want activate this module ?',
          'configuration_key' => 'MODULES_TELL_A_FRIEND_RECAPTCHA_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want activate this module in your shop ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please select the width of the module',
          'configuration_key' => 'MODULES_TELL_A_FRIEND_RECAPTCHA_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Select a number between 1 and 12',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULES_TELL_A_FRIEND_RECAPTCHA_SORT_ORDER',
          'configuration_value' => '350',
          'configuration_description' => 'Sort order of display. Lowest is displayed first',
          'configuration_group_id' => '6',
          'sort_order' => '10',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array('MODULES_TELL_A_FRIEND_RECAPTCHA_STATUS',
                 'MODULES_TELL_A_FRIEND_RECAPTCHA_CONTENT_WIDTH',
                 'MODULES_TELL_A_FRIEND_RECAPTCHA_SORT_ORDER'
                );
    }
  }
