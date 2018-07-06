<?php require_once("initPHP.php");
?>
var dat = {
    pag_title: "<?php if(isset($view_title)) echo $view_title;?>",
    oj_home: "./",
    user_id: <?php $a="";if(isset($_SESSION['user_id'])) $a=$_SESSION['user_id']; echo "\"$a\"";?>,
    mail: "<?php echo checkmail();?>",
    admin: <?php $a=false;if(isset($_SESSION['administrator'])) $a=true; echo "\"$a\"";?>,
    contestCreator: <?php $a=false; if(isset($_SESSION['contest_creator'])) $a=true; echo "\"$a\"";?>,
    problemEditor: <?php $a=false; if(isset($_SESSION['problem_editor'])) $a=true; echo "\"$a\"";?>,
    problemME: <?php $a=false; if(isset($_SESSION['problem_master_editor'])) $a=true; echo "\"$a\"";?>,
    sourceBrowser: <?php $a=false; if(isset($_SESSION['source_browser'])) $a=true; echo "\"$a\"";?>,
    screenWidth: (window.innerWidth<1020?2:1),
    bg0: [" black", " white", ""],
    bg1: [" grey lighten-5", " blue-grey darken-3", ""], // fondo
    bg2: [" grey lighten-4", " blue-grey darken-2", ""], // fondo2
    bg3: [" blue-grey lighten-5", " blue-grey darken-2", ""], // header footer
    bg4: [" blue-grey lighten-4"," blue-grey", ""], // resaltar
    bg5: [" orange accent-4"," grey accent-3", ""],
    bg6: [" orange lighten-5", " grey darken-2", ""],    
    tx0: [" black-text", " white-text", ""],
    tx1: [" grey-text text-darken-4", " blue-grey-text text-lighten-5", ""], // fondo
    tx2: [" grey-text text-darken-3", " grey-text text-lighten-5", ""], // fondo2
    tx3: [" blue-grey-text text-darken-4 ", " teal-text text-lighten-5", ""], // head footer
    tx4: [" blue-grey-text text-darken-4", " teal-text text-lighten-3", ""], // resaltar 
    tx5: [" deep-orange-text text-accent-4", " grey-text text-darken-3", ""],
    tx6: [" grey-text text-lighten-5", " grey-text text-lighten-5", ""],
    bg4HTML: ["#cfd8dc"," grey lighten-3"], // resaltar
    cl1: [" #EF5350", "#EF5350"], //red ligh
    cl2: [" #66BB6A", "#EF5350"] //red ligh //no se 
             
};
dat.st1=[dat.bg1[0]+" "+dat.tx1[0], dat.bg1[1]+" "+dat.tx1[1], dat.bg1[2]+" "+dat.tx1[2]]; //fondo body
dat.st2=[dat.bg2[0]+" "+dat.tx2[0], dat.bg2[1]+" "+dat.tx2[1], dat.bg2[2]+" "+dat.tx2[2]]; // 
dat.st3=[dat.bg3[0]+" "+dat.tx3[0], dat.bg3[1]+" "+dat.tx3[1], dat.bg3[2]+" "+dat.tx3[2]]; // header footer
dat.st4=[dat.bg4[0]+" "+dat.tx4[0], dat.bg4[1]+" "+dat.tx4[1], dat.bg4[2]+" "+dat.tx4[2]]; // resaltar
var msg = {
    problem: <?php echo "\"$MSG_PROBLEM\"";?>,
    //problemId: <?php echo "\"$MSG_PROBLEM_ID\"";?>,
    problems: <?php echo "\"$MSG_PROBLEMS\"";?>,
    status: <?php echo "\"$MSG_STATUS\"";?>,
    ranklist: <?php echo "\"$MSG_RANKLIST\"";?>,
    numContest: <?php  $a=checkcontest(); echo "\"$a\"";?>,
    contest: <?php echo "\"$MSG_CONTEST\"";?>,
    contests: <?php echo "\"$MSG_CONTESTS\"";?>,
    faq: <?php echo "\"$MSG_FAQ\"";?>,
    bbs: <?php echo "\"$MSG_BBS\"";?>,
    userInfo: <?php echo "\"$MSG_USERINFO\"";?>,
    logout: <?php echo "\"$MSG_LOGOUT\"";?>,
    admin: <?php echo "\"$MSG_ADMIN\"";?>,
    user: <?php echo "\"$MSG_USER\""?>,
    userId: <?php echo "\"$MSG_USER_ID\"";?>,
    description: <?php echo "\"$MSG_Description\"";?>,
    input: <?php echo "\"$MSG_Input\"";?>,
    output: <?php echo "\"$MSG_Output\"";?>,
    sampleInput: <?php echo "\"$MSG_Sample_Input\"";?>,
    sampleOutput: <?php echo "\"$MSG_Sample_Output\"";?>,
    hint: <?php echo "\"$MSG_HINT\"";?>,
    //title: <?php echo "\"$MSG_TITLE\""?>,
    //source: <?php echo "\"$MSG_SOURCE\""?>,
    search: <?php echo "\"$MSG_SEARCH\""?>,
    //ac: <?php echo "\"$MSG_AC\""?>,
    submit: <?php echo "\"$MSG_SUBMIT\""?>,
    //runId: <?php echo "\"$MSG_RUNID\""?>,
    //result: <?php echo "\"$MSG_RESULT\""?>,
    //memory: <?php echo "\"$MSG_MEMORY\""?>,
    //time: <?php echo "\"$MSG_TIME\""?>,
    //lang: <?php echo "\"$MSG_LANG\""?>,
    //codeLength: <?php echo "\"$MSG_CODE_LENGTH\""?>,
    //submitTime: <?php echo "\"$MSG_SUBMIT_TIME\""?>,
    //submits: "Envios"
    
};



