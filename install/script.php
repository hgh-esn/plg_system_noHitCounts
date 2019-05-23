<?php
/**
 * The name of this class is dependent on the component being installed.
 * The class name should have the component's name, directly followed by
 * the text InstallerScript (ex:. com_helloWorldInstallerScript).
 *
 * This class will be called by Joomla!'s installer, if specified in your component's
 * manifest file, and is used for custom automation actions in its installation process.
 *
 * In order to use this automation script, you should reference it in your component's
 * manifest file as follows:
 * <scriptfile>script.php</scriptfile>
 *
 * @package     Joomla.Administrator
 * @subpackage  plg_stophitcounts
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class plgsystemstophitcountsInstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent)
    {
//      $parent->getParent()->setRedirectURL('index.php?option=stophitcounts');
//      echo '<br />' .JText::_('stophitcounts_INSTALL_TEXT');
		echo '<br />' .JText::_('PLG_SYSTEM_SHC_INST_SEE_NOTES');

    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent)
    {
//		echo '<br />' .'Uninstall - nothing to do';
		echo '<br />' .JText::_('PLG_SYSTEM_SHC_UNINST_NOTHING_TO_DO');
    }

    /**
     * This method is called after a component is updated.
     *
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent)
    {
//      echo '<br />' .JText::_('stophitcounts_UPDATE_' . $type . ' see notes!');
		echo '<br />' .JText::_('PLG_SYSTEM_SHC_UPD_SEE_NOTES');

		// aktuelles Verzeichnis
//      echo '<br />' .'akt. Verzeichnis= ' .getcwd() . "\n";

		$dsn = '../plugins/system/stophitcounts/stophitcounts.xml';

		if (file_exists($dsn))
		{
			$xml = simplexml_load_file($dsn);
// 			print "<pre>";
//       	print_r($xml);
// 			print "</pre>";

//			echo '<br />' .'Note: Die neue/aktuelle Version des Plugins ist jetzt: <b>' .$xml->version .'</b>';
			echo '<br />' .JText::_('PLG_SYSTEM_SHC_UPD_NEW_VERSION_IS')  .'<b>' .$xml->version .'</b>';

		}
		else
		{
			exit('Konnte ' .$dsn .' nicht öffnen.');
//      	echo '<br />' .'Konnte ' .$dsn .' nicht öffnen.';
			echo '<br />' .JText::_('PLG_SYSTEM_SHC_UPD_DSN_NOT_TO_OPEN');

		}
	}

    /**
     * Runs just before any installation action is performed.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
//		echo '<br />' .JText::_('stophitcounts_PREFLIGHT_' . $type . ' We do some updates');
		echo '<br />' .JText::_('PLG_SYSTEM_SHC_DB_UPD_PARAMS');

		// Start prefight

		/*********************************************************************
		 * preflight actions
		 * in V1.2.2 params items have been changed there names.
		 * disable_users  -> disabled_users
		 * disable_groups -> disabled_groups
		 * this changes have to be done also in the DB to already existing entries
		 *********************************************************************/
		$db =& JFactory::getDBO();

//		$query = $db->getQuery(true);
 		$query = 'SELECT params FROM #__extensions WHERE name LIKE "%stophitcount%"';

	//  qn = 'quotename'
	//	$query->select($query->qn('params'))
	//		  ->from  ($query->qn('#__extensions'))
	//		  ->where ($query->qn('name') . 'LIKE "%stophitcount%"');

		$db->setQuery($query);

 		$shc_parms_readFromDB = $db->loadResult();
//
// depreciated in J3x +
//
//		if ( $db->getErrorNum() )
//		{
// 			echo  '<br />' 	.'db-query: db-error - return';
// 			echo  '<br />' 	.$db->getErrorNum();
//			return;
//		}
//
		if ( empty($shc_parms_readFromDB ) )
		{
			// we are on an initial installation
			echo  '<br />' .'no params-entry exists - nothing further to do ... return';
//			echo  '<br />' .'$shc_parms_readFromDB= ' .$shc_parms_readFromDB;
			return;
		}
		else
		{
			echo  '<br />' 	.'params-entry exist - do further operations';
//			echo  '<br />' .'$shc_parms_readFromDB= ' .$shc_parms_readFromDB;
// ========================================
 			$query = 'SELECT extension_id FROM #__extensions WHERE name LIKE "%stophitcount%"';

	//		$query->select($query->qn('extension_id'))
	//			  ->from  ($query->qn('#__extensions'))
	//			  ->where ($query->qn('name') . 'LIKE "%stophitcount%"');

			$db->setQuery($query);

			$shc_exid = $db->loadResult();

			echo '<br /> readFromDb= ' .$shc_parms_readFromDB;
			echo '<br /> exid= ' .$shc_exid;
//
// depreciated in J3x +
//
//			if ( $db->getErrorNum() )
//				{
//					$msg = $db->getErrorMsg();
//					echo  '<br />' 		   .'no entry found - return';
//					echo  '<br />'.JText::_('PLG_SYSTEM_SHC_DB_UPD_ERR');
//	 				echo  '<br />' .$msg;
//					{
//						JLog::add($msg);
//					}
//					return false;
//				}
//
			// do rename parms
			//
			// change the disabled_users & disabled_groups params : since V1.2.5
			//
				$shc_parms = str_ireplace('disable_users','disabled_users',$shc_parms_readFromDB,$cnt);
				if ($cnt > 0 )
				{
//					echo '<br /> count rename - disable_/disabled_users : change-counts  =' .$cnt;
					echo '<br />' .JText::_('PLG_SYSTEM_SHC_DB_UPD_PARAMS_DISABLE_USERS_CHG');
				}
				$shc_parms = str_ireplace('disable_groups','disabled_groups',$shc_parms,$cnt);
				if ($cnt > 0 )
				{
//					echo '<br /> count rename - disable_/disabled_groups: change-counts =' .$cnt;
					echo '<br />' .JText::_('PLG_SYSTEM_SHC_DB_UPD_PARAMS_DISABLE_GROUPS_CHG');
				}
			//
			// change the default pov-params : since V1.2.9
			//
				$shc_parms = str_ireplace('"qookie_pov":"3600"','"qookie_pov":"86400"',$shc_parms,$cnt);
				if ($cnt > 0 )
				{
//					echo '<br /> qookie_pov: changed DEFAULT from "1h-3600" to "24h-86400"';
					echo '<br />' .JText::_('PLG_SYSTEM_SHC_DB_UPD_PARAMS_POV_DEFAULT_CHG');
				}

//			echo '<br />' .$shc_parms;

			if ( $shc_parms === $shc_parms_readFromDB)
			{
//	 			echo '<br />' 		   .'PLG_SYSTEM_SHC_DB_UPD_PARAMS_NO';
				echo '<br />' .JText::_('PLG_SYSTEM_SHC_DB_UPD_PARAMS_NO');
				return;
			}
			else
			{
//	 			echo '<br />'          .'PLG_SYSTEM_SHC_DB_UPD_PARAMS_YES';
				echo '<br />' .JText::_('PLG_SYSTEM_SHC_DB_UPD_PARAMS_YES');

//				$db =& JFactory::getDBO();

				$query = 'UPDATE #__extensions SET params=' .$shc_parms .' WHERE extension_id=' .$shc_exid;

//				$query->update($query->qn('#__extensions'))
//					  ->set   ($query->qn('params=' .$shc_parms))
//					  ->where ($query->qn('extension_id=') .$shc_exid);

				$db->setQuery($query);

				$res = $db->execute();
/*
				$query = $db->getQuery(true);

				$fields = array( $db->quoteName('shc_parms')	);
				$conditions = array( $db->quoteName('extension_id') . ' = ' . $db->quote('$shc_exid') );

				$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);

				$db->setQuery($query);
				$res = $db->execute();
*/
//				if ( $db->getErrorNum() )
//				{
//					echo  '<br />'.JText::_('PLG_SYSTEM_SHC_DB_UPD_ERR');
//					echo  '<br />' .$msg;
//					JLog::add($msg);
//					return false;
//				}
//				else
//				{
// 					echo '<br />' 		   .'PLG_SYSTEM_SHC_DB_UPD_OK';
					echo '<br />' .JText::_('PLG_SYSTEM_SHC_DB_UPD_OK');
//				}
			}
// ========================================
		}
		// End prefight test

	}

    /**
     * Runs right after any installation action is preformed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
	function postflight($type, $parent)
	{
		echo '<br />' .JText::_('stophitcounts_POSTFLIGHT_' . $type . ' We do some clean-ups');
		echo '<br />' .JText::_('PLG_SYSTEM_SHC_POSTFLIGHT_CLEANUP');
	// 	echo '' 		 .JText::sprintf('The new version is now: ', $parent->get('manifest')->version);

		$pfad='../media/2delete'; // for testing

		if(is_dir($pfad) == true)
		{
			function rrmdir($dir)
			{
				if (is_dir($dir))
				{
					$objects = scandir($dir);
					foreach ($objects as $object)
					{
						if ($object != "." && $object != "..")
						{
							if (filetype($dir."/".$object) == "dir")
							{
								rrmdir($dir."/".$object);
							}
							else
							{
								unlink($dir."/".$object);
							}
						}
					}
					reset($objects);
					rmdir($dir);
				}
			}
			rrmdir($pfad);
	//      echo 'Pfad: ' .$pfad .' gelöscht.';
			echo '<br />' .'clean-up done for ' .$pfad;
			echo '<br />' .JText::_('PLG_SYSTEM_SHC_POSTFLIGHT_CLEANUP_DONE');

		}
		  else
		{
	//      echo 'Pfad: ' .$pfad .' nicht gefunden';
			echo '<br />' .JText::_('PLG_SYSTEM_SHC_POSTFLIGHT_CLEANUP_PATH_NOT_EXISTS');
			echo '<br />' .'nothing to do!';
			echo '<br />' .JText::_('PLG_SYSTEM_SHC_POSTFLIGHT_CLEANUP_NOTHING_TO_DO');
		}
	}
} // end-class
