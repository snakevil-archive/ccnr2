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
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <title>
        <xsl:value-of select="$title" />
        <xsl:text><![CDATA[ | CCNR v2]]></xsl:text>
      </title>
      <meta name="author">
        <xsl:attribute name="content">
          <xsl:value-of select="$author" />
        </xsl:attribute>
      </meta>
      <meta
        name="viewport"
        content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"
      />
      <link rel="icon" href="//s.szen.in/n/icon.png" />
      <link
        rel="stylesheet"
        href="//cdn.staticfile.org/twitter-bootstrap/3.2.0/css/bootstrap.min.css"
      />
      <link rel="stylesheet" href="//s.szen.in/n/ccnr2.min.css" />
    </head>
    <body>
      <xsl:copy-of select="$content" />
      <script src="//cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
      <script src="//s.szen.in/n/ccnr2.min.js"></script>
    </body>
    </html>
  </xsl:template>
</xsl:transform>
