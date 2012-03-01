  <?php    
  return array(
    "tables" => array( 'tblclinic' => array( 'Clinic','ClinicKh','ART','District','OD','Province' ) 
		, 'tblaimain' => array( 'CLinicID','DateFirstVisit','Age','Sex','House','Street','Grou','Village','Commune','District','Province','Phone','NameContPs1','ContAddress1','ContPhone1','NameContPs2','ContAddress2','ContPhone2','MaritalStatus','Occupation','Education','Rea','Writ','Referredfrom','NameLocationHBC','DateConfPosHIV','DateLastNegHIV','OffYesNo','OffTransferin','DateStaART','ArtNumber','TbPasMediHis','Gravida','Para','DailyAlcohol','Tabacco','Idu','Yama','PreviousARV','Precontrimoxazole','Prefluconzazole','PreIsoniazid','PreTranditional','DrugAllergy','Vct','PClinicID','ID','test_id' ) 
		, 'tblcimain' => array( 'ClinicID','DateVisit','DOB','Sex','AddGuardian','House','Street','Grou','Village','Commune','District','Province','Phone','NameContPs1','ContAddress1','ContPhone1','NameContPs2','ContAddress2','ContPhone2','ChildStatus','FatherStatus','MotherStatus','Education','Refer','HBCTeam','FTesDate','FAge','FOption','FResult','STestDate','SAge','SOption','SResult','OffYesNo','OfficeIn','DateARV','ARVNumber','TBPastMedical','Vaccinat','InfantNutrit','PreviousARV','Precontrimoxazole','Prefluconzazole','PreTranditional','DrugAllergy','ID' ) 
		, 'tblpatienttest' => array( 'TestID','ClinicID','Dat','CD4','CD','CD8','HIVLoad','HIVLog','HIVAb','HBsAg','HCVPCR','HBeAg','TPHA','AntiHBcAb','RPR','AntiHBeAb','RPRab','HCVAb','HBsAb','WBC','Neutrophils','HGB','Eosinophis','HCT','Lymphocyte','MCV','Monocyte','PLT','Reticulocte','Prothrombin','ProReticulocyte','Creatinine','HDL','Bilirubin','Glucose','Sodium','AlPhosphate','GotASAT','Potassium','Amylase','GPTALAT','Chloride','CK','CHOL','Bicarbonate','Lactate','Triglyceride','Urea','Magnesium','Phosphorus','Calcium','BHCG','SputumAFB','AFBCulture','AFBCulture1','UrineMicroscopy','UrineComment','CSFCell','CSFGram','CSFAFB','CSFIndian','CSFCCag','CSFProtein','CSFGlucose','BloodCulture','BloodCulture0','BloodCulture1','BloodCulture10','CTNA','GCNA','CXR','Abdominal','ID' ) 
		, 'tblavmain' => array( 'ClinicID','DateVisit','TypeVisit','Weight','Height','Pulse','Resp','Blood','Temperature','STIPrevent','ARTAdherence','BirthSpacing','TBinfection','Partner','Advice','Cough','Fever','Drenching','FamilyNoYes','FamilyPlan','Hivpreven','HospitlastVisit','NumberDay','CauseHispital','MissARV','Mtime','Condom','Ncondom','Asymptomatic','PGeneralised','weightLoss','Minor','HerpesZ','Recurrent','Angular','Seborrhoeic','Oral','Papular','Fungal','ULow','Uchronic','Ufever','Ocandidiasis','Ohairy','Pulmonary','Bacterial','Severe','Acute','Anaemia','HIVwast','Pneumocystis','Pneumonia','Cryptococcal','Atypical','Extrapulmonary','Candidiasis','HSV','Disseminated','Cryptonsoridiosis','Cytome','tuberculous','Kaposi','Lymphoma','HIV','Progressive','Toxoplasmosis','Typhoid','Associated','Chronic','Mycosis','Isoporiasis','Invasive','Cryptosporidiosis','Who','TST','NTST','TestID','EligibleART','funct','Pregnancy','PregnantStatus','EDDate','EligiblePro','Refer','NextApp','AV_ID','ARTNum','ID' ) 
		, 'tblcvmain' => array( 'ClinicID','DateVisit','TypeVisit','Weight','Height','Pulse','Resp','Blood','Temperat','Malnutrition','WH','HospitalLastVisit','NumDay','Causses','MissMonth','MTime','MissDay','DTime','Eye','Ear','Mouth','Skin','LN4','Heart','Lung','Abdomen','Genital','Neurology','Psychological','Asymptomatic','Persistent','Hepatos','Papula','Seborrheic','Fungal','Angular','Lineal','Molluscum','Human','RecurrentOral','Parotid','Herpes','RecurrentRespiratory','UnModerate','UnPersistent','UnPersistentFever','OralCandidiasis','OralHairy','Pulmonary','Severe','AcuteNecrotizing','Lymphoid','ChronicHIV','UnAnemia','UnSevere','Pneumocystis','RecurrentSevere','ChronicHerpes','Oesophageal','Extrapulmonary','Kaposi','CMVRetinitis','CNSToxoplasmosis','Cryptococcal','HIVEncephalopathy','Progressive','AnyDisseminat','Candida','Disseminated','Cryptosporidiosis','Cerebral','AcquiredHIV','HIVAssociat','WHO','TestID','ART','Funct','TBdrugs','Refer','NexApp','Cid','ARTNum','ID' ) 
		, 'tblaiarvtreatment' => array( 'ClinicID','Detaildrugtreat','Clinic','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblaicotrimo' => array( 'ClinicID','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblaidrugallergy' => array( 'ClinicID','DrugAllergy','Allergy','Dat','ID' ) 
		, 'tblaifamily' => array( 'ClinicID','RelativeSpoPart','Age','HivStatus','Status','Mother','Child','ARV','OIART','ReceiARV','HostoryTB','ID' ) 
		, 'tblaifluconazole' => array( 'ClinicID','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblaiisoniazid' => array( 'ClinicID','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblaiothermedical' => array( 'ClinicID','Detaildrugtreat','Clinic','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblaiothpasmedical' => array( 'ClinicID','HIVRelatill','DateOn','OthNotHIV','ID' ) 
		, 'tblaitbpastmedical' => array( 'ClinicID','Ptb','EPTB','DateOnSick','TbTreatment','DoTreatment','TreatmentOut','Datecomptreat','ID' ) 
		, 'tblaitraditional' => array( 'ClinicID','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblappoint' => array( 'AV_ID','Atime','Tosee','Doct','Att','ID' ) 
		, 'tblart' => array( 'ClinicID','ART','ARTDate','ID' ) 
		, 'tblavarv' => array( 'ARV','Dose','Quantity','Freq','Form','Status','Dat','Reason','Remark','AV_ID','ID' ) 
		, 'tblavlostdead' => array( 'ClinicID','Status','LDdate','AV_ID','ID' ) 
		, 'tblavoidrugs' => array( 'ARV','Dose','Quantity','Freq','Form','Status','Dat','Reason','Remark','AV_ID','ID' ) 
		, 'tblavtb' => array( 'IFPTB','PTB','Sbx','IfEPTB','EPTB','TBTreatment','TBdate','AV_ID','ID' ) 
		, 'tblavtbdrugs' => array( 'ARV','Dose','Quantity','Freq','Form','Status','Dat','Reason','Remark','AV_ID','ID' ) 
		, 'tblcart' => array( 'ClinicID','ART','ARTDate','ID' ) 
		, 'tblciarvtreatment' => array( 'ClinicID','Detaildrugtreat','Clinic','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblcicotrimo' => array( 'ClinicID','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblcidrugallergy' => array( 'ClinicID','DrugAllergy','Allergy','Dat','ID' ) 
		, 'tblcifamily' => array( 'ClinicID','RelativeSpoPart','Age','HivStatus','Status','Mother','Child','ARV','OIART','ReceiARV','HostoryTB','ID' ) 
		, 'tblcifluconazole' => array( 'ClinicID','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblciothpastmedical' => array( 'ClinicID','HIV','DateOnset','ID' ) 
		, 'tblcitbpastmedical' => array( 'ClinicID','TypePTB','EPTB','DateOnSick','TBTreat','Treatment','ID' ) 
		, 'tblcitraditional' => array( 'ClinicID','StartDate','StopDate','ReasonStop','ID' ) 
		, 'tblcvarv' => array( 'ARV','Form','Dose','Freq','TotalTable','Status','Dat','Reason','Remark','Cid','ID' ) 
		, 'tblcvarvoi' => array( 'ARVOI','Status','Dat','Reason','Cid','ID' ) 
		, 'tblcvlostdead' => array( 'ClinicID','Status','LDdate','Cid','ID' ) 
		, 'tblcvoi' => array( 'OI','Form','Dose','Freq','TotalTable','Status','Dat','Reason','Remark','Cid','ID' ) 
		, 'tblcvtb' => array( 'IFPTB','PTB','Sbx','IfEPTB','EPTB','Cid','ID' ) 
		//, 'tblcvtbdrugs' => array( 'ARV','Dose','Quantity','Freq','Form','Status','Dat','Reason','Remark','Cid','ID' ) 
		, 'tbltestabdominal' => array( 'TestID','Abdo','Abdo1','Abdo2','ID' ) 
		, 'tbltestcxr' => array( 'TestID','CXR','CXR1','CXR2','ID' ) ),
    "drugControls" => array('3TC'
		,'ABC'
		,'AZT'
		,'d4T'
		,'ddl'
		,'EFV'
		,'IDV'
		,'Kaletra(LPV/r)'
		,'LPV'
		,'NFV'
		,'NVP'
		,'RTV'
		,'SQV'
		,'TDF')  
);      