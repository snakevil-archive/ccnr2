<?xml version="1.0" encoding="utf-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output
    method="html"
    encoding="utf-8"
    omit-xml-declaration="yes"
    indent="no"
    />
  <xsl:strip-space elements="*" />

  <xsl:include href="page.xslt" />

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
          <xsl:apply-templates select="Author" />
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

  <xsl:template match="/Novel/Author">
    <address>
      <xsl:value-of select="." />
    </address>
  </xsl:template>

  <xsl:template match="/Novel/Chapters">
    <ol>
      <xsl:apply-templates />
    </ol>
  </xsl:template>

  <xsl:template match="/Novel/Chapters/Chapter">
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
  </xsl:template>
</xsl:transform>
