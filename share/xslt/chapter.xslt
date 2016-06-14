<?xml version="1.0" encoding="utf-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:param name="dev" />
  <xsl:param name="toc" />

  <xsl:output
    method="html"
    encoding="utf-8"
    omit-xml-declaration="yes"
    indent="no"
    />
  <xsl:strip-space elements="*" />

  <xsl:include href="page.xslt" />

  <xsl:variable name="novel" select="document($toc)/Novel" />
  <xsl:variable name="ref" select="/Chapter/@ref" />
  <xsl:variable name="cci">
    <xsl:for-each select="$novel/Chapters/Chapter">
      <xsl:choose>
        <xsl:when test="$ref = @ref">
          <xsl:value-of select="position()" />
        </xsl:when>
      </xsl:choose>
    </xsl:for-each>
  </xsl:variable>

  <xsl:template match="/Chapter">
    <xsl:call-template name="page">
      <xsl:with-param name="dev" select="$dev" />
      <xsl:with-param name="type">
        <xsl:text>chapter</xsl:text>
      </xsl:with-param>
      <xsl:with-param name="title">
        <xsl:value-of select="$novel/Title" />
        <xsl:text> </xsl:text>
        <xsl:value-of select="Title" />
      </xsl:with-param>
      <xsl:with-param name="author" select="$novel/Author" />
      <xsl:with-param name="content">
        <article>
          <xsl:apply-templates />
          <footer>
            <nav>
              <xsl:call-template name="ptn">
                <xsl:with-param name="cci" select="$cci" />
                <xsl:with-param name="novel" select="$novel" />
              </xsl:call-template>
            </nav>
          </footer>
        </article>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="/Chapter/Title">
    <h2>
      <xsl:value-of select="." />
    </h2>
  </xsl:template>

  <xsl:template match="/Chapter/Paragraphs/Paragraph">
    <p>
      <xsl:value-of select="." />
    </p>
  </xsl:template>

  <xsl:template name="ptn">
    <xsl:param name="cci" />
    <xsl:param name="novel" />
    <xsl:variable name="toc" select="$novel/Chapters/Chapter" />
    <ul>
      <li>
        <a>
          <xsl:choose>
            <xsl:when test="$cci != 1">
              <xsl:attribute name="href">
                <xsl:value-of select="$cci - 1" />
              </xsl:attribute>
              <xsl:attribute name="title">
                <xsl:text><![CDATA[前一章《]]></xsl:text>
                <xsl:value-of select="$toc[position() = $cci - 1]" />
                <xsl:text><![CDATA[》]]></xsl:text>
              </xsl:attribute>
            </xsl:when>
            <xsl:otherwise>
              <xsl:attribute name="href">
                <xsl:text>#</xsl:text>
              </xsl:attribute>
            </xsl:otherwise>
          </xsl:choose>
          <span class="iconfont icon-prev"></span>
        </a>
      </li>
      <li>
        <a href=".">
          <xsl:attribute name="title">
            <xsl:text><![CDATA[《]]></xsl:text>
            <xsl:value-of select="$novel/Title" />
            <xsl:text><![CDATA[》章节目录]]></xsl:text>
          </xsl:attribute>
          <span class="iconfont icon-list"></span>
        </a>
      </li>
      <li>
        <a>
          <xsl:choose>
            <xsl:when test="$cci != count($toc)">
              <xsl:attribute name="href">
                <xsl:value-of select="$cci + 1" />
              </xsl:attribute>
              <xsl:attribute name="title">
                <xsl:text><![CDATA[后一章《]]></xsl:text>
                <xsl:value-of select="$toc[position() = $cci + 1]" />
                <xsl:text><![CDATA[》]]></xsl:text>
              </xsl:attribute>
            </xsl:when>
            <xsl:otherwise>
              <xsl:attribute name="href">
                <xsl:text>#</xsl:text>
              </xsl:attribute>
            </xsl:otherwise>
          </xsl:choose>
          <span class="iconfont icon-next"></span>
          <span class="hidden badge">0</span>
        </a>
      </li>
    </ul>
  </xsl:template>
</xsl:transform>
