<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT

 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\Apps\Configuration\Antispam\Classes\AntispamClass;

  class ac_account_customers_newsletters_no_account_simple_antispam {
    public string $code;
    public $group;
    public $title;
    public $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('modules_account_customers_newsletter_no_account_simple_antispam_title');
      $this->description = CLICSHOPPING::getDef('modules_account_customers_newsletter_no_account_simple_antispam_description');

      if (\defined('MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_STATUS')) {
        $this->sort_order = (int)MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_SORT_ORDER;
        $this->enabled = (MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_STATUS == 'True');
      }

      if (!\defined('CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS') || CLICSHOPPING_APP_ANTISPAM_AM_SIMPLE_STATUS == 'False' || CLICSHOPPING_APP_ANTISPAM_NEWSLETTER_NO_ACCOUNT == 'False') {
         $this->enabled = false;
      }
    }

    public function execute() {
      $CLICSHOPPING_Template = Registry::get('Template');

      if (isset($_GET['Account']) &&  isset($_GET['NewslettersNoAccount'])) {
        $content_width = (int)MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_CONTENT_WIDTH;

        $antispam = AntispamClass::getConfirmationSimpleAntiSpam();
        $account_customers_newsletter_no_account_antispam = '<!--  account_customers_newsletter_no_account_antispam start -->' . "\n";

        ob_start();
        require_once($CLICSHOPPING_Template->getTemplateModules($this->group . '/content/account_customers_newsletter_no_account_simple_antispam'));

        $account_customers_newsletter_no_account_antispam .= ob_get_clean();

        $account_customers_newsletter_no_account_antispam .= '<!-- account_customers_newsletter_no_account_antispam end -->' . "\n";

        $CLICSHOPPING_Template->addBlock($account_customers_newsletter_no_account_antispam, $this->group);
      }

    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return \defined('MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to enable this module ?',
          'configuration_key' => 'MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to enable this module in your shop ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please select the width of the module',
          'configuration_key' => 'MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_CONTENT_WIDTH',
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
          'configuration_key' => 'MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_SORT_ORDER',
          'configuration_value' => '370',
          'configuration_description' => 'Sort order of display. Lowest is displayed first. The sort order must be different on every module',
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
      return ['MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_STATUS',
              'MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_CONTENT_WIDTH',
              'MODULES_ACCOUNT_CUSTOMERS_NEWSLETTER_NO_ACCOUNT_SIMPLE_ANTISPAM_SORT_ORDER'
             ];
    }
  }
