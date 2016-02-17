<script type="text/javascript">

jqueryNoConflict(document).ready(function(){
//jqueryNoConflict('#pf_menu_load').hide();
       jqueryNoConflict.ajax({
          url: "http://www.parentfinder.com/pf_badge_load/json-data.php?page=topMenu",
          type: "POST",
          cache: false,
          data: {approve: 1, from: 'profile'},
          datatype: "json",
          success: function(jd) {

                if (jd.children_value) {
                  for (var i in jd.children_value) {
                  var liSpan1 = jqueryNoConflict('<li></li>');
                  var LinkAtt=  jqueryNoConflict('<a class="item" id="'+jd.children_value[i].id+'" type="Familysize">'+jd.children_value[i].data[0]+'</a>');
                  liSpan1.html(jd.children_value[i].data[0]);
                  liSpan1.attr('type', 'Familysize');
                  liSpan1.attr('id', jd.children_value[i].id);
                  jqueryNoConflict('.Familysize').append(jqueryNoConflict(liSpan1));
                  jqueryNoConflict('#pf_kids_resmenu').append(jqueryNoConflict(LinkAtt));
                  }
                }
                else {
                jqueryNoConflict('.Familysize').css('display', 'none');
                }

                if (jd.state_value) {
                  for (var i in jd.state_value) {
                  var liSpan = jqueryNoConflict('<li></li>');
                  liSpan.html(jd.state_value[i].id);
                  liSpan.attr('type', 'State');
                  liSpan.attr('id', jd.state_value[i].id);
                  jqueryNoConflict('.State').append(jqueryNoConflict(liSpan));
                  }
                }
                else 
                {
                jqueryNoConflict('.State').css('display', 'none');
                }

                if (jd.sort_value) {
                  for (var i in jd.sort_value) {
                  var liSpan3 = jqueryNoConflict('<li></li>');
                  liSpan3.html(jd.sort_value[i].data);
                  liSpan3.attr('type', 'Sortby');
                  liSpan3.attr('id', jd.sort_value[i].id);
                  jqueryNoConflict('.Sortby').append(jqueryNoConflict(liSpan3));
                  }
                }
                else {
                jqueryNoConflict('.Sortby').css('display', 'none');
                }

                if (jd.region_value) {
                  for (var i in jd.region_value) {
                  var liSpan4 = jqueryNoConflict('<li></li>');
                  var LinkAtt=  jqueryNoConflict('<a class="item" id="'+jd.region_value[i].id+'" type="Region">'+jd.region_value[i].data+'</a>');
                  liSpan4.html(jd.region_value[i].data);
                  liSpan4.attr('type', 'Region');
                  liSpan4.attr('id', jd.region_value[i].id);
                  jqueryNoConflict('.Region').append(jqueryNoConflict(liSpan4));
                  jqueryNoConflict('#pf_region_resmenu').append(jqueryNoConflict(LinkAtt));                               
                  }
                }
                else {
                jqueryNoConflict('.Region').css('display', 'none');
                }

                if (jd.religion_value) {
                  for (var i in jd.religion_value) {
                  var liSpan5 = jqueryNoConflict('<li></li>');
                  liSpan5.html(jd.religion_value[i].data);
                  liSpan5.attr('type', 'Religion');
                  liSpan5.attr('id', jd.religion_value[i].id);
                  jqueryNoConflict('.Religion').append(jqueryNoConflict(liSpan5));
                  }
                }
                else {
                jqueryNoConflict('.Religion').css('display', 'none');
                }


                if (jd.ethnicity_value) {
                  for (var i in jd.ethnicity_value) {
                  var liSpan6 = jqueryNoConflict('<li></li>');
                  liSpan6.html(jd.ethnicity_value[i].data);
                  liSpan6.attr('type', 'ethnicity');
                  liSpan6.attr('id', jd.ethnicity_value[i].id);
                  jqueryNoConflict('.ethnicity').append(jqueryNoConflict(liSpan6));
                  }
                }
                else {
                jqueryNoConflict('.ethnicity').css('display', 'none');
                }
           jqueryNoConflict('#pf_menu_load').show();
           jqueryNoConflict('#pf_footer_load').show();
          }
        });
});
</script>



<style type="text/css">
/*.pf_header{
    background-color:#FFFFFF !important;
    font-family: Helvetica, Arial, "Lucida Grande", sans-serif !important;
    font-size: 12px !important;
    line-height:16px !important;
    color:#76787b !important;
  }*/
</style>

<div class="pf_container pf_view pf_header">
  <div class="Familysize1"><ul></ul></div>
<div id='pf_search'>
  
<input type="text" id="sskey" style="display:none">

 <div class="pf_row" id="pf_menu_load" style="display:none"> <!-- menu section -->      
                <div id="pf_view_id">VIEW OUR FAMILIES <span id="pf_reload"></span></div>
                

                <nav>
                <div class="pf_content_menu">


                        <a>KIDS IN FAMILY
                        <ul style="width:165px;" class="Familysize"></ul>
                        </a>

                        <a>CHILD PREFERENCE 
                        <ul style="width:165px;" class="ethnicity"></ul>
                        </a>
                     
                        <a>RELIGION
                          <ul style="width:165px;" class="Religion"></ul>
                        </a>
                        <a>REGION
                          <ul style="width:165px;" class="Region"></ul>
                        </a>

                        <a>STATE
                             <ul style="width:165px;" class="State">
                             <li type="State" id="all">All States</li>
                             </ul>
                        </a>

                        <a class="last item">SORT BY
                          <ul style="width:165px;" class="Sortby"></ul>
                        </a>
                      </div>

                </nav>



                <div id="pf_filter"> <!--filter menu responsive --> 
                    <div class="pf_filter_cl01"> <!--main filter menu --> 
                      <div class="pf_filter_cl03">
                        KIDS IN FAMILY
                        <span class="pf_filter_cl04"><!--filter menu up arrow --> </span>
                      </div>
                      <div class="pf_content_menu" id="pf_kids_resmenu">

                      </div>
                    </div>

                    <div class="pf_filter_cl01 pf_filter_cl02"> <!--sub filter menu  --> 
                        <div class="pf_filter_cl03">
                          REGION
                          <span class="pf_filter_cl04"><!--filter menu up arrow --> </span>
                        </div>
                        <div class="pf_content_menu" id="pf_region_resmenu">

                        </div>
                    </div>
                 </div>


<div style="clear:both; height:2px;"> </div>
<div class="pf_view_cl024"> 
<div class="pf_view_cl025"> 
<input type="text" class="pf_view_cl026" id="searchInput" placeholder="Search by Name"/>
<input type="button" text="Search by name" value="Search" class="pf_view_cl027"/>
</div>
</div> 


</div>
</div>
