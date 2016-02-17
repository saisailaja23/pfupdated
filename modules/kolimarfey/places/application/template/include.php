    
    <?php if (!$aPlaces): ?>
        
        <?php echo $this->t('Places No Places'); ?>

    <?php else: ?>

        <div id="container_groups">
        
        <?php foreach ($aPlaces as $aPlace): ?> 
            <div>   
                <div class="icon_block" style="width:66px; height:66px;">
                    <div class="thumbnail_block" style="float:left;">
                        <a href="<?php $html->href('view/'.$aPlace['pl_uri']); ?>"><img class="icons" src="<?= $GLOBALS['site']['url'] . K_APP_PATH . 'template/img/spacer.gif '; ?>" style="width: 64px; height: 64px; background-image: url(<?php $html->thumb_href($aPlace['pl_thumb']); ?>);" alt=""/></a>
                    </div>
    	        </div>
	            <div class="blog_wrapper_n" style="width:75%">
                    <div class="blog_subject_n"><?php $html->url($aPlace['pl_name'], 'view/'.$aPlace['pl_uri'], 0); ?></div>
                    <div class="blogInfo">        
                        <?php if ($isDisplayAuthor): ?>
                        <span><?= $this->t('Places Author'); ?>: <?php $this->helperhtml->url($aPlace['NickName'], getProfileLink($aPlace['pl_author_id']), false); ?></span>
                        <?php endif; ?>
                        <span><img src="<?= "{$GLOBALS['site']['url']}templates/tmpl_{$GLOBALS['tmpl']}/images/icons/clock.gif";  ?>" alt="" /><?= $aPlace['pl_created_f']; ?></span>
                        <span><img src="<?= "{$GLOBALS['site']['url']}templates/tmpl_{$GLOBALS['tmpl']}/images/icons/folder_small.png";  ?>" /><?= $this->t($aPlace['pl_cat_name']); ?></span>
                    </div>                
                    <div class="blog_text">
                        <?= $aPlace['pl_city'] ?>, <?= $aPlace['pl_country_name'] ?>
                    </div>
                </div>
            </div>
            <div class="clear_both"></div>
        <?php endforeach; ?>
            
        </div>

    <div style="text-align:center;"><a href="<?= $sLinkAll ?>"><?= $this->t('View All'); ?></a></div>

    <?php endif; ?>
    
