<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT

   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Apps\Configuration\Antispam\Classes;

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;

  class AntispamClass
  {
    /**
     * Function to simple antispam : Display a mandatory number
     * public function
     * @return string $antispam sentence
     */
    public static function getConfirmationSimpleAntiSpam(): string
    {
      $random_number = rand(1, 20);

      $number = $random_number + 3;
      $antispam = ' (' . $random_number . ' + ' . CLICSHOPPING::getDef('text_antispam') . ') x 1';

      $_SESSION['createResponseAntiSpam'] = md5($number);

      return $antispam;
    }

    /**
     * @param string $antispan_confirmation
     * @return bool
     */
    private static function getValidateSimpleAntispam($antispan_confirmation): bool
    {
      if (isset($_SESSION['createResponseAntiSpam'])) {
        if ($antispan_confirmation === $_SESSION['createResponseAntiSpam']) {
          $valid_antispan_confirmation = false;
        } else {
          $valid_antispan_confirmation = true;
        }
      } else {
        $valid_antispan_confirmation = true;
      }

      unset($_SESSION['createResponseAntiSpam']);

      return $valid_antispan_confirmation;
    }

    /**
     *
     * @return bool
     */
    public static function getResultSimpleAntispam(): bool
    {
      $error = false;
      if (isset($_POST['antispam'])) {
        $antispam = HTML::sanitize($_POST['antispam']);
        $result = md5($antispam);

        if (self::getValidateSimpleAntispam($result) === true) {
          $error = true;
        }
      } else {
        $error = true;
      }

      return $error;
    }
  }