; <?php exit; ?> DO NOT REMOVE THIS LINE
{
"allowcache":true,
"splitmode":"1",
"path.pdf":"\/var\/www\/html\/pf\/PDFTemplates\/user\/",
"path.swf":"\/var\/www\/html\/pf\/log\/swf_files\/",
"renderingorder.primary":"flash",
"renderingorder.secondary":"html",
"cmd.conversion.singledoc":"\"D:\\wwwroot\\mark\\www\\documentViewer\\coversors\\SWFTools\\pdf2swf.exe\" \"{path.pdf}{pdffile}\" -o \"{path.swf}{pdffile}.swf\" -f -T 9 -t -s storeallcharacters -s linknameurl",
"cmd.conversion.splitpages":"\"D:\\wwwroot\\mark\\www\\documentViewer\\coversors\\SWFTools\\pdf2swf.exe\" \"{path.pdf}{pdffile}\" -o \"{path.swf}{pdffile}_%.swf\" -f -T 9 -t -s storeallcharacters -s linknameurl",
"cmd.conversion.renderpage":"\"D:\\wwwroot\\mark\\www\\documentViewer\\coversors\\SWFTools\\swfrender.exe\" \"{path.swf}{swffile}\" -p {page} -o \"{path.swf}{pdffile}_{page}.png\" -X 1024 -s keepaspectratio",
"cmd.conversion.rendersplitpage":"\"D:\\wwwroot\\mark\\www\\documentViewer\\coversors\\SWFTools\\swfrender.exe\" \"{path.swf}{swffile}\" -o \"{path.swf}{pdffile}_{page}.png\" -X 1024 -s keepaspectratio",
"cmd.conversion.jsonfile":"\"C:\\Program Files\\PDF2JSON\\pdf2json.exe\" \"{path.pdf}{pdffile}\" -enc UTF-8 -compress \"{path.swf}{pdffile}.js\"",
"cmd.conversion.splitjsonfile":"\"C:\\Program Files\\PDF2JSON\\pdf2json.exe\" \"{path.pdf}{pdffile}\" -enc UTF-8 -compress -split 10 \"{path.swf}{pdffile}_%.js\"",
"cmd.conversion.splitpdffile":"\"C:\\Program Files (x86)\\PDF Labs\\PDFtk Server\\bin\\pdftk.exe\" \"{path.pdf}{pdffile}\" burst output \"{path.swf}{pdffile}_%1d.pdf\" compress",
"cmd.searching.extracttext":"\"D:\\wwwroot\\mark\\www\\documentViewer\\coversors\\SWFTools\\swfstrings.exe\" \"{swffile}\"",
"cmd.query.swfwidth":"swfdump \"{swffile}\" -X",
"cmd.query.swfheight":"swfdump.exe \"{swffile}\" -Y",
"pdf2swf":true,
"admin.username":"cairs",
"admin.password":"flexpaper",
"licensekey":"@0b018f276767ed8579b$0cbe0d872881db4ab0f"
}