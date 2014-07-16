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
      <xsl:with-param name="title" select="Title" />
      <xsl:with-param name="author" select="Author" />
      <xsl:with-param name="content">
        <section>
          <header class="page-header">
            <div class="container">
              <div class="row">
                <xsl:apply-templates select="Title" />
                <xsl:apply-templates select="Author" />
              </div>
            </div>
          </header>
          <xsl:apply-templates select="Chapters" />
        </section>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="/Novel/Title">
    <h1 class="col-xs-12 text-nowrap text-right">
      <xsl:text>《</xsl:text>
      <xsl:value-of select="." />
      <xsl:text>》</xsl:text>
    </h1>
  </xsl:template>

  <xsl:template match="/Novel/Author">
    <address class="col-xs-12 hidden-xs text-nowrap text-right">
      <xsl:value-of select="." />
    </address>
  </xsl:template>

  <xsl:template match="/Novel/Chapters">
    <nav class="container">
      <ul class="row list-inline">
        <xsl:apply-templates />
      </ul>
    </nav>
  </xsl:template>

  <xsl:template match="/Novel/Chapters/Chapter">
    <li class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-4 text-nowrap">
      <a>
        <xsl:attribute name="href">
          <xsl:number />
          <xsl:text>.xml</xsl:text>
        </xsl:attribute>
        <xsl:attribute name="title">
          <xsl:value-of select="." />
        </xsl:attribute>
        <xsl:value-of select="." />
      </a>
    </li>
  </xsl:template>
</xsl:transform>
