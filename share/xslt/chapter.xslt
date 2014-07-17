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
      <xsl:with-param name="title">
        <xsl:value-of select="$novel/Title" />
        <xsl:text> </xsl:text>
        <xsl:value-of select="Title" />
      </xsl:with-param>
      <xsl:with-param name="author" select="$novel/Author" />
      <xsl:with-param name="content">
        <article class="container">
          <xsl:apply-templates />
          <footer>
            <xsl:call-template name="ptn">
              <xsl:with-param name="cci" select="$cci" />
              <xsl:with-param name="novel" select="$novel" />
            </xsl:call-template>
          </footer>
        </article>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="/Chapter/Title">
    <h2 class="text-center text-nowrap">
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
    <nav class="navbar navbar-collapse navbar-fixed-bottom">
      <ul class="list-inline text-center">
        <li>
          <a class="btn btn-primary navbar-btn">
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
                <xsl:attribute name="class">
                  <xsl:text>btn btn-default navbar-btn disabled</xsl:text>
                </xsl:attribute>
              </xsl:otherwise>
            </xsl:choose>
            <span class="glyphicon glyphicon-chevron-left"></span>
          </a>
        </li>
        <li>
          <a class="btn btn-primary navbar-btn" href=".">
            <xsl:attribute name="title">
              <xsl:text><![CDATA[《]]></xsl:text>
              <xsl:value-of select="$novel/Title" />
              <xsl:text><![CDATA[》章节目录]]></xsl:text>
            </xsl:attribute>
            <span class="glyphicon glyphicon-list-alt"></span>
          </a>
        </li>
        <li>
          <a class="btn btn-warning navbar-btn">
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
                <xsl:attribute name="class">
                  <xsl:text>btn btn-default navbar-btn disabled</xsl:text>
                </xsl:attribute>
              </xsl:otherwise>
            </xsl:choose>
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="badge hidden">0</span>
          </a>
        </li>
      </ul>
    </nav>
  </xsl:template>
</xsl:transform>
