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
        </article>
        <aside class="hidden">
          <nav>
            <ul>
              <li>
                <a class="disabled" href="#" title="回到页首">
                  <span class="ico ico-notop"></span>
                </a>
              </li>
              <li>
                <a class="disabled" href="#">
                  <span class="ico ico-left"></span>
                </a>
              </li>
              <li>
                <a href="." title="目录">
                  <span class="ico ico-toc"></span>
                </a>
              </li>
              <li>
                <a class="disabled" href="#">
                  <span class="ico ico-right"></span>
                  <span class="hidden badge">0</span>
                </a>
              </li>
            </ul>
          </nav>
        </aside>
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
