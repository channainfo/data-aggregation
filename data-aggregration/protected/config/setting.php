<?php    
  return array(
     'tblaimain' => array( 'clinicid','grou' ) ,
	'tblcimain' => array( 'clinicid','datevisit','dob','sex','addguardian','house','street','grou','village','commune','district','province','phone','namecontps1','contaddress1','contphone1','namecontps2','contaddress2','contphone2','childstatus','fatherstatus','motherstatus','education','refer','hbcteam','ftesdate','fage','foption','fresult','stestdate','sage','soption','sresult','offyesno','officein','datearv','arvnumber','tbpastmedical','vaccinat','infantnutrit','previousarv','precontrimoxazole','prefluconzazole','pretranditional','drugallergy','id' ) ,
	'tblcvmain' => array( 'datevisit','typevisit','weight','height','pulse' ) ,
	'tblaiarvtreatment' => array( 'clinicid','detaildrugtreat','clinic','startdate','stopdate','reasonstop','id' ) ,
	'tblaifamily' => array( 'clinicid','id' ) ,
	'tblaiothpasmedical' => array( 'clinicid','hivrelatill','dateon','othnothiv','id' ) ,
	'tblart' => array( 'clinicid','art','artdate','id' ) ,
	'tblavtb' => array( 'ifptb','ptb','sbx','ifeptb','eptb','tbtreatment','tbdate','av_id','id' ) ,
	'tblcicotrimo' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
	'tblciothpastmedical' => array( 'clinicid','hiv','dateonset','id' ) ,
	'tblcvarvoi' => array( 'arvoi','status','dat','reason','cid','id' ) ,
	'tblcvtbdrugs' => array( 'arv','dose','quantity','freq','form','status','dat','reason','remark','cid','id' ) ,
	'tblevmain' => array( 'clinicid','datevisit','typevisit','temperat','pulse','resp','weight','height','head','malnutrition','mild','moderate','severe','bcg','poli','measies','vaccinother','receivingnvp','receivingnone','arvproother','breast','formula','mixed','food','complementatry','dna1','andna1','dna2','andna2','dna','andna','datecollected','datesent','datereceived','result','datedelivered','resultantibody','dateresult','dateapp','eid','id' ) ,
	'tblcommune' => array( 'idcommune','iddistrict','communeen' ) ,
	'tblvillage' => array( 'idvillage','idcommune','villageen' ) 
);      