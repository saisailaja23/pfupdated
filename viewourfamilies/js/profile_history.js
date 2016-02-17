$(document).ready(function() {

    defaultHistory();
    $("#filterDiv").append("<select id='filterMenu'></select>");
    $("#filterMenu").append("<option value=' '>Filter By</option>");
    $("#filterMenu").append("<option value='FirstName'>First Name</option>");
    $("#filterMenu").append("<option value='LastName'>Last Name</option>");
    $("#filterMenu").append("<option value='NickName'>User Name</option>");
    $("#filterMenu").css('width', '200px');
    $(".members-history").css('line-height', '25px');
    $("#pagination").css('float', 'right');
    $('#reload').click(function() {
        $(".pf_view_history").hide();
        $(".members-history tbody").remove();
        $(".loader").show();
        $("#searchInput").val("");
        defaultHistory();
    });


});



function defaultHistory(search_value, filter_menu, page) {
    var search_value = $("#searchInput").val();
    var filter_menu = $("#filterMenu option:selected").val();
    if (!page) page = 1;
    $.ajax({
        url: "/viewourfamilies/processors/profile-status.php",
        type: "POST",
        cache: false,
        async: true,
        data: "page=" + page + "&filter_value=" + search_value + "&filter_by=" + filter_menu,
        datatype: "json",
        success: function(data) {
            $(".loader").hide();
            $(".pf_view_history").show();
            var n = JSON.parse(data);
            $("#pagination").html(n.pages);
            $("#statusList").after(n.html);
        }
    });

}

function menuClick() {
    var search_value = $("#searchInput").val();
    var filter_menu = $("#filterMenu option:selected").val();
    if (filter_menu == '' || search_value == '') {
        alert("Please enter search values");
        return;
    }
    $(".pf_view_history").hide();
    $(".members-history tbody").remove();
    $(".loader").show();
    defaultHistory(search_value, filter_menu);

}

function pageHistoryView($page) {
    $(".pf_view_history").hide();
    $(".members-history tbody").remove();
    $(".loader").show();
    defaultHistory('', '', $page);

}