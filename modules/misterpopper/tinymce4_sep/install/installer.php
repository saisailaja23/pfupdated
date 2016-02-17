<?php

/**
* Copyright (c) 2012-2013 Andreas Pachler - http://www.paan-systems.com
* This is a commercial product made by Andreas Pachler and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from Andreas Pachler.
* This notice may not be removed from the source code.
*/

bx_import('BxDolInstaller');

class MPTinyMCE4Installer extends BxDolInstaller
{

    function MPTinyMCE4Installer($aConfig)
    {
        parent::BxDolInstaller($aConfig);
    }

    function install($aParams)
    {
        return parent::install($aParams);
    }

    function uninstall($aParams)
    {
        $aResult = parent::uninstall($aParams);
        return $aResult;
    }

}
