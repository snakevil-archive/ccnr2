<?xml version="1.0" encoding="utf-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
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
  <xsl:variable name="title" select="/Chapter/Title" />
  <xsl:variable name="cci">
    <xsl:for-each select="$novel/Chapters/Chapter">
      <xsl:choose>
        <xsl:when test="$title = text()">
          <xsl:value-of select="position()" />
        </xsl:when>
      </xsl:choose>
    </xsl:for-each>
  </xsl:variable>

  <xsl:template match="/Chapter">
    <xsl:call-template name="page">
      <xsl:with-param name="title" select="Title" />
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
    <nav class="row">
      <ul class="list-inline col-xs-12 col-md-8 col-md-offset-2">
        <li class="hidden-xs col-sm-4 col-sm-push-4 text-center">
          <a class="btn btn-link" href=".">
            <xsl:text>《</xsl:text>
            <xsl:value-of select="$novel/Title" />
            <xsl:text>》</xsl:text>
          </a>
        </li>
        <li class="col-xs-6 col-sm-4 col-sm-pull-4">
          <xsl:choose>
            <xsl:when test="$cci != 1">
              <a class="btn btn-default">
                <xsl:attribute name="href">
                  <xsl:value-of select="$cci - 1" />
                  <xsl:text>.xml</xsl:text>
                </xsl:attribute>
                <xsl:value-of select="$toc[position() = $cci - 1]" />
              </a>
            </xsl:when>
          </xsl:choose>
        </li>
        <li class="col-xs-6 col-sm-4 text-right">
          <xsl:choose>
            <xsl:when test="$cci != count($toc)">
              <a class="btn btn-primary">
                <xsl:attribute name="href">
                  <xsl:value-of select="$cci + 1" />
                  <xsl:text>.xml</xsl:text>
                </xsl:attribute>
                <xsl:value-of select="$toc[position() = $cci + 1]" />
              </a>
            </xsl:when>
          </xsl:choose>
        </li>
      </ul>
    </nav>
  </xsl:template>
</xsl:transform>
