<?php
/**
 * This file contains endereco installer.
 *
 * PHP Version 7
 *
 * @package   Endereco\OxidClient\Core
 * @author    Ilja Weber <ilja.weber@mobilemojo.de>
 * @copyright 2019 mobilemojo – Apps & eCommerce UG (haftungsbeschränkt) & Co. KG
 *            (https://www.mobilemojo.de/)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License,
 *            version 3 (GPLv3)
 * @link      https://www.endereco.de/
 */

namespace Endereco\OxidClient\Core;

 /**
  * Installer
  *
  * Class that takes care of installation and deinstallation procedure of
  * endereco client module
  *
  * PHP Version 7
  *
  * @package   Endereco\OxidClient\Core
  * @author    Ilja Weber <ilja.weber@mobilemojo.de>
  * @copyright 2019 mobilemojo – Apps & eCommerce UG (haftungsbeschränkt) & Co. KG
  *            (https://www.mobilemojo.de/)
  * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License,
  *            version 3 (GPLv3)
  * @link      https://www.endereco.de/
  */
class Installer
{
    /**
     *  A procedure to execute once the module is activated
     */
    public static function onActivate()
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sOxId = $oConfig->getShopId();

        // Set default values to settings
        $oConfig->saveShopConfVar('str', 'sCONNSTATUS', '0', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sAPIKEY', '', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sSERVICEURL', 'https://endereco-service.de/rpc/v1', $sOxId, 'module:enderecoclientox');

        $oConfig->saveShopConfVar('str', 'bSTATUSINDICATOR', '1', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'bPOSTCODEAUTOCOMPLETE', '1', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'bCITYNAMEAUTOCOMPLETE', '1', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'bSTREETAUTOCOMPLETE', '1', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'bEMAILCHECK', '1', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'bNAMECHECK', '1', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'bPREPHONECHECK', '1', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sPHONEFORMAT', '8', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'bADDRESSCHECK', '1', $sOxId, 'module:enderecoclientox');

        $oConfig->saveShopConfVar('str', 'sPRIMARYCOLOR', '#009EC0', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sPRIMARYCOLORHOVER', '#0089a7', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sPRIMARYCOLORTEXT', '#ffffff', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sSECONDARYCOLOR', '#FC6621', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sSECONDARYCOLORHOVER', '#FC6621', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sSECONDARYCOLORTEXT', '#ffffff', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sWARNINGCOLOR', '#f0ad4e', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sWARNINGCOLORHOVER', '#eea236', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sWARNINGCOLORTEXT', '#ffffff', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sSUCCESSCOLOR', '#5cb85c', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sSUCCESSCOLORHOVER', '#4cae4c', $sOxId, 'module:enderecoclientox');
        $oConfig->saveShopConfVar('str', 'sSUCCESSCOLORTEXT', '#ffffff', $sOxId, 'module:enderecoclientox');
    }

    /**
     * Deactivation routine.
     */
    public static function onDeactivate()
    {
        self::cleanTmp();
    }

    /**
     * Clean temp folder content.
     *
     * @param string $sClearFolderPath Sub-folder path to delete from. Should be a full, valid path inside temp folder.
     *
     * @return boolean
     */
    public static function cleanTmp($sClearFolderPath = '')
    {
        $sTempFolderPath = realpath(\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sCompileDir'));

        if (!empty($sClearFolderPath) &&
            ( strpos($sClearFolderPath, $sTempFolderPath) !== false ) &&
            is_dir($sClearFolderPath)
        ) {
            $sFolderPath = $sClearFolderPath;
        } elseif (empty($sClearFolderPath)) {
            $sFolderPath = $sTempFolderPath;
        } else {
            return false;
        }

        $hDir = opendir($sFolderPath);

        if (!empty($hDir)) {
            while (false !== ($sFileName = readdir($hDir))) {
                $sFilePath = $sFolderPath . '/' . $sFileName;

                if (!in_array($sFileName, array('.', '..', '.htaccess')) &&
                    is_file($sFilePath)
                ) {
                    @unlink($sFilePath);
                } elseif (('smarty' === $sFileName) && is_dir($sFilePath)) {
                    self::cleanTmp($sFilePath);
                }
            }
        }

        return true;
    }
}
