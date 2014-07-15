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
          <header>
            <div class="container">
              <xsl:apply-templates select="Title" />
              <xsl:apply-templates select="Author" />
            </div>
          </header>
          <xsl:apply-templates select="Chapters" />
        </section>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="/Novel/Title">
    <h1 class="text-nowrap">
      <xsl:value-of select="." />
    </h1>
  </xsl:template>

  <xsl:template match="/Novel/Author">
    <address>
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
    <li class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
      <a class="text-nowrap">
        <xsl:attribute name="href">
          <xsl:number />
          <xsl:text>.xml</xsl:text>
        </xsl:attribute>
        <xsl:value-of select="." />
      </a>
    </li>
  </xsl:template>
</xsl:transform>
