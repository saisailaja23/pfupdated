<?php
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by AndrewP and cannot be modified for other than personal usage. 
* This product cannot be redistributed for free or a fee without written permission from AndrewP. 
* This notice may not be removed from the source code.
*
***************************************************************************/
bx_import('BxDolModule');

class ALCaptchaModule extends BxDolModule {

    // variables
    var $sModuleUrl;

    /**
    * Constructor
    */
    function ALCaptchaModule($aModule) {
        parent::BxDolModule($aModule);

        $this->sModuleUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();
    }

    function actionAdministration($sAction = '') {
        $sCode = $this->_oTemplate->displayAccessDenied();
        if (! isAdmin()) {
            $this->_oTemplate->pageCode($this->aPageTmpl, array('page_main_code' => $sCode));
            return;
        }

        $sActions = array(
            'tab1' => array('href' => $this->sModuleUrl . 'administration/add/', 'title' => _t('_add'), 'onclick' => '', 'active' => ($sAction == 'add')),
        );

        $i = (int)bx_get('id');
        if ($i) {
            if ($_POST['do'] == 'del') {
                $this->_oDb->deleteQuestion($i);
                echo '1';
                exit;
            }
            $sCode = DesignBoxAdmin(_t('_alc_admin'), $this->getWrap($this->getAddEditForm($i)), $sActions);
        } else {
            $sForm = '';
            if ($sAction == 'add') {
                $sForm = DesignBoxContent(_t('_add'), $this->getAddEditForm(), 1);
            }
            $sCode = DesignBoxAdmin(_t('_alc_admin'), $this->getWrap($this->getQuestions() . $sForm), $sActions);
        }

        $this->aPageTmpl['name_index'] = 9;
        $this->aPageTmpl['header'] = _t('_alc_admin');
        $this->_oTemplate->pageCode($this->aPageTmpl, array('page_main_code' => $sCode), array(), array(), true);
    }

    function getWrap($sCode) {
        return '<div class="bx-def-bc-padding">' . $sCode . '<div class="clear_both"></div></div>';
    }

    function getAddEditForm($i = 0) {
        $sButCap = _t('_add');
        $aInfo = array();
        $sSubAct = '';
        if ($i) {
            $sButCap = _t('_Edit');
            $aInfo = $this->_oDb->getQuestionInfo($i);
        } else {
            $sSubAct = 'add/';
        }

        //adding form
        $aForm = array(
            'form_attrs' => array(
                'name' => 'create_categories_form',
                'action' => $this->sModuleUrl . 'administration/' . $sSubAct,
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'alcaptcha_questions',
                    'key' => 'id',
                    'submit_name' => 'add_button',
                ),
            ),
            'inputs' => array(
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => $i,
                ),
                'question' => array(
                    'type' => 'text',
                    'name' => 'question',
                    'caption' => _t('_alc_q'),
                    'required' => true,
                    'value' => $aInfo['question'],
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(2,255),
                        'error' => _t('_title_min_lenght', 2),
                    ),
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                ),
                'answer' => array(
                    'type' => 'text',
                    'name' => 'answer',
                    'caption' => _t('_alc_a'),
                    'required' => true,
                    'value' => $aInfo['answer'],
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(1,255),
                        'error' => _t('_title_min_lenght', 1),
                    ),
                    'db' => array (
                        'pass' => 'Xss', 
                    ),
                ),
                'add_button' => array(
                    'type' => 'input_set',
                    'colspan' => 'true',
                    0 => array (
                        'type' => 'submit',
                        'name' => 'add_button',
                        'value' => $sButCap,
                    ),
                    1 => array (
                        'type' => 'button',
                        'value' => _t('_Cancel'),
                        'attrs' => array('onclick' => "window.location.href = '{$this->sModuleUrl}administration/'"),
                    ),
                )

            ),
        );

        $sCode = '';
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();
        if ($oForm->isSubmittedAndValid()) {
            $aValsAdd = array();

            $iLastId = -1;
            if ($i>0) {
                $oForm->update($i, $aValsAdd);
                $iLastId = $i;
            } else {
                $iLastId = $oForm->insert($aValsAdd);
            }

            if ($iLastId > 0) {
                $sCode = MsgBox(_t('_Success'), 1);
            } else {
                $sCode = MsgBox(_t('_Failed to apply changes'), 1);
            }
        }
        return $sCode . $oForm->getCode();
    }

    function getQuestions() {
        $sTableRes = '';
        $aRecords = $this->_oDb->getQuestions();
        foreach ($aRecords as $i => $aVal) {
            $sTableRes .= "<tr id='alc{$aVal['id']}'><td class='bx-def-border'>{$aVal['id']}</td><td class='bx-def-border'>{$aVal['question']}</td><td class='bx-def-border'>{$aVal['answer']}</td><td class='bx-def-border'><a href=\"{$this->sModuleUrl}administration/&id={$aVal['id']}\">Edit</a> | <a href=\"javascript: void(0)\" onclick=\"alcDel('{$aVal['id']}')\">Delete</a></td></tr>";
        }

        $this->_oTemplate->addJsTranslation(array(
            '_Are you sure?'
        ));

        $sQc = _t('_alc_q');
        $sAc = _t('_alc_a');
        $sAct = _t('_Action');

        return <<<EOF
<script>
function alcDel(i) {
    if (confirm(_t('_Are you sure?'))) {
        $.post('{$this->sModuleUrl}administration/', {do:'del', id:i}, function(data) {
            $('#alc'+i).remove();
        });
    }
}
</script>
<table style="width:99%; border-collapse:collapse;" cellpadding="4">
    <tr>
        <td class="bx-def-border">ID</td>
        <td class="bx-def-border">{$sQc}</td>
        <td class="bx-def-border">{$sAc}</td>
        <td class="bx-def-border">{$sAct}</td>
    </tr>
    {$sTableRes}
</table>
EOF;
    }
}