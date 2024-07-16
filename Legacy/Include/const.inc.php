<?php
//oj-header.php
static $MSG_FAQ = "F.A.Qs";
$MSG_BBS = "Foro";
$MSG_HOME = "Principal";
$MSG_PROBLEMS = "Problemas";
$MSG_STATUS = "Estado";
$MSG_RANKLIST = "Ranking";
$MSG_CONTEST = "Concursos";
$MSG_RECENT_CONTEST = "Reciente";
$MSG_LOGOUT = "Salir";
$MSG_LOGIN = "Entrar";
$MSG_REGISTER = "Registro";
$MSG_ADMIN = "Admin";
$MSG_STANDING = "Standing";
$MSG_STATISTICS = "Statistics";
$MSG_USERINFO = "Editar Usuario";
$MSG_MAIL = "Correo";
$CHAT = "Chat patito";
//status.php
$MSG_Pending = "Pending";
$MSG_Pending_Rejudging = "Pending Rejudging";
$MSG_Compiling = "Compiling";
$MSG_Running_Judging = "Running & Judging";
$MSG_Accepted = "Accepted";
$MSG_Presentation_Error = "Presentation Error";
$MSG_Wrong_Answer = "Wrong Answer";
$MSG_Time_Limit_Exceed = "Time Limit Exceed";
$MSG_Memory_Limit_Exceed = "Memory Limit Exceed";
$MSG_Output_Limit_Exceed = "Output Limit Exceed";
$MSG_Runtime_Error = "Runtime Error";
$MSG_TEST_RUN = "Test Running Done";
$MSG_Compile_Error = "Compile Error";

$MSG_Runtime_Click = "Runtime Error(Click)";
$MSG_Compile_Click = "Compile Error(Click)";
$MSG_Click_Detail = "Click To View Detail";
$MSG_Compile_OK = "Compile OK";
$MSG_RUNID = "RunID";
$MSG_USER = "User";
$MSG_PROBLEM = "Problem";
$MSG_RESULT = "Result";
$MSG_MEMORY = "Memory";
$MSG_TIME = "Time";
$MSG_LANG = "Language";
$MSG_CODE_LENGTH = "Code Length";
$MSG_SUBMIT_TIME = "Submit Time";
$MSG_Manual = "Manual Judge";
$MSG_OK = "OK";
$MSG_Explain = "Type reason or explaination";
//problemstatistics.php
$MSG_PD = "PD";
$MSG_PR = "PR";
$MSG_CI = "CI";
$MSG_RJ = "RJ";
$MSG_AC = "AC";
$MSG_PE = "PE";
$MSG_WA = "WA";
$MSG_TLE = "TLE";
$MSG_MLE = "MLE";
$MSG_OLE = "OLE";
$MSG_RE = "RE";
$MSG_CE = "CE";
$MSG_CO = "CO";
$MSG_TR = "Test";
//problemset.php
$MSG_SEARCH = "Buscar";
$MSG_PROBLEM_ID = "Problem ID";
$MSG_TITLE = "Titulo";
$MSG_SOURCE = "Setter";
$MSG_SUBMIT = "Enviar";

//ranklist.php
$MSG_Number = "No.";
$MSG_NICK = "Nombre";
$MSG_SOVLED = "Resuelto";
$MSG_RATIO = "Ratio";

//registerpage.php
$MSG_USER_ID = "Usuario";
$MSG_PASSWORD = "Contraseña";
$MSG_REPEAT_PASSWORD = "Repita Contraseña";
$MSG_SCHOOL = "Institucion";
$MSG_EMAIL = "Correo";
$MSG_REG_INFO = "Registro";
$MSG_VCODE = "Codigo de Verificacion";
$MSG_LASTNAME = "Apellido";
$MSG_COUNTRY = "Pais";
$MSG_CITY = "Ciudad";
$MSG_INSTITUTE = "Institucion";
//problem.php
$MSG_NO_SUCH_PROBLEM = "Problema no valido!";
$MSG_Description = "Descripción";
$MSG_Input = "Entrada";
$MSG_Output = "Salida";
$MSG_Sample_Input = "Ejemplo Entrada";
$MSG_Sample_Output = "Ejemplo Salida";
$MSG_HINT = "Ayuda";
$MSG_Source = "Codigo";
$MSG_Time_Limit = "Time Limit";
$MSG_Memory_Limit = "Memory Limit";

//admin menu
$MSG_TRACK = "Seguimiento";
$MSG_SEEOJ = "Estado";
$MSG_ADD = "Agregar";
$MSG_LIST = "Listar";
$MSG_NEWS = "Noticias";
$MSG_TEAMGENERATOR = "GenerarEquipo";
$MSG_SETMESSAGE = "EnviarMensaje";
$MSG_SETPASSWORD = "CambiarClave";
$MSG_REJUDGE = "Rejudge";
$MSG_PRIVILEGE = "Privilegios";
$MSG_GIVESOURCE = "GiveSource";
$MSG_IMPORT = "Importar";
$MSG_EXPORT = "Exportar";
$MSG_UPDATE_DATABASE = "UpdateDatabase";
$MSG_ONLINE = "En linea";

//contest.php
$MSG_PRIVATE_WARNING = "Este es un concurso privado";
$MSG_WATCH_RANK = "Click HERE to watch contest rank.";

$MSG_Public = "Publico";
$MSG_Private = "Privado";
$MSG_Running = "Corriendo";
$MSG_Start = "Inicio";
$MSG_TotalTime = "Falta";
$MSG_LeftTime = "Termina en";
$MSG_Ended = "Termino";

$judge_result = array($MSG_Pending, $MSG_Pending_Rejudging, $MSG_Compiling, $MSG_Running_Judging, $MSG_Accepted, $MSG_Presentation_Error, $MSG_Wrong_Answer, $MSG_Time_Limit_Exceed, $MSG_Memory_Limit_Exceed, $MSG_Output_Limit_Exceed, $MSG_Runtime_Error, $MSG_Compile_Error, $MSG_Compile_OK, $MSG_TEST_RUN);
$jresult      = array($MSG_PD, $MSG_PR, $MSG_CI, $MSG_RJ, $MSG_AC, $MSG_PE, $MSG_WA, $MSG_TLE, $MSG_MLE, $MSG_OLE, $MSG_RE, $MSG_CE, $MSG_CO, $MSG_TR);
$judge_color  = array("gray", "gray", "orange", "orange", "green", "red", "red", "red", "red", "red", "red", "red ", "red");

//                         0    1      2             3     4      5       6         7       8       9     10       11          12               13 ,14,  15          16          17       18, 19
//$language_name    = array("C", "C++", "Pascal", "Java", "Ruby", "Bash", "Python2", "PHP", "Perl", "C#", "Obj-C", "FreeBasic", "Other Language", "", "", "Python3", "C++11", "Python3.7", "Go", "Python3.12");
$language_name    = array("C", "C++11", "Pascal", "Java", "Ruby", "Bash", "Python2", "PHP", "Perl", "C#", "Obj-C", "FreeBasic", "Other Language", "", "", "Python3", "C++11", "Python3.12", "Go", "Python3.12");
$language_ext     = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");
$language_visible = array(0,  0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1);
$PID = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

$PID2 = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "A1", "B1", "C1", "D1", "E1", "F1", "G1", "H1", "I1", "J1", "K1", "L1", "M1", "N1", "O1", "P1", "Q1", "R1", "S1", "T1", "U1", "V1", "W1", "X1", "Y1", "Z1", "A2", "B2", "C2", "D2", "E2", "F2", "G2", "H2", "I2", "J2", "K2", "L2", "M2", "N2", "O2", "P2", "Q2", "R2", "S2", "T2", "U2", "V2", "W2", "X2", "Y2", "Z2", "A3", "B3", "C3", "D3", "E3", "F3", "G3", "H3", "I3", "J3", "K3", "L3", "M3", "N3");