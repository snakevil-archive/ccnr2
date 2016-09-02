<?xml version="1.0" encoding="utf-8"?>
<xsl:transform version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output
    method="html"
    encoding="utf-8"
    omit-xml-declaration="yes"
    indent="no"
    />
  <xsl:strip-space elements="*" />

  <xsl:include href="page.xslt" />

  <xsl:variable name="size" select="100" />

  <xsl:template match="/Novel">
    <xsl:call-template name="page">
      <xsl:with-param name="type">
        <xsl:text>toc</xsl:text>
      </xsl:with-param>
      <xsl:with-param name="title" select="Title" />
      <xsl:with-param name="author" select="Author" />
      <xsl:with-param name="content">
        <header>
          <xsl:apply-templates select="Title" />
        </header>
        <nav>
          <xsl:apply-templates select="Chapters" />
        </nav>
        <aside class="hidden">
          <nav>
            <ul>
              <li>
                <a class="disabled" href="#" title="回到页首">
                  <span class="ico ico-notop"></span>
                </a>
              </li>
            </ul>
          </nav>
        </aside>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="/Novel/Title">
    <h1>
      <xsl:value-of select="." />
    </h1>
  </xsl:template>

  <xsl:template match="/Novel/Chapters">
    <xsl:apply-templates select="Chapter[position() mod $size = 1]" />
  </xsl:template>

  <xsl:template match="/Novel/Chapters/Chapter">
    <fieldset>
      <xsl:choose>
        <xsl:when test="position() = 1">
          <xsl:attribute name="class">
            <xsl:text>expanded</xsl:text>
          </xsl:attribute>
        </xsl:when>
      </xsl:choose>
      <legend>
        <a>
          <xsl:attribute name="href">
            <xsl:value-of select="position() * $size - $size + 1" />
          </xsl:attribute>
          <xsl:value-of select="." />
        </a>
      </legend>
      <ol>
        <xsl:call-template name="chapter-group">
          <xsl:with-param name="low" select="position() * $size - $size" />
          <xsl:with-param name="high" select="position() * $size + 1" />
        </xsl:call-template>
      </ol>
    </fieldset>
  </xsl:template>

  <xsl:template name="chapter-group">
    <xsl:param name="low" />
    <xsl:param name="high" />
    <xsl:for-each select="/Novel/Chapters/Chapter[$low &lt; position() and position() &lt; $high]">
      <li>
        <a>
          <xsl:attribute name="href">
            <xsl:number />
          </xsl:attribute>
          <xsl:attribute name="title">
            <xsl:value-of select="." />
          </xsl:attribute>
          <xsl:value-of select="." />
        </a>
      </li>
    </xsl:for-each>
  </xsl:template>
</xsl:transform>
