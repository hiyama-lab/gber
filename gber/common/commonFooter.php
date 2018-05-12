<?php
echo "<script type=\"text/javascript\">\n";
echo "var baseurl = \"" . $baseurl . "\";\n";
echo "var groupnamelist = [" . $groupstr . "];\n";
echo "var autosizebox = autosize(document.querySelectorAll('textarea'));";
//echo "(function(\$) {\$.extend({nl2br: function nl2br(str) {return str.replace(/[\\n\\r]/g, \"<br />\"); } }); })(jQuery);\n";
echo "setTimeout(function(){\$(\"*\").removeClass(\"ui-corner-all\");},100);\n";
echo "</script>\n";
?>