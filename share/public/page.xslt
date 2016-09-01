<?xml version="1.0" encoding="utf-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template name="page">
    <xsl:param name="type" />
    <xsl:param name="title" />
    <xsl:param name="author" />
    <xsl:param name="content" />
    <html lang="zh-CN">
      <xsl:attribute name="class">
        <xsl:text>page-</xsl:text>
        <xsl:value-of select="$type" />
      </xsl:attribute>
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
      <link href="//cdn.bootcss.com/normalize/4.2.0/normalize.min.css" rel="stylesheet" />
      <link href="//s.szen.in/n/ccnr2.min.css" rel="stylesheet" />
    </head>
    <body>
      <div>
        <a class="github-fork-ribbon" href="https://github.com/snakevil/ccnr2" target="_blank" title="Fork me on GitHub"></a>
      </div>
      <section>
        <xsl:copy-of select="$content" />
      </section>
      <footer>
        <address>by CCNRv2</address>
        <div class="progress"></div>
      </footer>
      <script src="//cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
      <script src="//s.szen.in/n/ccnr2.min.js"></script>
    </body>
    </html>
  </xsl:template>
</xsl:transform>
