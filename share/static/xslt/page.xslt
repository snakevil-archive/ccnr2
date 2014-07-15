<?xml version="1.0" encoding="utf-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template name="page">
    <xsl:param name="title" />
    <xsl:param name="author" />
    <xsl:param name="content" />
    <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
    <html lang="zh-CN">
    <head>
      <meta charset="utf-8" />
      <meta
        name="viewport"
        content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"
      />
      <title>
        <xsl:value-of select="$title" />
        <xsl:text><![CDATA[ | CCNR v2]]></xsl:text>
      </title>
      <meta name="author">
        <xsl:attribute name="content">
          <xsl:value-of select="$author" />
        </xsl:attribute>
      </meta>
      <link
        rel="stylesheet"
        href="http://libs.useso.com/js/bootstrap/3.2.0/css/bootstrap.min.css"
      />
      <link rel="stylesheet" href="http://s.szen.in/c/ccnr2.css" />
    </head>
    <body>
      <xsl:copy-of select="$content" />
      <script src="http://libs.useso.com/js/jquery/2.1.1/jquery.min.js"></script>
    </body>
    </html>
  </xsl:template>
</xsl:transform>
