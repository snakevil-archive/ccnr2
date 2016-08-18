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

  <xsl:variable name="ref" select="/Chapter/@ref" />

  <xsl:template match="/Chapter">
    <xsl:call-template name="page">
      <xsl:with-param name="type">
        <xsl:text>chapter</xsl:text>
      </xsl:with-param>
      <xsl:with-param name="title" select="Title" />
      <xsl:with-param name="author">
        <xsl:text> </xsl:text>
      </xsl:with-param>
      <xsl:with-param name="content">
        <article>
          <xsl:attribute name="data-toc">
            <xsl:value-of select="/Chapter/@toc" />
          </xsl:attribute>
          <xsl:apply-templates />
          <footer class="hidden">
            <nav>
              <ul>
                <li>
                  <a href="#">
                    <span class="iconfont icon-prev"></span>
                  </a>
                </li>
                <li>
                  <a href="." title="章节目录">
                    <span class="iconfont icon-list"></span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <span class="iconfont icon-next"></span>
                    <span class="hidden badge">0</span>
                  </a>
                </li>
              </ul>
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
</xsl:transform>
