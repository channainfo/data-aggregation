<?php    
  return array(
    "tables" => array(
       'tblaiarvtreatment' => array( 'clinicid','detaildrugtreat','clinic','startdate','stopdate','reasonstop','id' ) ,
		 'tblaicotrimo' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
		 'tblaidrugallergy' => array( 'clinicid','drugallergy','allergy','dat','id' ) ,
		 'tblaifamily' => array( 'clinicid','relativespopart','age','hivstatus','status','mother','child','arv','oiart','receiarv','hostorytb','id' ) ,
		 'tblaifluconazole' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
		 'tblaiisoniazid' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
		 'tblaimain' => array( 'clinicid','datefirstvisit','age','sex','house','street','grou','village','commune','district','province','phone','namecontps1','contaddress1','contphone1','namecontps2','contaddress2','contphone2','maritalstatus','occupation','education','rea','writ','referredfrom','namelocationhbc','dateconfposhiv','datelastneghiv','offyesno','offtransferin','datestaart','artnumber','tbpasmedihis','gravida','para','dailyalcohol','tabacco','idu','yama','previousarv','precontrimoxazole','prefluconzazole','preisoniazid','pretranditional','drugallergy','vct','pclinicid','id' ) ,
		 'tblaiothermedical' => array( 'clinicid','detaildrugtreat','clinic','startdate','stopdate','reasonstop','id' ) ,
		 'tblaiothpasmedical' => array( 'clinicid','hivrelatill','dateon','othnothiv','id' ) ,
		 'tblaitbpastmedical' => array( 'clinicid','ptb','eptb','dateonsick','tbtreatment','dotreatment','treatmentout','datecomptreat','id' ) ,
		 'tblaitraditional' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
		 'tblappoint' => array( 'av_id','atime','tosee','doct','att','id' ) ,
		 'tblart' => array( 'clinicid','art','artdate','id' ) ,
		 'tblavarv' => array( 'arv','dose','quantity','freq','form','status','dat','reason','remark','av_id','id' ) ,
		 'tblavlostdead' => array( 'clinicid','status','lddate','av_id','id' ) ,
		 'tblavmain' => array( 'clinicid','datevisit','typevisit','weight','height','pulse','resp','blood','temperature','stiprevent','artadherence','birthspacing','tbinfection','partner','advice','cough','fever','drenching','familynoyes','familyplan','hivpreven','hospitlastvisit','numberday','causehispital','missarv','mtime','condom','ncondom','asymptomatic','pgeneralised','weightloss','minor','herpesz','recurrent','angular','seborrhoeic','oral','papular','fungal','ulow','uchronic','ufever','ocandidiasis','ohairy','pulmonary','bacterial','severe','acute','anaemia','hivwast','pneumocystis','pneumonia','cryptococcal','atypical','extrapulmonary','candidiasis','hsv','disseminated','cryptonsoridiosis','cytome','tuberculous','kaposi','lymphoma','hiv','progressive','toxoplasmosis','typhoid','associated','chronic','mycosis','isoporiasis','invasive','cryptosporidiosis','who','tst','ntst','testid','eligibleart','funct','pregnancy','pregnantstatus','eddate','eligiblepro','refer','nextapp','av_id','artnum','id' ) ,
		 'tblavoidrugs' => array( 'arv','dose','quantity','freq','form','status','dat','reason','remark','av_id','id' ) ,
		 'tblavtb' => array( 'ifptb','ptb','sbx','ifeptb','eptb','tbtreatment','tbdate','av_id','id' ) ,
		 'tblavtbdrugs' => array( 'arv','dose','quantity','freq','form','status','dat','reason','remark','av_id','id' ) ,
		 'tblcart' => array( 'clinicid','art','artdate','id' ) ,
		 'tblciarvtreatment' => array( 'clinicid','detaildrugtreat','clinic','startdate','stopdate','reasonstop','id' ) ,
		 'tblcicotrimo' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
		 'tblcidrugallergy' => array( 'clinicid','drugallergy','allergy','dat','id' ) ,
		 'tblcifamily' => array( 'clinicid','relativespopart','age','hivstatus','status','mother','child','arv','oiart','receiarv','hostorytb','id' ) ,
		 'tblcifluconazole' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
		 'tblcimain' => array( 'clinicid','datevisit','dob','sex','addguardian','house','street','grou','village','commune','district','province','phone','namecontps1','contaddress1','contphone1','namecontps2','contaddress2','contphone2','childstatus','fatherstatus','motherstatus','education','refer','hbcteam','ftesdate','fage','foption','fresult','stestdate','sage','soption','sresult','offyesno','officein','datearv','arvnumber','tbpastmedical','vaccinat','infantnutrit','previousarv','precontrimoxazole','prefluconzazole','pretranditional','drugallergy','eclinicid','id' ) ,
		 'tblciothpastmedical' => array( 'clinicid','hiv','dateonset','id' ) ,
		 'tblcitbpastmedical' => array( 'clinicid','typeptb','eptb','dateonsick','tbtreat','treatment','id' ) ,
		 'tblcitraditional' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
		 'tblcvarv' => array( 'arv','form','dose','freq','totaltable','status','dat','reason','remark','cid','id' ) ,
		 'tblcvarvoi' => array( 'arvoi','status','dat','reason','cid','id' ) ,
		 'tblcvlostdead' => array( 'clinicid','status','lddate','cid','id' ) ,
		 'tblcvmain' => array( 'clinicid','datevisit','typevisit','weight','height','pulse','resp','blood','temperat','malnutrition','wh','hospitallastvisit','numday','causses','missmonth','mtime','missday','dtime','eye','ear','mouth','skin','ln4','heart','lung','abdomen','genital','neurology','psychological','asymptomatic','persistent','hepatos','papula','seborrheic','fungal','angular','lineal','molluscum','human','recurrentoral','parotid','herpes','recurrentrespiratory','unmoderate','unpersistent','unpersistentfever','oralcandidiasis','oralhairy','pulmonary','severe','acutenecrotizing','lymphoid','chronichiv','unanemia','unsevere','pneumocystis','recurrentsevere','chronicherpes','oesophageal','extrapulmonary','kaposi','cmvretinitis','cnstoxoplasmosis','cryptococcal','hivencephalopathy','progressive','anydisseminat','candida','disseminated','cryptosporidiosis','cerebral','acquiredhiv','hivassociat','who','testid','art','funct','tbdrugs','refer','nexapp','cid','artnum','id' ) ,
		 'tblcvoi' => array( 'oi','form','dose','freq','totaltable','status','dat','reason','remark','cid','id' ) ,
		 'tblcvtb' => array( 'ifptb','ptb','sbx','ifeptb','eptb','cid','id' ) ,
		 'tblcvtbdrugs' => array( 'arv','dose','quantity','freq','form','status','dat','reason','remark','cid','id' ) ,
		 'tbleimain' => array( 'clinicid','datevisit','dob','sex','addguardian','house','street','grou','village','commune','district','province','namecontps1','contaddress1','contphone1','fage','fhiv','fstatus','mage','moi','mart','hospital','mstatus','placedelivery','pmtct','datedelivery','statusdelivery','lenght','weight','bpregnancy','dtpregnancy','dpregnancy','art','arvpro','none','syrupnvp','cotrim','offyesno','transferin','hivtest','id' ) ,
		 'tblevarv' => array( 'arv','form','dose','freq','totaltable','status','dat','reason','remark','eid','id' ) ,
		 'tblevlostdead' => array( 'clinicid','status','lddate','eid','id' ) ,
		 'tblevmain' => array( 'clinicid','datevisit','typevisit','temperat','pulse','resp','weight','height','head','malnutrition','mild','moderate','severe','bcg','poli','measies','vaccinother','receivingnvp','receivingnone','arvproother','breast','formula','mixed','food','complementatry','dna1','andna1','dna2','andna2','dna','andna','datecollected','datesent','datereceived','result','datedelivered','resultantibody','dateresult','dateapp','eid','id' ) ,
		 'tblpatienttest' => array( 'testid','clinicid','dat','cd4','cd','cd8','hivload','hivlog','hivab','hbsag','hcvpcr','hbeag','tpha','antihbcab','rpr','antihbeab','rprab','hcvab','hbsab','wbc','neutrophils','hgb','eosinophis','hct','lymphocyte','mcv','monocyte','plt','reticulocte','prothrombin','proreticulocyte','creatinine','hdl','bilirubin','glucose','sodium','alphosphate','gotasat','potassium','amylase','gptalat','chloride','ck','chol','bicarbonate','lactate','triglyceride','urea','magnesium','phosphorus','calcium','bhcg','sputumafb','afbculture','afbculture1','urinemicroscopy','urinecomment','csfcell','csfgram','csfafb','csfindian','csfccag','csfprotein','csfglucose','bloodculture','bloodculture0','bloodculture1','bloodculture10','ctna','gcna','cxr','abdominal','id' ) ,
		 'tbltestabdominal' => array( 'testid','abdo','abdo1','abdo2','id' ) ,
		 'tbltestcxr' => array( 'testid','cxr','cxr1','cxr2','id' ) 
    ),
    "fixed" => array(
       'tblclinic' => array( 'clinic','clinickh','art','district','od','province' ) ,
		 'tblcommune' => array( 'idcommune','iddistrict','communeen' ) ,
		 'tbldistrict' => array( 'iddistrict','idprovince','districten' ) ,
		 'tblprovince' => array( 'idprovince','provinceen','provincekh','distance' ) ,
		 'tbluser' => array( 'username','password','child','adult','report','exp','back','muser' ) ,
		 'tblvillage' => array( 'idvillage','idcommune','villageen' ) ,
		 'tblvisitreason' => array( 'visitreasonen','idvisitreasontype' ) 
    ),
    "keys" => array(
       'tblclinic' => 'ART' ,
		 'tblaimain' => 'CLinicID' ,
		 'tblcimain' => 'ClinicID' ,
		 'tblpatienttest' => 'TestID' ,
		 'tblavmain' => 'AV_ID' ,
		 'tblcvmain' => 'Cid' ,
		 'tblaiarvtreatment' => 'ClinicID' ,
		 'tblaicotrimo' => 'ClinicID' ,
		 'tblaidrugallergy' => 'ClinicID' ,
		 'tblaifamily' => 'ClinicID' ,
		 'tblaifluconazole' => 'ClinicID' ,
		 'tblaiisoniazid' => 'ClinicID' ,
		 'tblaiothermedical' => 'ClinicID' ,
		 'tblaiothpasmedical' => 'ClinicID' ,
		 'tblaitbpastmedical' => 'ClinicID' ,
		 'tblaitraditional' => 'ClinicID' ,
		 'tblappoint' => 'AV_ID' ,
		 'tblart' => 'ClinicID' ,
		 'tblavarv' => 'AV_ID' ,
		 'tblavlostdead' => 'AV_ID' ,
		 'tblavoidrugs' => 'AV_ID' ,
		 'tblavtb' => 'AV_ID' ,
		 'tblavtbdrugs' => 'AV_ID' ,
		 'tblcart' => 'ClinicID' ,
		 'tblciarvtreatment' => 'ClinicID' ,
		 'tblcicotrimo' => 'ClinicID' ,
		 'tblcidrugallergy' => 'ClinicID' ,
		 'tblcifamily' => 'ClinicID' ,
		 'tblcifluconazole' => 'ClinicID' ,
		 'tblciothpastmedical' => 'ClinicID' ,
		 'tblcitbpastmedical' => 'ClinicID' ,
		 'tblcitraditional' => 'ClinicID' ,
		 'tblcommune' => 'IDCommune' ,
		 'tblcvarv' => 'Cid' ,
		 'tblcvarvoi' => 'Cid' ,
		 'tblcvlostdead' => 'Cid' ,
		 'tblcvoi' => 'Cid' ,
		 'tblcvtb' => 'Cid' ,
		 'tblcvtbdrugs' => '' ,
		 'tbldistrict' => 'IDDistrict' ,
		 'tbleimain' => 'ClinicID' ,
		 'tblevarv' => '' ,
		 'tblevlostdead' => 'ClinicID' ,
		 'tblevmain' => 'EID' ,
		 'tblprovince' => 'IDProvince' ,
		 'tbltestabdominal' => 'TestID' ,
		 'tbltestcxr' => 'TestID' ,
		 'tbluser' => '' ,
		 'tblvillage' => '' ,
		 'tblvisitreason' => '' 
    ),   
    "drugControls" => array(
        '3TC',
		'ABC',
		'AZT',
		'd4T',
		'ddI',
		'EFV',
		'IDV',
		'Kaletra(LPV/r)',
		'Kaletra (LPV/r)',
		'LPV',
		'NFV',
		'NVP',
		'RTV',
		'SQV',
		'TDF',
		'ATV',
		'ATV/r'
    )  
);      