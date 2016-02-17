<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
class pagination{


    function setPaging($pageSize,$currentPage,$action,$nextPage,$previousPage,$page1,$page2,$page3,$totalItems){
    		$firstPage	= 1;
    		$lastPage	= ceil($totalItems/$pageSize);
			$page1active    = '';
			$page2active    = '';
			$page3active    = '';
			//based on the action determine the page to be displayed.
       		switch($action)
       		{
        	   case "pagesizechange":
        	   case "first":
        	   		$currentPage 		= 1;
        	   		$page1				= 1;
        	   		$page2				= 2;
        	   		$page3				= 3;
        	   		$nextPage			= 4;
					$page1active    			= 'cc_paging_c02';
        	   		break;
        	   case "previous":
        	   		$currentPage 		= $previousPage;
        	   		$page1				= $previousPage;
        	   		$page2				= $page1 + 1;
        	   		$page3				= $page2 + 1;
        	   		$previousPage      	= $previousPage - 1;
        	   		$nextPage			= $page3 + 1;
					$page1active    			= 'cc_paging_c02';
        	   	break;
        	   case "page1":
        	   		$currentPage 		= $page1;
					$page1active    			= 'cc_paging_c02';
        	   	break;
        	   case "page2":
        	   		$currentPage 		= $page2;
					$page2active    			= 'cc_paging_c02';
        	   	break;
       		   case "page3":
        	   		$currentPage 		= $page3;
					$page3active    	= 'cc_paging_c02';
        	   	break;
       		   case "next":
					$page3active    	= 'cc_paging_c02';
       		   		$currentPage 		= $nextPage;
        	   		$page3				= $nextPage;
        	   		$page2				= $page3 - 1;
        	   		$page1				= $page2 - 1;
        	   		$previousPage      	= $page1 - 1;
        	   		$nextPage			= $nextPage + 1;
       		   	break;
       		   case "last":
					$page3active    	= 'cc_paging_c02';
       		   		$currentPage 		= $lastPage;
        	   		$nextPage			= 0;
        	   		$page3				= $lastPage;
        	   		$page2				= $page3 - 1;
        	   		$page1				= $page2 - 1;
        	   		$previousPage      	= $page1 - 1;
       		   	break;
       		}

       		if ($lastPage == $page3){
        	   	$nextPage			= 0;
        	   	$lastPage			= 0;
       		}

       		if ($lastPage == $page2){
        	   	$nextPage			= 0;
        	   	$lastPage			= 0;
        	   	$page3				= 0;
       		}

       		if ($lastPage == $page1){
        	   	$nextPage			= 0;
        	   	$lastPage			= 0;
        	   	$page3				= 0;
        	   	$page2				= 0;
       		}

       		if ($page1 == 1){
        	   	$previousPage		= 0;
        	   	$firstPage			= 0;
       		}

       		$currentPageMessageOffsetStart = ($currentPage - 1) * $pageSize + 1;
       		$currentPageMessageOffsetEnd   = $currentPage  * $pageSize;
			if ($currentPageMessageOffsetEnd > $totalItems){
				$currentPageMessageOffsetEnd 	= $totalItems;
			}

			$pagingCtl				=  $pagingCtl . "<span class=\"\">Page:</span>";


			if ($firstPage			!= 0){
       			$pagingCtl			= $pagingCtl .
       								  "<span class=\"cc_paging_c01\" pagingaction=\"first\"> << </span>" ;
			}
       		if ($previousPage		!= 0){
       			$pagingCtl			= $pagingCtl .
       								  "<span class=\"cc_paging_c01\" pagingaction=\"previous\"> < </span>" ;
			}

			$pagingCtl				= $pagingCtl .
       								  "<span class=\"cc_paging_c01 $page1active\" pagingaction=\"page1\"> $page1 </span>" ;

			if ($page2				!= 0){

       			$pagingCtl			= $pagingCtl .
       								  "<span class=\"cc_paging_c01 $page2active\" pagingaction=\"page2\"> $page2 </span>" ;
			}
       		if ($page3				!= 0){
       			$pagingCtl			= $pagingCtl .
       								  "<span class=\"cc_paging_c01 $page3active\" pagingaction=\"page3\"> $page3 </span>" ;
			}
       		if ($nextPage			!= 0){
       			$pagingCtl			= $pagingCtl .
       								  "<span class=\"cc_paging_c01\" pagingaction=\"next\"> > </span>" ;
			}
       		if ($lastPage			!= 0){
       			$pagingCtl			= $pagingCtl .
       								  "<span class=\"cc_paging_c01\" pagingaction=\"last\"> >> </span>" ;
			}
     $pagination                    = "";

     $pagination['currentPage']                                  = $currentPage;
     $pagination['previousPage']                                 = $previousPage;
     $pagination['page1']                                        = $page1;
     $pagination['page2']                                        = $page2;
     $pagination['page3']                                        = $page3;
     $pagination['nextPage']                                     = $nextPage;
     $pagination['pagingCtl']                                    = $pagingCtl;
     $pagination['currentPageMessageOffsetStart']                = $currentPageMessageOffsetStart;
     $pagination['currentPageMessageOffsetEnd']                  = $currentPageMessageOffsetEnd;
     $pagination['totalItems']                                   = $totalItems;
     $pagination['pageSize']                                     = $pageSize;

     return $pagination;
    }
}

?>