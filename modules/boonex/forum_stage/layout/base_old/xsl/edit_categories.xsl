<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" xmlns:exsl="http://exslt.org/common" extension-element-prefixes="exsl">

<xsl:include href="box.xsl" />
<xsl:include href="replace.xsl" />

<xsl:template match="urls" />
<xsl:template match="logininfo" />

<xsl:template match="page">

    <xsl:if test="invalid_license">
        
        <div id="warn" style="background-color:#CC3333; color:#FFFFFF; padding:25px; font-size:12px;" class="forum_default_margin_bottom">

            <h2 style="font-size:25px;">This Copy Of Orca Is Not Registered</h2>

            <br/>

            Please, go to your <a style="color:#FFFFFF;" href="http://www.boonex.com/unity/">Unity Account</a> to generate a free license. At Unity 
            you may track your licenses, promote your site and download new 
            software - all for free.
            <br/>
            
            <div style="font-size:130%; font-weight:bold;">

                <br/><br/>
                <a style="color:#FFFFFF;" href="http://www.boonex.com/unity/">Go To Unity</a> To Generate Free License
                <br/><br/>

                <a style="color:#FFFFFF;" href="https://www.boonex.com/payment.php?product=Orca">Buy Link-Free License</a> For One Year
                <br/><br/>

                <a style="color:#FFFFFF;" href="javascript:void(0);" onclick="document.getElementById('warn').style.display = 'none';">Continue</a> Using Unregistered Orca
                <br /><br />

                <iframe width="1" height="1" border="0" name="register_orca" style="border:none;">&#160;</iframe>
                <form method="post" action="" target="register_orca">
                    Input License:
                    <input type="hidden" name="action" value="register_orca" />
                    <input type="text" size="10" name="license_code" />
                    <input type="submit" value="Register" />
                </form>

            </div>


        </div>

    </xsl:if>

    <xsl:variable name="menu_links">
        <!-- <btn href="{/root/urls/base}" onclick="return f.selectForumIndex()" icon="">[L[Forums]]</btn> -->
        <btn href="{/root/urls/base}" onclick="" icon="">[L[Forums]]</btn>
        <btn href="javascript:void(0);" onclick="orca_admin.newCat()" icon="{/root/urls/img}btn_icon_new_cat.gif">[L[New Group]]</btn>        
        <xsl:for-each select="../langs/lang">
            <btn href="javascript:void(0);" onclick="return orca_admin.compileLangs('{.}')" icon="">[L[Compile Lang:]]<xsl:value-of select="." /></btn>
        </xsl:for-each>
    </xsl:variable>

    <xsl:call-template name="box">
        <xsl:with-param name="title">[L[Manage Forum]]</xsl:with-param>
        <xsl:with-param name="content">

            <table class="forum_table_list forum_table_categories">
                <xsl:apply-templates select="categs" />
            </table>

        </xsl:with-param>
        <xsl:with-param name="menu" select="exsl:node-set($menu_links)/*" />
    </xsl:call-template>

</xsl:template>

<xsl:template match="categ">		

	<tr id="cat{@id}">		
        <td colspan="3">
            <div style="position:relative;">

                <a class="colexp" href="javascript:void(0);" onclick="return orca_admin.selectCat('{@uri}', 'cat{@id}');">
                    <xsl:element name="div">
                        <xsl:attribute name="class">colexp</xsl:attribute>
                        <xsl:if test="count(forum) &gt; 0">
                            <xsl:attribute name="style">background-position:0px -32px</xsl:attribute>
                        </xsl:if>
                        &#160;
                    </xsl:element>
                </a>			

                <a class="forum_cat_title" href="javascript:void(0);" onclick="return orca_admin.selectCat('{@uri}', 'cat{@id}');"><xsl:value-of select="title" disable-output-escaping="yes" /></a>

                <span class="forum_stat"> 
                    &#8226; 
                    <xsl:call-template name="replace_hash">
                        <xsl:with-param name="s" select="string('[L[# forums]]')"/>
                        <xsl:with-param name="r" select="@count_forums"/>
                    </xsl:call-template>
                    &#8226; 
                    <xsl:call-template name="replace_hash">
                        <xsl:with-param name="s" select="string('[L[# topics]]')"/>
                        <xsl:with-param name="r" select="@count_topics"/>
                    </xsl:call-template>
                    &#8226;
                    <xsl:call-template name="replace_hash">
                        <xsl:with-param name="s" select="string('[L[# posts]]')"/>
                        <xsl:with-param name="r" select="@count_posts"/>
                    </xsl:call-template>
                </span>

                <div style="position:absolute; right:8px; top:13px; width:90px;">			

                    <div title="[L[edit]]" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
                        <a href="javascript:void(0);" onclick="orca_admin.editCat ({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
                        <img src="{/root/urls/img}btn_icon_edit.gif" />
                    </div>

                    <div title="[L[delete]]" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
                        <a href="javascript:void(0);" onclick="orca_admin.delCat ({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
                        <img src="{/root/urls/img}btn_icon_delete.gif" />
                    </div>

                    <div title="[L[new forum]]" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
                        <a href="javascript:void(0);" onclick="orca_admin.newForum ({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
                        <img src="{/root/urls/img}btn_icon_new_forum.gif" />
                    </div>

                </div>
            </div>
		</td>
	</tr>
		

</xsl:template>

</xsl:stylesheet>


