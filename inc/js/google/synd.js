function igs_e(igs_){if(igs_.charAt(0)=="#"){igs_=igs_.substring(1,igs_.length)}var igs_c=0,igs_b=0,igs_a=0;if(igs_.length==3){igs_c=parseInt(igs_.charAt(0),16)/16;igs_b=parseInt(igs_.charAt(1),16)/16;igs_a=parseInt(igs_.charAt(2),16)/16}else if(igs_.length==6){igs_c=parseInt(igs_.substring(0,2),16)/255;igs_b=parseInt(igs_.substring(2,4),16)/255;igs_a=parseInt(igs_.substring(4,6),16)/255}return(igs_c+igs_b+igs_a)/3}
var igs_d=-1;function _IG_AdjustObjColor(igs_){if(igs_d==-1){var igs_c=document.bgColor;igs_d=0;if(igs_e(igs_c)<0.5){igs_d=1}}var igs_b=igs_d?"ig_darkbg":"ig_lightbg";var igs_a=document.getElementById(igs_);if(igs_a.tagName=="DIV"){igs_a.setAttribute("class",igs_b)}else if(igs_a.tagName=="A"){igs_a.setAttribute("class",igs_b+"link")}else if(igs_a.tagName=="IFRAME"){igs_a.setAttribute("class",igs_b+"frame")}else if(igs_a.tagName=="TD"){igs_a.setAttribute("class",igs_b)}else if(igs_a.tagName=="P"){
igs_a.setAttribute("class",igs_b)}}
function _IG_ClearCachedIsDark(){igs_d=-1}
function _IG_NumGadgets(){for(var igs_=0;1;igs_++){if(_gel("_ig_title"+igs_)==null){return igs_}}return 0}
;

