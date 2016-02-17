<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
function setMatchVariables($prfType){
    $result = mysql_query("SELECT * FROM matchfileds WHERE ProfileType= $prfType");
    if(mysql_numrows($result) > 0)
    {
        while($row = mysql_fetch_array($result))
        {
            $matchtempArray                  = "";
            $matchtempArray['fieldName']     = $row['fieldName'];
            $matchtempArray['percentage']    = $row['percentage'];
            $matchtempArray['columnName']    = $row['columnName'];
            $matchtempArray['dependency']    = $row['dependency'];
            $matchtempArray['fieldid']       = $row['id'];
            $MatchVariables[]                = $matchtempArray;

        }
        mysql_free_result($result);
        return $MatchVariables;
    }
}
function setMtachSection($prfType){
    $result = mysql_query("SELECT * FROM matchfileds m WHERE m.ProfileType = $prfType ORDER BY m.Order");
    if(mysql_numrows($result) > 0)
    {
        while($row = mysql_fetch_assoc($result))
        {
            $matchtempArray                  = "";
            foreach($row as $key=>$value)
            {
                $matchtempArray[$key]     = $value;
            }
            $MatchVariables[]                = $matchtempArray;

        }
        mysql_free_result($result);
        return $MatchVariables;
    }
}

function acceptAllCheck($chkVal)
{
    $accepteAllValues   = array("Not Specified","Open");
    foreach($accepteAllValues as $accValue)
    {
        $acceptalllValue = trim(strtolower($accValue));
    if(!is_array($chkVal))
    {        
        $chkVal = trim(strtolower($chkVal));
        if($chkVal == $acceptalllValue)
            $chkStaus = 1;
        else
            $chkStaus = 0;
    }
    else
    {
         $lower_case_array = unserialize(strtolower(serialize($chkVal)));
         $chkStaus         = in_array($acceptalllValue,$lower_case_array)?1:0;        
        }

        if($chkStaus)
            break;
    }
    return $chkStaus;
}

function fetchMtachUsers($matchoptions,$pid)
{
    //$pid = getLoggedId();
    $_aInfo                 = getProfileInfo($pid);
    $ptype                  = $_aInfo['ProfileType'];
    $prfType                = $ptype;
    $MatchVariables         = setMatchVariables($matchoptions['profileType']);
    if($matchoptions['profileType'] == 2)
    {
        $childMatchVariables          = setMatchVariables(6);
        $MatchVariables               = array_merge($MatchVariables, $childMatchVariables);
    }

    $parentuserDetails      = getuserDetails($pid);
    ///print_r($parentuserDetails);
    $agency_ID              = $parentuserDetails['agencyID'];

    //echo "agency ID ".$agency_ID."<br/>";
    //print_r($MatchVariables);
    //setMatchVariables
    $person_sql = "SELECT p.ID,
    p.Status,
    p.Role,
    p.Couple,
    p.Sex,
    p.LookingFor,
    p.Country,
    p.City,
    p.DateOfBirth,
    p.Rate,
    p.RateCount,
    p.Income,
    p.Occupation,
    p.Religion,
    p.Education,
    p.RelationshipStatus,
    p.Ethnicity,
    p.ProfileType,
    p.Region,
    p.ChildAge,
    p.FamilyStructure,
    p.ChildGender,
    p.ChildEthnicity,
    p.Residency,
    p.State,
    p.Avatar,
    p.Smoking,
    p.BMChildEthnicity,
    p.BMChildDOB,
    p.BMChildSex,
    p.BirthFatherStatus,
    p.DrugsAlcohol,
    p.SmokingDuringPregnancy,
    p.BPFamilyHistory,
    p.FirstName,
    p.LastName,
    p.Pet,
    p.Stayathome,
    p.Reason,
    p.Adoptiontype,
    TIMESTAMPDIFF(YEAR, p.DateOfBirth, CURDATE()) AS age,
    TIMESTAMPDIFF(DAY, p.DateReg, CURDATE()) AS daysWaiting,
    p.Druguse,
    p.Drinking,
    p.Familyhistory,
    p.Conception,
    p.DueDate,
    p.Specialneeds,
    p.noofchildren,
    (SELECT REPLACE(LKey, '_', '') FROM sys_pre_values S where S.Key ='Country' AND S.Value = p.Country) AS parentCountry,
    ag.title AS Agency
    FROM
    Profiles p
    LEFT JOIN bx_groups_main ag ON (ag.id  = p.AdoptionAgency)";
    $person_where  = "";
    //print_r($matchoptions);
    //echo "<br/><br/>";
    foreach($MatchVariables AS $matchDetails)
    {
        $pref_split_qry               = split(',',$matchoptions[$matchDetails['fieldName']]);
        if(count($pref_split_qry) > 1)
            $preference_Value_qry     = $pref_split_qry;
        else
            $preference_Value_qry     = $pref_split_qry[0];
        //print_r($matchDetails);
        //echo "<br/><br/>";
        //echo "column name".$matchDetails['fieldName']."  ---  optional value ".$preference_Value_qry."<br/>";
        if($matchoptions[$matchDetails['fieldName']])
        {
            //echo "inside***********************************";
            $max_match_option = $max_match_option + $matchDetails['percentage'];
            //echo $matchDetails['fieldName']."       ";print_r($preference_Value_qry);echo "<br/>";
            //parents age check
            if($matchDetails['fieldName'] == 'age')
            {
                //print_r($preference_Value_qry);
                if(is_array($preference_Value_qry))
                {
                    $ageArray     = $preference_Value_qry;
                    foreach($ageArray as $ageSelect)
                    {
                        list($minage,$maxage) = split("-",$ageSelect);
                        //echo $minage." **** ".$maxage;
                        if($maxage)
                        {
                            $ageCondition = "TIMESTAMPDIFF(YEAR, ".$matchDetails['columnName'].", CURDATE()) BETWEEN $minage AND $maxage ";
                            if($person_where)
                                $person_where   .= " OR ".$ageCondition;
                            else
                                $person_where   = " WHERE (".$ageCondition;
                        }
                        else
                        {
                            $minage  = 50;
                            if($person_where)
                                $person_where   .= " OR TIMESTAMPDIFF(YEAR, ".$matchDetails['columnName'].", CURDATE()) < ".$minage;
                            else
                                $person_where   = " WHERE (TIMESTAMPDIFF(YEAR, ".$matchDetails['columnName'].", CURDATE()) < ".$minage;
                        }
                    }
                }
            }
            //child age check
            else if($matchDetails['fieldName'] == 'Age')
            {
                //echo "aaa".$preference_Value_qry;
                $multiAgePref     = $preference_Value_qry;
                if(!is_array($multiAgePref))
                {
                    if($multiAgePref)
                    {
                            $ageRange   = childAgeRange($multiAgePref);
                            //echo "age range ".$ageRange."<br/>";
                            list($minAgeRange,$maxAgeRange)  = split('/',$ageRange);
                            if($maxAgeRange)
                            {
                                if($person_where)
                                    $person_where   .= " OR ".$matchDetails['columnName']." between '$maxAgeRange'and '$minAgeRange'";
                                else
                                    $person_where   .= " WHERE (".$matchDetails['columnName']." between '$maxAgeRange'and '$minAgeRange'";
                            }
                            else
                            {
                                if($person_where)
                                    $person_where   .= " OR ".$matchDetails['columnName']." <'$minAgeRange'";
                                else
                                    $person_where   .= " WHERE (".$matchDetails['columnName']." < '$minAgeRange'";
                            }
                    }
                }
                else
                {
                foreach($multiAgePref as $agePref)
                {
                    if($agePref)
                    {
                        $ageRange   = childAgeRange($agePref);
                        //echo "age range ".$ageRange."<br/>";
                        list($minAgeRange,$maxAgeRange)  = split('/',$ageRange);
                        if($maxAgeRange)
                        {
                            if($person_where)
                                $person_where   .= " OR ".$matchDetails['columnName']." between '$maxAgeRange'and '$minAgeRange'";
                            else
                                $person_where   .= " WHERE (".$matchDetails['columnName']." between '$maxAgeRange'and '$minAgeRange'";
                        }
                        else
                        {
                            if($person_where)
                                $person_where   .= " OR ".$matchDetails['columnName']." <'$minAgeRange'";
                            else
                                $person_where   .= " WHERE (".$matchDetails['columnName']." < '$minAgeRange'";
                        }
                    }
                }
            }
            }
            else
            {
                //echo "Field name ".$matchDetails['fieldName']."<br/>";
                if(!is_array($preference_Value_qry))
                {
                    $acceptAllCheck   = acceptAllCheck($preference_Value_qry);
                    if($acceptAllCheck)
                    {
                if($person_where)
                            $person_where   .= " OR ".$matchDetails['columnName']."  IS NOT NULL";
                        else
                            $person_where   = " WHERE (".$matchDetails['columnName']." IS NOT NULL";
                    }
                    else
                    {
                        if($person_where)
                    $person_where   .= " OR ".$matchDetails['columnName']."  LIKE '".$preference_Value_qry."'";
                else
                    $person_where   = " WHERE (".$matchDetails['columnName']." LIKE '".$preference_Value_qry."'";
                }
                }
                else
                {
                    $multiArray     = $preference_Value_qry;
                    foreach($multiArray as $multi)
                    {
                        $acceptAllCheck   = acceptAllCheck($multi);
                        if($acceptAllCheck)
                        {
                         if($person_where)
                                $person_where   .= " OR ".$matchDetails['columnName']."  IS NOT NULL";
                            else
                                $person_where   = " WHERE (".$matchDetails['columnName']." IS NOT NULL";
                        }
                        else
                        {
                         if($person_where)
                            $person_where   .= " OR ".$matchDetails['columnName']."  LIKE '".$multi."'";
                        else
                            $person_where   = " WHERE (".$matchDetails['columnName']." LIKE '".$multi."'";
                    }
                    }

                }
            }
        }
    }
    if($prfType == 4)
        $otherprfType = 2;
    else if($prfType == 2)
        $otherprfType = 4;

    $globalMatchFlag  = ($parentuserDetails['globalFlag'] == 'Yes')?0:1;
    /*if($globalMatchFlag)
    {
        $agencyWhere  = " AND p.AdoptionAgency = $agency_ID ";
    }
    else
    {*/
        $agencyWhere = " AND ((p.globalval ='Yes' AND p.AdoptionAgency != $agency_ID) || p.AdoptionAgency = $agency_ID)";
    //}
    //echo "agency where ".$agencyWhere."<br/>";
    if($person_where)
        $person_where  .= ") AND p.Status = 'Active' AND p.ProfileType= $otherprfType".$agencyWhere;
    else
        $person_where  = " WHERE p.Status = 'Active' AND p.ProfileType= $otherprfType".$agencyWhere;
    $sql_match_query    = $person_sql.$person_where;
    //echo $sql_match_query."<br/>";

    $result_person = mysql_query($sql_match_query);
    if(mysql_numrows($result_person) > 0)
    {
        while($row_person = mysql_fetch_assoc($result_person))
        {
            $row_person_array[]        = $row_person;
            $max_match          = 0;
            $max_match_option   = 0;
            //print_r($MatchVariables);
            foreach($MatchVariables AS $matchDetails)
            {
                $pref_split               = split(',',$matchoptions[$matchDetails['fieldName']]);
                if(count($pref_split) > 1)
                    $preference_Value     = $pref_split;
                else
                    $preference_Value     = $pref_split[0];
                //echo "fieldName ".$matchDetails['fieldName']." -- ";
                //echo "match field ".$matchDetails['fieldName']." -- match value" .$preference_Value." -- org value".$row_person[$matchDetails['fieldName']]. "<br/>";
                if($matchoptions[$matchDetails['fieldName']])
                {
                    $max_match_option = $max_match_option + $matchDetails['percentage'];
                    //dependency match check initial
                    if($matchDetails['dependency'])
                    {
                        $dependancyField        = getmatchDependency($matchDetails['fieldid']);
                        //inital match
                        $dependantMatchFlag     = dependantMatch($dependancyField,$pid,$row_person['ID'],$preference_Value,$row_person[$matchDetails['columnName']]);
                        //echo " dependant match flag  ".$dependantMatchFlag."<br/><br/>";
                        if($dependantMatchFlag)
                            $max_match   = $max_match  + $matchDetails['percentage'];
                    }
                    //parents age match check
                    else if($matchDetails['fieldName'] == 'age')
                    {
                        $acceptAllCheck   = acceptAllCheck($preference_Value);
                        if($acceptAllCheck)
                        {
                             $max_match   = $max_match  + $matchDetails['percentage'];
                        }
                        else
                        {
                        $ageArray       = $preference_Value;
                        list($parentyear,$parentmonth) = split('/',birthday($row_person[$matchDetails['columnName']]));
                        if($row_person[$matchDetails['columnName']])
                            $parentageFlag  = parentAgeMatcher($parentyear);
                        else
                            $parentageFlag  = "";
                        if(!is_array($ageArray))
                        {
                            if($parentageFlag == $ageArray)
                            {
                                $max_match   = $max_match  + $matchDetails['percentage'];
                            }
                        }
                        else
                        {
                            foreach($ageArray as $ageSelect)
                            {
                                if($parentageFlag == $ageSelect)
                                {
                                    $max_match   = $max_match  + $matchDetails['percentage'];
                                }
                            }
                        }
                    }
                    }
                    //child age match check
                    else if($matchDetails['fieldName'] == 'Age')
                    {
                        $acceptAllCheck   = acceptAllCheck($preference_Value);
                        if($acceptAllCheck)
                        {
                             $max_match   = $max_match  + $matchDetails['percentage'];
                        }
                        else
                        {
                        $ageArray       = $preference_Value;
                        if($row_person[$matchDetails['columnName']])
                            $childageFlag  = childAgeMatcher(birthday($row_person[$matchDetails['columnName']]));
                        else
                            $childageFlag  = "";
                        //echo $row_person[$matchDetails['columnName']]." childage flag ".$childageFlag."<br/>";
                        if(!is_array($ageArray))
                        {
                             if($childageFlag == $preference_Value)
                            {
                                $max_match   = $max_match  + $matchDetails['percentage'];
                            }
                        }
                        else
                        {
                            foreach($ageArray as $ageSelect)
                            {
                                if($childageFlag == $ageSelect)
                                {
                                    $max_match   = $max_match  + $matchDetails['percentage'];
                                }
                            }
                        }
                    }
                    }                    
                    else if($matchDetails['fieldName'] == 'Smoking')
                    {                        
                        if(!is_array($preference_Value))
                        {
                            //if($row_person[$matchDetails['columnName']] == $preference_Value)
                            $preference_Value   = ($preference_Value == 'None')?'No':'Yes';
                            if(strcasecmp($row_person[$matchDetails['columnName']], $preference_Value) == 0)
                            {
                               
                                $max_match   = $max_match  + $matchDetails['percentage'];
                            }
                        }
                    else
                        {
                            $multiArray     = $preference_Value;                            
                            foreach($multiArray as $multi)
                            {
                                $multi   = ($multi == 'None')?'No':'Yes';
                                if(strcasecmp($row_person[$matchDetails['columnName']], $multi) == 0)
                                {                                    
                                    $max_match   = $max_match  + $matchDetails['percentage'];
                                    break;
                                }

                            }
                        }
                    }
                    else if($matchDetails['fieldName'] == 'Druguse')
                    {
                       $multiFieldMatcFlag     = ChechkMultiDataValue($preference_Value,$row_person[$matchDetails['columnName']]);
                       if($multiFieldMatcFlag)
                            $max_match         = $max_match  + $matchDetails['percentage'];
                    }
                    else
                    {
                        if(($matchDetails['columnName'] == 'RelationshipType' || $matchDetails['columnName'] == 'Reason' || $matchDetails['columnName'] == 'Adoptiontype' ))
                            $userSetValue                           = getOrgOptionValue($matchDetails['columnName'],$row_person[$matchDetails['columnName']]);
                        else
                            $userSetValue                           = $row_person[$matchDetails['columnName']];
                        if(!is_array($preference_Value))
                        {
                            $acceptAllCheck   = acceptAllCheck($preference_Value);
                            if($acceptAllCheck)
                                $max_match   = $max_match  + $matchDetails['percentage'];
                            else
                            {
                            if(strcasecmp($userSetValue, $preference_Value) == 0)
                                $max_match   = $max_match  + $matchDetails['percentage'];
                            }
                        }
                        else
                        {
                            $multiArray     = $preference_Value;
                            $acceptAllCheck  = acceptAllCheck($preference_Value);
                            if($acceptAllCheck)
                                $max_match   = $max_match  + $matchDetails['percentage'];
                            else
                            {
                            foreach($multiArray as $multi)
                            {
                                if(strcasecmp($userSetValue, $multi) == 0)
                                    $max_match   = $max_match  + $matchDetails['percentage'];

                            }
                        }
                    }
                }
                }
		  else
                {
                    $max_match_option   = $max_match_option + $matchDetails['percentage'];
                    $max_match          = $max_match  + $matchDetails['percentage'];
                }
            }
            
            $matchPerc     = 0;
            if($max_match_option)
                $matchPerc     = round((intval($max_match)/intval($max_match_option))*100);
            //$matchPerc       = $max_match;
            if($matchPerc >= $matchoptions['matchpercenatage'])
            {
                $row_person['matchperc']    = $matchPerc;
                $matcheduser[]              = $row_person;
            }

        }
        mysql_free_result($result_person);
    }
    $preferenceFlag   =0;
    foreach($MatchVariables AS $matchDetails)
    {
        if($matchoptions[$matchDetails['fieldName']])
            $preferenceFlag     = 1;

    }
    if(count($matcheduser))
        $matchArray = subval_sort($matcheduser,'matchperc');
    else
        $matchArray = $matcheduser;
    //print_r($matchArray);
    //$matchArray     = array();

    if($pid && $matchArray && $preferenceFlag)
    {
        //echo " before filter count ".count($matcheduser)."<br/>";
        $reverseMatch    = reverseMatching($matchArray,$prfType,$pid);
        //echo " After filter count ".count($reverseMatch)."<br/>";
        //print_r($reverseMatch);
    }
    else if(!$preferenceFlag)
        $reverseMatch    = array();
    else
        $reverseMatch    = $matchArray;
   
    $matchMaxRecord                    = getUserMatchOptions($pid);
    if(count($reverseMatch)>$matchMaxRecord['matchrecords'])
        $reverseMatch  =  array_slice($reverseMatch, 0, $matchMaxRecord['matchrecords']);
   
    $ttlMatchArray  = "";
    $ttlMatchArray[]  = $reverseMatch;
    $ttlMatchArray[]  = $preferenceFlag;
    //print_r($ttlMatchArray);
    return $ttlMatchArray;
}

function ChechkMultiDataValue($getPreferenceValue, $actualDataValue)
{
    $multiDataFlag   = 0;
    //echo " actualDataValue ".$actualDataValue;
    $getDataValue    = split(",",$actualDataValue);
    //print_r($getPreferenceValue);
    //echo "<br/>";
    //print_r($getDataValue);
    if(!is_array($getPreferenceValue))
    {
        $acceptAllCheck   = acceptAllCheck($getPreferenceValue);
        if($acceptAllCheck)
           $multiDataFlag = 1;
        else
        {
            if(!is_array($getDataValue))
            {
                if(strcasecmp($getDataValue, $getPreferenceValue) == 0)
                   $multiDataFlag = 1;
            }
            else
            {
                foreach($getDataValue as $getData)
                {
                    if(strcasecmp($getData, $getPreferenceValue) == 0)
                        $multiDataFlag = 1;
                }
                
            }
        }
    }
    else
    {
        $multiArray      = $getPreferenceValue;
        $acceptAllCheck  = acceptAllCheck($getPreferenceValue);
        if($acceptAllCheck)
            $multiDataFlag = 1;
        else
        {
            foreach($multiArray as $multi)
            {
                if(!is_array($getDataValue))
                {
                    if(strcasecmp($getDataValue, $multi) == 0)
                       $multiDataFlag = 1;
                }
                else
                {
                    foreach($getDataValue as $getData)
                    {
                        if(strcasecmp($getData, $multi) == 0)
                            $multiDataFlag = 1;
                    }

                }

            }
        }
    }
    //echo " flag ".$multiDataFlag."<br/>";
    return $multiDataFlag;
}
function dependantMatch($dependancyField,$pref_personID,$user_personID,$preference_Value,$user_actualVal)
{
    $mt_flag    = 0;
    //echo "user actual value ".$user_actualVal."  **** preference value ".$preference_Value." *********** ";
    if($preference_Value == 'Yes')
    {
        //echo "user actual value ".$user_actualVal."  **** preference value ".$preference_Value." *********** ";
        if($user_actualVal == 'No')
        {
            $mt_flag   = 1 ;
        }
        else
        {
            //$respUserID          = $row_person['ID'];
            $chkSPNEEDDValue     = checkFieldEmpty($dependancyField['preferencecolumn'],$pref_personID);
            $chkSPNEEDPrefValue  = checkFieldEmpty($dependancyField['usercolumn'],$user_personID);
            //echo " SPNEEDprefvalue ".$chkSPNEEDDValue." ********** ";
            //echo "  SPNEEDuservalue ".$chkSPNEEDPrefValue."<br/>";
            $slcSPOPquery  = "SELECT COUNT(*) FROM Profiles p1 WHERE FIND_IN_SET('$chkSPNEEDDValue','$chkSPNEEDPrefValue') AND p1.ID =$user_personID";
            $result_SPOPT = mysql_query($slcSPOPquery);
            while($row_SPOPT = mysql_fetch_array($result_SPOPT))
            {
                $matchedFlag            = $row_SPOPT[0];
                //if($user['ID'] == 861)echo " match Flag ".$matchedFlag."<br/>";
                if($matchedFlag)
                    $mt_flag   = 1;
            }
        }
    }
    else
    {
        if(strcasecmp($user_actualVal, $preference_Value) == 0)
        {
            $mt_flag   = 1;
        }
    }
    //echo "  matchlfag ".$mt_flag."<br/>";
    //echo "<br/>";
    return $mt_flag;
}

function subval_sort($array,$subkey) {
	foreach($array as $k=>$v) {
		$b[$k] = intval($v[$subkey]);
	}
	arsort($b);
	foreach($b as $key=>$val) {
		$c[] = $array[$key];
	}
	return $c;
}

function reverseMatching($matchusers,$prfType,$pid)
{
    $setRvrsMatchFlds       = setreverseMatchfields($prfType);
    //print_r($setRvrsMatchFlds);
    //echo "<br/><br/>";
    $matchFilterArray   = array();
    foreach($matchusers as $user)
    {
        //print_r($matchusers);
        //echo "user name  ".$user['ID']." ** ".$user['LastName']."<br/>";
        $matchuserID  = $user['ID'];
        $max_match          = 0;
        $max_match_option   = 0;
        foreach($setRvrsMatchFlds as $Fields)
        {
            $dataFiled   = $Fields['datafield'];
            $prefField   = $Fields['preffield'];
            $percentVal  = $Fields['percentage'];
            $fiedIdVal   = $Fields['fieldid'];
            $fieldDepVal = $Fields['dependency'];
            $fieldfillCol = $Fields['fillColumn'];

            //echo "field name ".$dataFiled ."<br/>";
            //echo "preference name ".$prefField ."<br/>";
            //echo "percent value ".$percentVal ."<br/>";
            if($dataFiled && $prefField)
            {
                $chkuserValue  = checkFieldEmpty($dataFiled,$pid);
                $chkPrefValue  = checkFieldEmpty($prefField,$matchuserID);
                
                if($chkPrefValue)
                {
                    //echo "preference field ---".$prefField." -----  ".$chkuserValue." ------ ".$percentVal."<br/>";
                    $max_match_option = $max_match_option + $percentVal;
                    if($chkuserValue)
                    {
                        //dependency check reverse matching
                        if($fieldDepVal)
                        {
                            $dependancyField        = getmatchDependency($fiedIdVal);
                            //reverse match
                            $dependantMatchFlag     = dependantMatch($dependancyField,$matchuserID,$pid,$chkPrefValue,$chkuserValue);
                            if($dependantMatchFlag)
                               $max_match   = $max_match  + $percentVal;

                        }
                        if($prefField == 'ChildAge')
                        {
                             //$chkuserValue = "2011-05-20";
                             $chAge = birthday($chkuserValue);
                             $achAgeMathcer = childAgeMatcher($chAge);
                             //echo "Age mathcer  ".$achAgeMathcer ."<br/>";
                             $slcMatchquery  = "SELECT count(*) FROM Profiles p1 WHERE FIND_IN_SET('$achAgeMathcer','$chkPrefValue') AND p1.ID =$matchuserID";
                             $result_data = mysql_query($slcMatchquery);
                             while($row_data = mysql_fetch_array($result_data))
                             {
                                $matchedFlag            = $row_data[0];
                                if($matchedFlag)
                                    $max_match   = $max_match  + $percentVal;
                             }
                             mysql_free_result($result_data);
                        }
                        else if($prefField == 'bpage')
                        {
                            list($parentyear,$parentmonth) = split('/',birthday($chkuserValue));
                            $bpparnetAgeMathcer = parentAgeMatcher($parentyear);
                            $slcMatchquery  = "SELECT count(*) FROM Profiles p1 WHERE FIND_IN_SET('$bpparnetAgeMathcer','$chkPrefValue') AND p1.ID =$matchuserID";
                            $result_data = mysql_query($slcMatchquery);
                             //echo $slcMatchquery."<br/>";
                            while($row_data = mysql_fetch_array($result_data))
                            {
                               $matchedFlag            = $row_data[0];
                               if($matchedFlag)
                                   $max_match   = $max_match  + $percentVal;
                            }
                            mysql_free_result($result_data);
                        }
                        else if($prefField == 'DrugsAlcohol')
                        {
                           $multiFieldMatcFlag     = ChechkMultiDataValue($chkPrefValue,$chkuserValue);
                            
                           if($multiFieldMatcFlag)
                               $max_match   = $max_match  + $percentVal;
                        }
                                else
                        {
                            if(($prefField == 'bprelationtype' || $prefField == 'bpreason' || $prefField == 'bpadoption' ))
                                $chkuserValue         = getOrgOptionValue($fieldfillCol,$chkuserValue);
                        else
                                $chkuserValue         = $chkuserValue;                            
                            //echo "column name ".$prefField."<br/>";
                            //echo "user value ".$chkuserValue."<br/>";
                            //echo "preference value ".$chkPrefValue."<br/>";
                            //echo "<br/>*************************************************************<br/>";                            
                            $slcMatchquery  = "SELECT count(*) FROM Profiles p1 WHERE FIND_IN_SET('$chkuserValue','$chkPrefValue') AND p1.ID =$matchuserID";
                            $result_data = mysql_query($slcMatchquery);
                            while($row_data = mysql_fetch_array($result_data))
                            {
                                $matchedFlag            = $row_data[0];
                                if($matchedFlag)
                                    $max_match   = $max_match  + $percentVal;
                            }
                            mysql_free_result($result_data);
                        }
                    }

                }
            }
        }

        $matchPerc     = 0;
        if($max_match_option)
            $matchPerc     = round((intval($max_match)/intval($max_match_option))*100);
        //if($max_match_option) echo "maximum percentage ".$max_match_option."<br/>";
        //if($user['ID'] == 861)echo "match percentage ".$matchPerc."<br/>";
        if($matchPerc || (!$max_match_option))
        {
            //unset($reverseMatch[2]);
            $matchFilterArray[] = $user;
        }

    }
    return $matchFilterArray;

}

function checkFieldEmpty($fieldName,$usid){
    $sqlfield   = "SELECT p2.$fieldName FROM Profiles p2 WHERE p2.ID = $usid";
    //echo $sqlfield."<br/>";
    $result = mysql_query($sqlfield);
    while($row = mysql_fetch_array($result))
    {
        //print_r($row);
        $fieldVal            = $row[0];
    }
    return $fieldVal;
}

function setreverseMatchfields($prfType)
{
    $fetchmatchfields  = "";
    if($prfType == 4)
    {
        $userPref  = setMtachSection(2);
        foreach($userPref as $pref)
        {
            $setfields = "";
            $setfields['datafield']  = $pref['columnName'];
            $setfields['preffield']  = $pref['prefColumn'];
            $setfields['percentage'] = $pref['percentage'];
            $setfields['dependency'] = $pref['dependency'];
            $setfields['fillColumn'] = $pref['fillColumn'];
            $setfields['fieldid']    = $pref['id'];
            $fetchmatchfields[ ]     = $setfields;
        }
        $userPref  = setMtachSection(6);
        foreach($userPref as $pref)
        {
            $setfields = "";
            $setfields['datafield']  = $pref['columnName'];
            $setfields['preffield']  = $pref['prefColumn'];
            $setfields['percentage'] = $pref['percentage'];
            $setfields['dependency'] = $pref['dependency'];
            $setfields['fillColumn'] = $pref['fillColumn'];
            $setfields['fieldid']    = $pref['id'];
            $fetchmatchfields[ ]     = $setfields;
        }
    }
    else if($prfType == 2)
    {
        $userPref  = setMtachSection(4);
        foreach($userPref as $pref)
        {
            $setfields = "";
            $setfields['datafield']  = $pref['columnName'];
            $setfields['preffield']  = $pref['prefColumn'];
            $setfields['percentage'] = $pref['percentage'];
            $setfields['dependency'] = $pref['dependency'];
            $setfields['fillColumn'] = $pref['fillColumn'];
            $setfields['fieldid']    = $pref['id'];
            $fetchmatchfields[ ]     = $setfields;
        }
    }
    return $fetchmatchfields;
}
function birthday($birthday){
    $localtime = getdate();
    $today = $localtime['mday']."-".$localtime['mon']."-".$localtime['year'];

    $dob_a = explode("-", $birthday);
    $today_a = explode("-", $today);

    $dob_d = $dob_a[2];
    $dob_m = $dob_a[1];
    $dob_y = $dob_a[0];

    $today_d = (strlen($today_a[0])==1)?"0".$today_a[0]:$today_a[0];
    $today_m = $today_a[1];
    $today_y = $today_a[2];

    $years = $today_y - $dob_y;
    $months = $today_m - $dob_m;


    if ($today_m.$today_d < $dob_m.$dob_d) {
    $years--;
    $months = 12 + $today_m - $dob_m;

    }

    if ($today_d < $dob_d) {
    $months--;
    }

    $firstMonths=array(1,3,5,7,8,10,12);
    $secondMonths=array(4,6,9,11);
    $thirdMonths=array(2);

    if($today_m - $dob_m == 1) {
        if(in_array($dob_m, $firstMonths)) {
            array_push($firstMonths, 0);
        }elseif(in_array($dob_m, $secondMonths)) {
            array_push($secondMonths, 0);
        }elseif(in_array($dob_m, $thirdMonths)) {
            array_push($thirdMonths, 0);
        }
    }

    $age  = "$years/$months ";
    return $age;
  }
function childAgeMatcher($childAge)
{
    list($year,$month) = split('/',$childAge);
    //echo "year  ".$year ."    month ".$month."<br/>";
    if($year < 0)
    {
        $ageMatchedFlag = "Newborn";
    }
    else if($year != 0)
    {
        switch ($year)
        {
            case 1:
                $ageMatchedFlag = "1 year old";
                break;
            case 2:
                $ageMatchedFlag = "2 years old";
                break;
            case 3:
                $ageMatchedFlag = "3 years old";
                break;
            case 4:
                $ageMatchedFlag = "4 years old";
                break;
            case 5:
                $ageMatchedFlag = "5 years old";
                 break;
            case 6:
                $ageMatchedFlag = "6 years old";
                break;
            case 7:
                $ageMatchedFlag = "7 years old";
                 break;
            case 8:
                $ageMatchedFlag = "8 years old";
                break;
           default:
                $ageMatchedFlag = "Over 8 years old";
                break;
        }
    }
    else
    {
        switch ($month)
        {
            case 0:
                $ageMatchedFlag = "Newborn";
                break;
            case ($month == 1 || $month == 2):
                $ageMatchedFlag = "1-2 months";
                break;
            case ($month == 3 || $month == 4):
                $ageMatchedFlag = "3-4 months";
                break;
            case ($month == 5 || $month == 6):
                $ageMatchedFlag = "5-6 months";
                break;
            case ($month == 7 || $month == 8):
                $ageMatchedFlag = "7-8 months";
                 break;
            case ($month == 9 || $month == 11):
                $ageMatchedFlag = "9-11 months";
                break;
           default:
                $ageMatchedFlag = "";
                break;
        }

    }

    return $ageMatchedFlag;

}

function childAgeRange($childPref)
{
    //echo "childPref  ".$childPref."<br/>";
    switch ($childPref)
    {
         case "Newborn":
            $agerange = datSub(0,1);
            break;
        case "1 year old":
            $agerange = datSub(12,24);
            break;
        case "2 years old":
            $agerange = datSub(25,36);
            break;
        case "3 years old":
            $agerange = datSub(37,48);
            break;
        case "4 years old":
            $agerange = datSub(49,60);
            break;
        case "5 years old":
            $agerange = datSub(61,72);
             break;
        case "6 years old":
            $agerange = datSub(73,84);
            break;
        case "7 years old":
            $agerange = datSub(85,96);
             break;
        case "8 years old":
            $agerange = datSub(97,108);
            break;
        case "1-2 months":
            $agerange = datSub(1,2);
            break;
        case "3-4 months":
            $agerange = datSub(3,4);
            break;
        case "5-6 months":
            $agerange = datSub(5,6);
            break;
        case "7-8 months":
            $agerange = datSub(7,8);
             break;
        case "9-11 months":
            $agerange = datSub(9,11);
            break;
       default:
            $agerange = datSub(109);
            break;
    }
    return $agerange;
}

function datSub($min,$max=NULL)
{
    $date           = date('Y-m-j');
    $mindaterange   = strtotime ( '-'.$min.' month' , strtotime ( $date ));
    $mindaterange   = date ( 'Y-m-j' , $mindaterange );
    if($max)
    {
        $maxdaterange   = strtotime ( '-'.$max.' month' , strtotime ( $date ) ) ;
        $maxdaterange   = date ( 'Y-m-j' , $maxdaterange );
    }

    return $mindaterange."/".$maxdaterange;

}

function parentAgeMatcher($parentAge)
{
    //echo "parentage  ".$parentAge ."    <br/>";
    switch ($parentAge)
    {
        case ($parentAge >14 && $parentAge <21):
            $parentageMatchedFlag = "15-20";
            break;
        case ($parentAge >20 && $parentAge <26):
            $parentageMatchedFlag = "21-25";
            break;
        case ($parentAge >25 && $parentAge <31):
            $parentageMatchedFlag = "26-30";
            break;
        case ($parentAge >30 && $parentAge <36):
            $parentageMatchedFlag = "31-35";
            break;
        case ($parentAge >35 && $parentAge <41):
            $parentageMatchedFlag = "36-40";
             break;
        case ($parentAge >40 && $parentAge <46):
            $parentageMatchedFlag = "41-45";
            break;
        case ($parentAge >45 && $parentAge <51):
            $parentageMatchedFlag = "46-50";
             break;
       default:
            $parentageMatchedFlag = "Above 50";
            break;
    }


    return $parentageMatchedFlag;

}

// Not used
function fetchCurrentuserPreference($pid,$prfType)
{
    $fetchPref  = "";
    if($prfType == 4)
    {
        $userPref  = setMtachSection(2);
        $selqueryfileds = "";
        foreach($userPref as $pref)
        {
            if($pref['columnName'])
                $selqueryfileds .= $pref['columnName'].',';
        }
        $userPref  = setMtachSection(6);
        foreach($userPref as $pref)
        {
            if($pref['columnName'])
                $selqueryfileds .= $pref['columnName'].',';
        }
        $selqueryfileds =  substr($selqueryfileds, 0, strlen($selqueryfileds)-1);
        $fetchPref = "SELECT ".$selqueryfileds." from Profiles where id= $pid;";
    }
    else if($prfType == 2)
    {
        $userPref  = setMtachSection(4);
        $selqueryfileds = "";
        foreach($userPref as $pref)
        {
            if($pref['columnName'])
                $selqueryfileds .= $pref['columnName'].',';
        }
        $selqueryfileds =  substr($selqueryfileds, 0, strlen($selqueryfileds)-1);
        $fetchPref = "SELECT ".$selqueryfileds." from Profiles where id= $pid;";
    }
    if($fetchPref)
    {
        $result = mysql_query($fetchPref);
        if(mysql_numrows($result) > 0)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $userPref                = $row;

            }
            mysql_free_result($result);
        }
    }
    return $userPref;
}
// Not used
function fetchMatchuserPreference($matchuserID,$prfType)
{
    $fetchmatchPref  = "";
    if($prfType == 4)
    {
        $userPref  = setMtachSection(2);
        $selqueryfileds = "";
        foreach($userPref as $pref)
        {
            if($pref['prefColumn'])
                $selqueryfileds .= $pref['prefColumn'].',';
        }
        $userPref  = setMtachSection(6);
        foreach($userPref as $pref)
        {
            if($pref['prefColumn'])
                $selqueryfileds .= $pref['prefColumn'].',';
        }
        $selqueryfileds =  substr($selqueryfileds, 0, strlen($selqueryfileds)-1);
        $fetchmatchPref = "SELECT ".$selqueryfileds." from Profiles where id= $matchuserID;";
    }
    else if($prfType == 2)
    {
        $userPref  = setMtachSection(4);
        $selqueryfileds = "";
        foreach($userPref as $pref)
        {
            if($pref['prefColumn'])
                $selqueryfileds .= $pref['prefColumn'].',';
        }
        $selqueryfileds =  substr($selqueryfileds, 0, strlen($selqueryfileds)-1);
        $fetchmatchPref = "SELECT ".$selqueryfileds." from Profiles where id= $matchuserID;";
    }
    //echo $fetchmatchPref;
    if($fetchmatchPref)
    {
        $result = mysql_query($fetchmatchPref);
        if(mysql_numrows($result) > 0)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $userMatchPref           = $row;

            }
            mysql_free_result($result);
        }
    }
    return $userMatchPref;
}

function getPrefFieldValue($profileID,$prefCol)
{
	if($prefCol)
	{
		$sqlQuery   = "SELECT p.$prefCol FROM Profiles p WHERE p.ID= $profileID";
		//echo "query ".$sqlQuery."<br/>";
		$result     = mysql_query($sqlQuery);
        //print_r($result);
		if(mysql_numrows($result) > 0)
		{
			while($row = mysql_fetch_array($result))
			{
				$PrefVal     = $row[0];
			}
		}
		mysql_free_result($result);
	}
    return $PrefVal;

}

function getmatchDependency($matchfieldid)
{
    $sqlQuery   = "SELECT md.`id`,md.`matchfieldid`,md.`usercolumn`,md.`preferencecolumn` FROM `matchdependency` md
    WHERE md.`matchfieldid`= $matchfieldid";
    //echo "query ".$sqlQuery."<br/>";
    $result     = mysql_query($sqlQuery);
    if(mysql_numrows($result) > 0)
    {
        while($row = mysql_fetch_assoc($result))
        {
            //print_r($row);
            $fieldVal     = $row;

        }
    }
    return $fieldVal;
}

function getuserDetails($profileID)
{
    $sqlQuery   = "SELECT p.Status,
    p.Role,
    p.FirstName,
    p.LastName,
    p.Avatar,
    TIMESTAMPDIFF(DAY, p.DateReg, CURDATE()) AS daysWaiting,
    p.AdoptionAgency AS agencyID,
    p.globalval AS globalFlag,
    p.NickName AS NickName,
    ag.title AS Agency
    FROM Profiles p
    LEFT JOIN bx_groups_main ag ON (ag.id  = p.AdoptionAgency)
    WHERE p.ID= $profileID";
    //echo "query ".$sqlQuery."<br/>";
    $result     = mysql_query($sqlQuery);
    if(mysql_numrows($result) > 0)
    {
        while($row = mysql_fetch_assoc($result))
        {
            //print_r($row);
            $userVal     = $row;

        }
    }
    mysql_free_result($result);
    return $userVal;
}
function getUserMatchOptions($profileID)
{
    $sqlQuery   = "SELECT p.Status,
    p.maxmatch,
    p.FirstName,
    p.LastName,
    p.matchrecords
    FROM Profiles p
    WHERE p.ID= $profileID";
    //echo "query ".$sqlQuery."<br/>";
    $result     = mysql_query($sqlQuery);
    if(mysql_numrows($result) > 0)
    {
        while($row = mysql_fetch_assoc($result))
        {
            $matchOptl     = $row;
        }
    }
    mysql_free_result($result);
    return $matchOptl;
}
//fetch user preference value for matching
function getuserPreferenceDetails($prfType,$pid)
{
    $preference_post                   = setMtachSection($prfType);
    $matchoptions                      = "";
    foreach($preference_post as $pref)
    {
         $prefFieldValue                                 = getPrefFieldValue($pid,$pref['prefColumn']);
         $matchoptions[$pref['fieldName']]               = $prefFieldValue;
    }
    if($prfType == 2)
    {
        $preference_post                   = setMtachSection(6);
        foreach($preference_post as $pref)
        {
             $prefFieldValue                                 = getPrefFieldValue($pid,$pref['prefColumn']);
             $matchoptions[$pref['fieldName']]               = $prefFieldValue;
        }
    }
    $matchOptionDet                    = getUserMatchOptions($pid);
    $matchoptions['matchpercenatage']  = ($matchOptionDet['maxmatch'])?$matchOptionDet['maxmatch']:0;
    $matchoptions['maxmatchrecords']   = ($matchOptionDet['matchrecords'])?$matchOptionDet['matchrecords']:50;
    $matchoptions['profileType']       = $prfType;
    return $matchoptions;
}
//Fetch user details value for matching
function getUserDataValues($userdetType,$pid)
{
    //echo "***********************************$pid****************************************<br/>";
    $preference_post                   = setMtachSection($userdetType);
    $userChar                          = "";
    foreach($preference_post as $pref)
    {
         //echo $pref['columnName']." , ";
         $userOrgValue                            = "";
         $prefFieldValue                             = getPrefFieldValue($pid,$pref['columnName']);
         if(($pref['fillColumn'] == 'RelationshipType' || $pref['fillColumn'] == 'Reason' || $pref['fillColumn'] == 'Adoptiontype' ))
             $userOrgValue                           = getOrgOptionValue($pref['fillColumn'],$prefFieldValue);
         $userChar[$pref['fieldName']]               = ($userOrgValue)?$userOrgValue:$prefFieldValue;
    }
    if($userdetType == 2)
    {
        $preference_post                   = setMtachSection(6);
        foreach($preference_post as $pref)
        {
             //echo $pref['columnName']." , ";
             $userOrgValue                            = "";
             $prefFieldValue                          = getPrefFieldValue($pid,$pref['columnName']);
             if(($pref['fillColumn'] == 'RelationshipType' || $pref['fillColumn'] == 'Reason' || $pref['fillColumn'] == 'Adoptiontype' ))
                $userOrgValue                         = getOrgOptionValue($pref['fillColumn'],$prefFieldValue);
             $userChar[$pref['fieldName']]            = ($userOrgValue)?$userOrgValue:$prefFieldValue;
        }
    }
    //echo "******************************$pid*********************************************<br/>";
    return $userChar;
}

function getOrgOptionValue($fillColumn,$colVlal)
{
    $sqlQuery   = "SELECT sp.LKey  FROM sys_pre_values sp WHERE sp.Key = '$fillColumn' AND sp.Value = '$colVlal' ";
    $result     = mysql_query($sqlQuery);
    if(mysql_numrows($result) > 0)
    {
        while($row = mysql_fetch_array($result))
        {
            $OrgColVal     = $row[0];
        }
    }
    mysql_free_result($result);
    return $OrgColVal;
}
function fetchIndividualmatches($parentType,$parentID,$matchParentID)
{
     $preference_post           = setMtachSection($parentType);
     //print_r($preference_post);
     if($parentType == 2)
     {
        $preference_child       = setMtachSection(6);
        foreach($preference_child as $prefChild)
            array_push($preference_post, $prefChild);
     }
     //echo "<br/><br/>";
     //print_r($preference_post);
     $max_match_option          = 0;
     $max_match                 = 0;
     $match_field_array         = array();
     foreach($preference_post as $pref)
     {
         //print_r($pref);
         $match_flag            = 0;
         //echo " field name..........".$pref['fieldName']." ********** column name..........".$pref['columnName']." ********** preference column name..........".$pref['prefColumn']."<br/>";
         $preferenceValue   = getPrefFieldValue($parentID,$pref['prefColumn']);
         $prefsplit         = split(',',$preferenceValue);
         if(count($prefsplit) > 1)
             $prefValue     = $prefsplit;
         else
             $prefValue     = $prefsplit[0];
         $userValue         = getPrefFieldValue($matchParentID,$pref['columnName']);
         if(($pref['fillColumn'] == 'RelationshipType' || $pref['fillColumn'] == 'Reason' || $pref['fillColumn'] == 'Adoptiontype' ))
             $userValue                           = getOrgOptionValue($pref['fillColumn'],$userValue);

         //Matching logic
         if($preferenceValue)
         {
            $max_match_option = $max_match_option + $pref['percentage'];
            //echo "filed name ".$pref['fieldName']." columnName ".$pref['columnName']."<br/>";
            //individual dependancy match check
            if($pref['dependency'])
            {
                //echo $matchDetails['fieldName']."<br/>";
                $dependancyField        = getmatchDependency($pref['id']);
                $user_Value             = getPrefFieldValue($matchParentID,$pref['columnName']);
                //inital match
                $dependantMatchFlag     = dependantMatch($dependancyField,$matchParentID,$parentID,$prefValue,$user_Value);
                //echo " dependant match flag  ".$dependantMatchFlag."<br/><br/>";
                if($dependantMatchFlag)
                {
                    $max_match          = $max_match  + $pref['percentage'];
                    $match_flag         = 1;
                }
                $match_field_array[$pref['fieldName']] = $match_flag;

            }
            //parents age match check
            else if($pref['fieldName'] == 'age')
            {
                $acceptAllCheck  = acceptAllCheck($prefValue);
                if($acceptAllCheck)
                {
                    $max_match   = $max_match  + $pref['percentage'];
                    $match_flag  = 1;
                    $match_field_array[$pref['fieldName']] = $match_flag;
                }
                else
                {
                $ageArray       = $prefValue;
                list($parentyear,$parentmonth) = split('/',birthday($userValue));
                if($userValue)
                    $parentageFlag  = parentAgeMatcher($parentyear);
                else
                    $parentageFlag  = "";
                //echo $row_person[$matchDetails['fieldName']]." parentage flag ".$parentageFlag."<br/>";
                if(!is_array($ageArray))
                {
                     if($parentageFlag == $ageArray)
                     {
                        $max_match   = $max_match  + $pref['percentage'];
                        $match_flag  = 1;
                     }
                    $match_field_array[$pref['fieldName']] = $match_flag;
                }
                else
                {
                    foreach($ageArray as $ageSelect)
                    {
                        if($parentageFlag == $ageSelect)
                        {
                            $max_match   = $max_match  + $pref['percentage'];
                            $match_flag  = 1;
                        }
                        //echo " age selected range values ".$ageSelect."<br/>";
                    }
                    $match_field_array[$pref['fieldName']] = $match_flag;
                }
            }
            }
            //child age match check
            else if($pref['fieldName'] == 'Age')
            {
                $acceptAllCheck  = acceptAllCheck($prefValue);
                if($acceptAllCheck)
                {
                    $max_match   = $max_match  + $pref['percentage'];
                    $match_flag  = 1;
                    $match_field_array[$pref['fieldName']] = $match_flag;
                }
                else
                {
                $ageArray       = $prefValue;
                if($userValue)
                    $childageFlag  = childAgeMatcher(birthday($userValue));
                else
                    $childageFlag  = "";
                //echo $row_person[$matchDetails['columnName']]." childage flag ".$childageFlag."<br/>";
                if(!is_array($ageArray))
                {
                    if($childageFlag == $ageArray)
                    {
                        $max_match   = $max_match  + $pref['percentage'];
                        $match_flag  = 1;
                    }
                    $match_field_array[$pref['fieldName']] = $match_flag;
                }
                else
                {
                    foreach($ageArray as $ageSelect)
                    {
                        if($childageFlag == $ageSelect)
                        {
                            $max_match   = $max_match  + $pref['percentage'];
                            $match_flag  = 1;

                        }
                    }
                    $match_field_array[$pref['fieldName']] = $match_flag;
                }
            }
            }            
            else if($pref['fieldName'] == 'Smoking')
            {
                $acceptAllCheck  = acceptAllCheck($prefValue);
                if($acceptAllCheck)
                {
                    $max_match   = $max_match  + $pref['percentage'];
                    $match_flag  = 1;
                }
                else
                {
                if(!is_array($prefValue))
                {
                    $prefValue   = ($prefValue == 'None')?'No':'Yes';
                    if(strcasecmp($prefValue, $userValue) == 0)
                    {
                        $max_match   = $max_match  + $pref['percentage'];
                        $match_flag  = 1;
                    }
                }
            else
                {
                    $multiArray     = $prefValue;
                    foreach($multiArray as $multi)
                    {
                        $multi   = ($multi == 'None')?'No':'Yes';
                        if(strcasecmp($multi, $userValue) == 0)
                        {
                            $max_match   = $max_match  + $pref['percentage'];
                            $match_flag  = 1;
                            break;
                        }

                        }
                    }
                }
                $match_field_array[$pref['fieldName']] = $match_flag;
            }
            else if($pref['fieldName'] == 'Druguse' || $pref['fieldName'] == 'DrugsAlcohol' )
            {
               $multiFieldMatcFlag     = ChechkMultiDataValue($prefValue,$userValue);

               if($multiFieldMatcFlag)
               {
                   
                   $max_match          = $max_match  + $pref['percentage'];
                   $match_flag         = 1;
                }
                $match_field_array[$pref['fieldName']] = $match_flag;
            }
            else
            {
                $acceptAllCheck  = acceptAllCheck($prefValue);
                if($acceptAllCheck)
                {
                    $max_match   = $max_match  + $pref['percentage'];
                    $match_flag  = 1;
                }
                else
                {
                if(!is_array($prefValue))
                {
                    if(strcasecmp($prefValue, $userValue) == 0)
                    {
                        $max_match   = $max_match  + $pref['percentage'];
                        $match_flag  = 1;
                    }
                }
                else
                {
                    $multiArray     = $prefValue;
                    foreach($multiArray as $multi)
                    {
                        if(strcasecmp($multi, $userValue) == 0)
                        {
                            $max_match   = $max_match  + $pref['percentage'];
                            $match_flag  = 1;
                        }

                        }
                    }
                }
                $match_field_array[$pref['fieldName']] = $match_flag;
            }
         }
         else
         {
             $max_match_option = $max_match_option + $pref['percentage'];
             $match_field_array[$pref['fieldName']] = 2;
             $max_match          = $max_match  + $pref['percentage'];
         }
         //echo "---------------------------------------------------------------------------------------------------------------------<br/>";
     }
     $matchPerc     = 0;
     //echo "max match ".$max_match_option."<br/>";
     //echo "match val ".$max_match."<br/>";
     if($max_match_option)
        $matchPerc     = round((intval($max_match)/intval($max_match_option))*100);
     $match_field_array['Percentage']   = $matchPerc;
     //print_r($match_field_array);
     //echo "<br/>";
     //echo "match percentage ".$matchPerc;
     //echo "*************************************************************************************************************************************************";
     return $match_field_array;
}

function getMatchImageFlag($matchFlag,$fieldName=NULL,$fieldValue=NULL)
{
    if(!$fieldValue)
        $fieldValue     = 'NA';
    if($fieldName == 'Parents Age' || $fieldName =='Child Age')
    {
       $parentFieldValue   = "DOB - ".$fieldValue.", Age - ".calc_age($fieldValue);
    }
    else
        $parentFieldValue   = $fieldName." - ".$fieldValue;
    if($matchFlag == 1  || $matchFlag== 2)
        $imageVar  = "<img src='Matching/images/green_round.png' width='15' height='15' />";
    else if($matchFlag == 0)
        $imageVar  = "<img alt='".$parentFieldValue."' title='".$parentFieldValue."' src='Matching/images/red_round.png' width='15' height='15' />";
    //else if($matchFlag == 2)
    //    $imageVar  = "<img alt='Matching' src='Matching/images/blue_round.png' width='15' height='15' />";
    return $imageVar;

}

function getCoupleDetails($profileID)
{
     $sqlCoupleQuery   = "SELECT
                p.ID,
                pc.FirstName AS coupleFirstName,
                pc.LastName AS coupleLastName
                FROM Profiles p
                LEFT JOIN Profiles pc ON (pc.ID  = p.Couple)
                WHERE p.ID= $profileID";
    $result_couple     = mysql_query($sqlCoupleQuery);
    if(mysql_numrows($result_couple) > 0)
    {
        while($row_couple = mysql_fetch_assoc($result_couple))
        {
            $user_coupleVal     = $row_couple;
        }
    }
    mysql_free_result($result_couple);
    return $user_coupleVal;
}

//For image resolution set up in lifebook, upload photos, files etc....
function getUploadImageResolution($profileimage,$box_height,$box_width)
{
    $imgSizeArray                       = array();
    list($width, $height, $type, $attr) = getimagesize($profileimage);
    $imageWidth                         = $width;
    $imageHeight                        = $height;
    if ($imageWidth > $box_width || $imageHeight > $box_height )
    {
        if($imageHeight == $imageWidth)
        {
            $imgSizeArray['imageHeight'] 		= $box_height;
            $imgSizeArray['imageWidth'] 		= $box_height;
        }
        else if($box_height<$box_width)
        {
            $imgSizeArray['imageWidth']         = ($imageWidth * $box_height)/$imageHeight;
            $imgSizeArray['imageHeight'] 		= $box_height;
	     if($imgSizeArray['imageWidth'] > $box_width)
            {
                 $imgSizeArray['imageWidth']    = $box_width;
                 $imgSizeArray['imageHeight'] 	= ($imageHeight * $box_width)/$imageWidth;                 
            }
        }
        else
        {
            $imgSizeArray['imageHeight'] 		= ($imageHeight * $box_width)/$imageWidth;
            $imgSizeArray['imageWidth'] 		= $box_width;
	     if($imgSizeArray['imageHeight'] > $box_height)
            {
                 $imgSizeArray['imageWidth']    = ($imageWidth * $box_height)/$imageHeight;
                 $imgSizeArray['imageHeight'] 	= $box_height;                
            }
        }
    }
    else
    {
            $imgSizeArray['imageHeight'] 		= $height;
            $imgSizeArray['imageWidth'] 		= $width;
    }
    $imgSizeArray['margintop']  				= ($box_height-$imgSizeArray['imageHeight'])/2;
    $imgSizeArray['marginleft']  				= ($box_width-$imgSizeArray['imageWidth'])/2;
    $imgSizeArray['profimage']  				= $profileimage;



    return $imgSizeArray;
}

function getProfileImage($ffprofile)
{
    $sql_avt = "SELECT *  FROM `bx_avatar_images` WHERE `author_id` = '$ffprofile'";
    $result_avt = mysql_query($sql_avt);
    //echo $sql_avt;
    $aData1='';

    while($row_avt = mysql_fetch_array($result_avt))
    {
        //echo "<pre>";print_r($row_avt);echo "</pre>";
        //$filename = '/var/www/html/pf/modules/boonex/avatar/data/images/'.$row[id].'.jpg';
        $filename = 'modules/boonex/avatar/data/images/'.$row_avt[id].'.jpg';

        if (file_exists($filename)) {
            //$filename12 = '/var/www/html/pf/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';
            $filename12 = 'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';

            if (file_exists($filename12)) {
                //$aData1 = 'http://www.parentfinder.com/modules/boonex/avatar/data/avatarphotos/'.$row[author_id].'.jpg';
                $aData1 = 'modules/boonex/avatar/data/avatarphotos/'.$row_avt[author_id].'.jpg';
                break;
            }
            
        }
    }
    if(!$aData1)
    {
	 $photouri = db_arr("SELECT NickName FROM Profiles WHERE ID='$ffprofile'");

        $photourl = $photouri['NickName'];
        $photouris = $photourl.'-s-photos';

        $sqlQuery = "SELECT `bx_photos_main`.`ID` as `id`, `bx_photos_main`.`Title` as `title`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Ext` as `ext`, `bx_photos_main`.`Uri` as `uri`, `bx_photos_main`.`Date` as `date`, `bx_photos_main`.`Size` as `size`, `bx_photos_main`.`Views` as `view`, `bx_photos_main`.`Rate`, `bx_photos_main`.`RateCount`, `bx_photos_main`.`Hash`, `bx_photos_main`.`Owner` as `ownerId`, `bx_photos_main`.`ID`, `sys_albums_objects`.`id_album`, `Profiles`.`NickName` as `ownerName`, `sys_albums`.`AllowAlbumView` FROM `bx_photos_main` left JOIN `Profiles` ON `Profiles`.`ID`=`bx_photos_main`.`Owner` left JOIN `sys_albums_objects` ON `sys_albums_objects`.`id_object`=`bx_photos_main`.`ID` left JOIN `sys_albums` ON `sys_albums`.`ID`=`sys_albums_objects`.`id_album` WHERE 1 AND `bx_photos_main`.`Status` ='approved' AND `bx_photos_main`.`Owner` ='$ffprofile' AND `sys_albums`.`Status` ='active' AND `sys_albums`.`Type` ='bx_photos' AND `sys_albums`.`Uri` ='$photouris'  ORDER BY `obj_order` ASC LIMIT 1";

        $aFilesList = db_res_assoc_arr($sqlQuery);
        foreach ($aFilesList as $iKey => $aData) {
            $ext =  $aData['ext'] ;
            $sHash =  $aData['Hash'] ;
            $aData1 =  $GLOBALS['site']['url'] .'m/photos/'. 'get_image/' . 'file' .'/' . $sHash .'.'.$ext;
        }
    }
    return $aData1;
}


function calc_age($birth_date){
  if ( $birth_date == "0000-00-00" )
  return _t("_uknown");

 $bd = explode( "-", $birth_date );
 $age = date("Y") - $bd[0] - 1;

 $arr[1] = "m";
 $arr[2] = "d";

 for ( $i = 1; $arr[$i]; $i++ ) {
  $n = date( $arr[$i] );
  if ( $n < $bd[$i] )
   break;
  if ( $n > $bd[$i] ) {
   ++$age;
   break;
  }
 }
 $age_text  = $age." Years";
  return $age_text;
}
?>