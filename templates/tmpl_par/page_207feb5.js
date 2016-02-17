<bx_include_auto:_resp_sub_header.html />

<!--<link rel="stylesheet" type="text/css" href="viewourfamilies/css/bootstrap.min.css?cache=__cache_control__"/>
<link rel="stylesheet" type="text/css" href="viewourfamilies/css/parentfinder.css?cache=__cache_control__"/>-->
<!--<script src="viewourfamilies/js/bootstrap.min.js?cache=__cache_control__"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js?cache=__cache_control__"></script>-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script>
<script src="viewourfamilies/js/viewourfamilies.js?cache=__cache_control__"></script>
<script src="viewourfamilies/js/script.js?cache=__cache_control__"></script>
<script type="text/javascript">
    var siteurl = '<bx_url_root />';
//    var url = document.referrer;
//    var hostname = $('<a>').prop('href', url).prop('hostname');
    var load_agencyid = '__load_agencyid__';
    var adoption_agency = '__profile_agencyid__';
</script>
<style>
    @media (min-width: 960px) {
        .pf_view_cl01 {
           /* width: 1025px;*/
	   width:100%;
        }
    } 
    .pf_view_cl01 {
        margin: auto;
    }
</style>
<div class="clear_both"></div>
<div class="page113">
    <div class="modal popup" id="more"><!-- tooltip dialog responsive-->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                    <div class="tooltipData pf_popupcl01"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="likeClick" data-toggle="modal" data-target="#likeAdded"></div>
    <div class="modal popup" id="likeAdded"><!-- tooltip dialog responsive-->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                    <div class="pf_popupcl01">
                        <div class="data ok"></div>
                        <div class="likeButtons">
                            <div class="likepop pf_popup_login_cl08" data-dismiss="modal">OK</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="likeDialog" data-toggle="modal" data-target="#likeLogin"></div>
    <div class="modal popup" id="likeLogin"><!-- tooltip dialog responsive-->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                    <div class="pf_popupcl01">
                        <div class="ok">Cannot save your favorites since you are not logged in.</div>
                        <div class="likeButtons">
                            <div class="createAcc likepop pf_popup_login_cl08">Create Account</div>
                            <div class="loginAcc likepop pf_popup_login_cl08">Login</div>
                            <div class="NoAcc likepop pf_popup_login_cl08" data-dismiss="modal">No Thanks</div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container view"> <!-- menu section -->
        <div u="loading" class="loader">Loading</div>
        <div class="row" style="display:none">
            <div id="pf_view_id">VIEW OUR FAMILIES</div><span id="reload"></span>
            <nav id="scroll">
                <div class="pf_content_menu">
                    <a data-val="Familysize" class="item Familysize">KIDS IN FAMILY
                        <ul style="width: 199px;"></ul>
                    </a>
                    <a data-val="ethnicity" class="item ethnicity">CHILD PREFERENCE
                        <ul style="width: 190px;">
                            <li><span data-option="Middle Eastern" data-val="ethnicity">Middle Eastern</span></li>
                            <li><span data-option="Asian" data-val="ethnicity">Asian</span></li>
                            <li><span data-option="African American" data-val="ethnicity">African American</span></li>
                            <li><span data-option="African American/Asian" data-val="ethnicity">African American/Asian</span></li>
                            <li><span data-option="Asian/Hispanic" data-val="ethnicity">Asian/Hispanic</span></li>
                            <li><span data-option="Bi-Racial" data-val="ethnicity">Bi-Racial</span></li>
                            <li><span data-option="Caucasian" data-val="ethnicity">Caucasian</span></li>
                            <li><span data-option="Caucasian/Asian" data-val="ethnicity">Caucasian/Asian</span></li>
                            <li><span data-option="Caucasian/African American" data-val="ethnicity">Caucasian/African American</span></li>
                            <li><span data-option="Caucasian/Hispanic" data-val="ethnicity">Caucasian/Hispanic</span></li>
                            <li><span data-option="European" data-val="ethnicity">European</span></li>
                            <li><span data-option="Caucasian/Native American" data-val="ethnicity">Caucasian/Native American</span></li>
                            <li><span data-option="Eastern European/Slavic/Russian" data-val="ethnicity">Eastern European/Slavic/Russian</span></li>
                            <li><span data-option="Hispanic" data-val="ethnicity">Hispanic</span></li>
                            <li><span data-option="Hispanic/African American" data-val="ethnicity">Hispanic/African American</span></li>
                            <li><span data-option="Hispanic or South/Central American" data-val="ethnicity">Hispanic or South/Central American</span></li>
                            <li><span data-option="Jewish" data-val="ethnicity">Jewish</span></li>
                            <li><span data-option="Mediterranean" data-val="ethnicity">Mediterranean</span></li>
                            <li><span data-option="Multi-Racial" data-val="ethnicity">Multi-Racial</span></li>
                            <li><span data-option="Native American (American Indian)" data-val="ethnicity">Native American (American Indian)</span></li>
                            <li><span data-option="Pacific Islander" data-val="ethnicity">Pacific Islander</span></li>
                            <li><span data-option="Other" data-val="ethnicity">Other</span></li>
                        </ul>
                    </a>
                    <a data-val="Religion" class="item Religion">RELIGION
                        <ul>
                            <li><span data-option="Anglican" data-val="Religion">Anglican</span></li>
                            <li><span data-option="Bahai" data-val="Religion">Bahai</span></li>
                            <li><span data-option="Baptist" data-val="Religion">Baptist</span></li>
                            <li><span data-option="Buddhist" data-val="Religion">Buddhist</span></li>
                            <li><span data-option="Catholic" data-val="Religion">Catholic</span></li>
                            <li><span data-option="Christian" data-val="Religion">Christian</span></li>
                            <li><span data-option="Church of Christ" data-val="Religion">Church of Christ</span></li>
                            <li><span data-option="Episcopal" data-val="Religion">Episcopal</span></li>
                            <li><span data-option="Hindu" data-val="Religion">Hindu</span></li>
                            <li><span data-option="Jewish" data-val="Religion">Jewish</span></li>
                            <li><span data-option="Lutheran" data-val="Religion">Lutheran</span></li>
                            <li><span data-option="Methodist" data-val="Religion">Methodist</span></li>
                            <li><span data-option="Non-denominational" data-val="Religion">Non-denominational</span></li>
                            <li><span data-option="None" data-val="Religion">None</span></li>
                            <li><span data-option="Other" data-val="Religion">Other</span></li>
                            <li><span data-option="Presbyterian" data-val="Religion">Presbyterian</span></li>
                            <li><span data-option="Protestant" data-val="Religion">Protestant</span></li>
                            <li><span data-option="Unitarian" data-val="Religion">Unitarian</span></li>
                        </ul>
                    </a>
                    <a data-val="Region" class="item Region">REGION
                        <ul>
                            <li><span data-option="Non US" data-val="Region">Non US</span></li>
                            <li><span data-option="North-central" data-val="Region">North-central</span></li>
                            <li><span data-option="Northeast" data-val="Region">Northeast</span></li>
                            <li><span data-option="Northwest" data-val="Region">Northwest</span></li>
                            <li><span data-option="South-central" data-val="Region">South-central</span></li>
                            <li><span data-option="Southeast" data-val="Region">Southeast</span></li>
                            <li><span data-option="Southwest" data-val="Region">Southwest</span></li>
                        </ul>
                    </a>
                    <a data-val="State" class="item State">STATE
                        <ul>
                            <li><span data-val="State" data-option="all">All States</span></li>
                        </ul>
                    </a>
                    <a class="last item Sortby" data-val="Sortby">SORT BY
                        <ul>
                            <li><span data-val="Sortby" data-option="newFirst">Newest First</span></li>
                            <li><span data-val="Sortby" data-option="oldFirst">Oldest First</span></li>
                            <li><span data-val="Sortby" data-option="FirstName">First Name</span></li>
                            <li><span data-val="Sortby" data-option="random">Random</span></li>
                        </ul>
                    </a>
                </div>
            </nav>
            <!--<div id="filter"></div>-->
            <div class="clear_both"></div>
            <div id="pf_filter"> <!--filter menu responsive --> 
                <div class="pf_filter_cl01"> <!--main filter menu --> 
                    <div class="pf_filter_cl03">
                        <label class="selectedMain">SORT BY</label>
                        <span class="pf_filter_cl04"><!--filter menu up arrow --> </span>
                    </div>
                    <div class="pf_content_menu">
                        <a data-val="Familysize" class="item main">KIDS IN THE FAMILY</a>
                        <a data-val="ethnicity" class="item main">CHILD PREFERENCE</a>
                        <a data-val="Religion" class="item main">RELIGION</a>
                        <a data-val="Region" class="item main">REGION</a>
                        <a data-val="State" class="item main">STATE</a>
                        <a data-val="Sortby" class="last item main" style="display:none;">SORT BY</a>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="pf_filter_cl01 pf_filter_cl02"> <!--sub filter menu  --> 
                    <div class="pf_filter_cl03">
                        <label class="selectedSub">Please Select</label>
                        <span class="pf_filter_cl04"><!--filter menu up arrow --> </span>
                    </div>
                    <div class="pf_content_menu">
                        <ul></ul>
                    </div>
                </div>
            </div> 
        </div> 
        <div class="row">
            <div class="searchFamilies">
                <input type="hidden" id="searchedName">
                <input type="text" id="searchInput" placeholder="Search by Name">
                <div class="search">Search</div>
            </div>
        </div>
        <div class="row">
            <div class="searchResult searchFamilies" style="display:none">
                <p>Search results for the name "<span></span>"</p>
            </div>
        </div>
        <div class="row" style="display: none;overflow-x: visible;">
            <input type="hidden" id="sortyby" value=""/>
            <input type="hidden" id="type" value=""/>
            <div class="pf_view_cl020"><!-- row section starts-->
                <div class="pf_view_cl01" ><!-- outer container starts-->
                    <div id="1" class="pf_view_cl02">
                        <div class="pf_view_cl03"><!-- image container-->
                            <img  src="" alt="image"/>
                        </div>
                        <div class="pf_view_cl04 pf_profile_cl01"><!-- name container-->
                            <!--<span></span>-->
                            <div class="pf_profile_filter_cl01">
                                <select class="selectpicker" data-style="btn-inverse" ></select>
                            </div>
<!--                            <div class="pf_profile_filter_cl01"> main filter menu  
                                <select class="selectpicker" data-style="btn-inverse" ></select>
                            </div>-->
                            <a href="#" class="pf_view_cl013"><img src="viewourfamilies/images/icon_profile_print.png" alt="" class="pf_profile_cl02"></a>
                            <span class="pinkBar"></span>
                            <img class="pf_like_button">
                        </div>
                        <div class="pf_view_cl05">
                            <div class="pf_view_cl06"><!-- informations-->
<!--                                <div class="pf_profile_filter_cl01">
                                    <select class="selectpicker" data-style="btn-inverse" ></select>
                                </div>-->
                                <div class="comb">
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">AGE:</span>
                                    <span class="age"></span>
                                </div>
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">STATE:</span>
                                    <span class="state"></span>
                                </div>
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">WAITING:</span>
                                    <span class="waiting"></span>
                                </div>
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">CHILDREN:</span>
                                    <span class="children"></span>
                                </div>
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">FAITH:</span>
                                    <span class="faith"></span>
                                </div>
                                </div>
                                <div class="ind1">
                                    <div class="pf_view_cl014 pf_profile_cl06">
                                        <span class="pf_view_cl015">AGE</span>
                                        <span> : </span>
                                        <span class="ageGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">EDUCATION</span>
                                        <span> : </span>
                                        <span class="EducationGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">PROFESSION</span>
                                        <span> : </span>
                                        <span class="ProfessionGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">ETHNICITY</span>
                                        <span> : </span>
                                        <span class="EthinicityGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">RELIGION</span>
                                        <span> : </span>
                                        <span class="ReligionGrid"></span>
                                    </div>
                                </div>
                                <div class="ind2">
                                    <div class="pf_view_cl014 pf_profile_cl06">
                                        <span class="pf_view_cl015">AGE</span>
                                        <span> : </span>
                                        <span class="ageGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">EDUCATION</span>
                                        <span> : </span>
                                        <span class="EducationGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">PROFESSION</span>
                                        <span> : </span>
                                        <span class="ProfessionGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">ETHNICITY</span>
                                        <span> : </span>
                                        <span class="EthinicityGrid"></span>
                                    </div>
                                    <div class="pf_view_cl014">
                                        <span class="pf_view_cl015">RELIGION</span>
                                        <span> : </span>
                                        <span class="ReligionGrid"></span>
                                    </div>
                                </div>
                                <div class="pf_view_cl016">
                                    <span class="ourchild">OUR CHILD</span>
                                </div>
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">ETHNICITY:</span>
                                    <span class="ethnicity"></span>
                                </div>
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">AGE:</span>
                                    <span class="agechild"></span>
                                </div>
                                <div class="pf_view_cl014">
                                    <span class="pf_view_cl015">ADOPTION TYPE:</span>
                                    <span class="adptype"></span>
                                </div>
                            </div>
                            <div class="pf_view_cl07"><!-- links container-->
                                <a href="#" target="" class="pf_view_cl08"><!-- icons-->  </a>
                                <a href="#" target="" class="pf_view_cl09"> <!-- icons-->  </a>
                                <a href="#" target="" class="pf_view_cl010"><!-- icons--> </a>
                                <a href="#" target="" class="pf_view_cl011"><!-- icons--> </a>
                                <a href="#" target="" class="pf_view_cl012"><!-- icons--> </a>
                                <!--<a class="pf_view_cl013" data-profileId="" href ="javascript:void(0)"> </a>-->
                                <a href="#" class="pf_profile_cl013"><!-- icons--> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- outer container ends-->
                <div class="norecords">No Families Found</div>
                <div class="clear_both"></div>
                <div class="loadmore_wrapper"><div class="loadmore">Load More</div></div>
            </div>
        </div>
        <div class="clear_both"></div>
        <div id="data_container_test" style="width:100%">
            <div id="error" class="errormessage"></div>
        </div>
    </div>
</div>
<div class="clear_both"></div>
<bx_include_auto:_resp_sub_footer.html />
