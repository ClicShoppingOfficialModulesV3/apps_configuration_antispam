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

  namespace ClicShopping\Apps\Configuration\Antispam\Module\HeaderTags;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Configuration\Antispam\Antispam as AntispamApp;

  class Recaptcha extends \ClicShopping\OM\Modules\HeaderTagsAbstract
  {
    protected $lang;
    protected $app;
    protected $group;

    protected function init()
    {
      if (!Registry::exists('Antispam')) {
        Registry::set('Antispam', new AntispamApp());
      }

      $this->app = Registry::get('Antispam');
      $this->group = 'header_tags'; // could be header_tags or footer_scripts

      $this->app->loadDefinitions('Module/Hooks/HeaderTags/recaptcha');

      $this->title = $this->app->getDef('module_header_tags_recaptcha_title');
      $this->description = $this->app->getDef('module_header_tags_recaptcha_description');

      if (defined('MODULES_HEADER_TAGS_GOOGLE_RECAPTCHA_STATUS')) {
        $this->sort_order = (int)MODULES_HEADER_TAGS_GOOGLE_RECAPTCHA_SORT_ORDER;
        $this->enabled = (MODULES_HEADER_TAGS_GOOGLE_RECAPTCHA_STATUS == 'True');
      }
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function getOutput()
    {
      $CLICSHOPPING_Template = Registry::get('Template');

      $footer = '<!-- start Recaptha -->' . "\n";
      $footer .= '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
      $footer .= '<!-- End Recaptha -->' . "\n";

      $content = $CLICSHOPPING_Template->addBlock($footer, 'footer_scripts');

      $output =
        <<<EOD
{$content}
EOD;

      return $output;
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez-vous activer ce module ?',
          'configuration_key' => 'MODULES_HEADER_TAGS_GOOGLE_RECAPTCHA_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Souhaitez vous activer ce module à votre boutique ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Ordre de tri d\'affichage',
          'configuration_key' => 'MODULES_HEADER_TAGS_GOOGLE_RECAPTCHA_SORT_ORDER',
          'configuration_value' => '100',
          'configuration_description' => 'Ordre de tri pour l\'affichage (Le plus petit nombre est montré en premier)',
          'configuration_group_id' => '6',
          'sort_order' => '4',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return ['MODULES_HEADER_TAGS_GOOGLE_RECAPTCHA_STATUS',
        'MODULES_HEADER_TAGS_GOOGLE_RECAPTCHA_SORT_ORDER'
      ];
    }
  }
