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
  use ClicShopping\OM\HTML;

  class ta_tell_a_friend_simple_invisible_antispam {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('modules_tell_a_friend_simple_invisible_antispam_title');
      $this->description = CLICSHOPPING::getDef('modules_tell_a_friend_simple_invisible_antispam_description');

      if ( defined('MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_STATUS') ) {
        $this->sort_order = (int)MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_SORT_ORDER;
        $this->enabled = (MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_STATUS == 'True');
      }

      if ((!defined('CLICSHOPPING_APP_ANTISPAM_INVISIBLE') || CLICSHOPPING_APP_ANTISPAM_INVISIBLE == 'False') && (!defined('CLICSHOPPING_APP_ANTISPAM_TELL_A_FRIEND') || CLICSHOPPING_APP_ANTISPAM_TELL_A_FRIEND == 'False')) {

        $this->enabled = false;
      }
    }

    public function execute() {
      $CLICSHOPPING_Template = Registry::get('Template');

      if (isset($_GET['Account'] ) && isset($_GET['TellAFriend']) ) {
        $tell_a_friend_invisible_antispam = '<!--  tell_a_friend_invisible_invisible_antispam start -->' . "\n";
        $tell_a_friend_invisible_antispam .= HTML::inputField('invisible_recaptcha', '', 'class="hiddenRecaptcha"');
        $tell_a_friend_invisible_antispam .= HTML::inputField('invisible_clicshopping', '', 'class="hiddenRecaptcha"');
        $tell_a_friend_invisible_antispam .= '<!-- tell_a_friend_invisible_invisible_antispam end -->' . "\n";

        $CLICSHOPPING_Template->addBlock($tell_a_friend_invisible_antispam, $this->group);
      }
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want activate this module ?',
          'configuration_key' => 'MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want activate this module in your shop ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_SORT_ORDER',
          'configuration_value' => '360',
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
      return ['MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_STATUS',
              'MODULES_TELL_A_FRIEND_SIMPLE_INVISIBLE_ANTISPAM_SORT_ORDER'
             ];
    }
  }