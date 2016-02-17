<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

    <xsl:template name="box">
        <xsl:param name="title" />
        <xsl:param name="content" />
        <xsl:param name="menu" />
        <div class="disignBoxFirst bx-def-margin-top bx-def-border">
            <div class="boxFirstHeader bx-def-bh-margin">
                <div class="dbTitle">
                    <xsl:value-of select="$title" />
                </div>
                <xsl:if test="$menu">
                    <div class="dbTopMenu">
                        <xsl:for-each select="$menu">
                            <div>
                                <xsl:attribute name="class"><xsl:choose><xsl:when test="@active and @active = 'yes'">active</xsl:when><xsl:otherwise>notActive</xsl:otherwise></xsl:choose></xsl:attribute>
                                <xsl:choose>
                                    <xsl:when test="@active and @active = 'yes'">
                                        <span><xsl:value-of select="." /></span>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <a class="top_members_menu" onclick="{@onclick}" href="{@href}"><xsl:value-of select="." /></a>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </div>
                            <xsl:if test="position()!=last()">
                                <div class="bullet">&#183;</div>
                            </xsl:if>
                        </xsl:for-each>
                    </div>
                </xsl:if>
                <div class="clear_both">&#160;</div>
            </div>
            <div class="boxContent">
                <xsl:copy-of select="$content" />
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>

