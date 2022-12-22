<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT

   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Apps\Configuration\Antispam;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class Antispam extends \ClicShopping\OM\AppAbstract
  {

    protected $api_version = 1;
    protected string $identifier = 'ClicShopping_Antispam_V1';

    protected function init()
    {
    }

    /**
     * @return array|mixed
     */
    public function getConfigModules(): mixed
    {
      static $result;

      if (!isset($result)) {
        $result = [];

        $directory = CLICSHOPPING::BASE_DIR . 'Apps/Configuration/Antispam/Module/ClicShoppingAdmin/Config';
        $name_space_config = 'ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config';
        $trigger_message = 'ClicShopping\Apps\Configuration\Antispam\Antispam::getConfigModules(): ';

        $this->getConfigApps($result, $directory, $name_space_config, $trigger_message);
      }

      return $result;
    }

    public function getConfigModuleInfo($module, $info)
    {
      if (!Registry::exists('AntispamAdminConfig' . $module)) {
        $class = 'ClicShopping\Apps\Configuration\Antispam\Module\ClicShoppingAdmin\Config\\' . $module . '\\' . $module;

        Registry::set('AntispamAdminConfig' . $module, new $class);
      }

      return Registry::get('AntispamAdminConfig' . $module)->$info;
    }


    public function getApiVersion()
    {
      return $this->api_version;
    }

     /**
     * @return string
     */
    public function getIdentifier() :String
    {
      return $this->identifier;
    }
  }
