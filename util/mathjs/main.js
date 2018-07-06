//Initialization of script
// COPIA DE http://eric.chopin.pagesperso-orange.fr/latex/latex4web.htm
var g_MaxClass = 0
var g_NextTokenPos = 0
var g_TokenStartPos = 0 // used for \label and \ref 

/*
 constants that define the current group category at 
 a given bracket level. For instance, when parsing 
 {\bf \vec X }, ltxBold is pushed in the stack when \bf is found, 
 then \ltxVector is pushed into the stack at the next bracket level 
 (even if there are no physical brackets around the \vec, \vec triggers the opening of a new group)
*/
var ltxNone=0
var ltxGroup=1
var ltxSubscript=2
var ltxSupscript=3
var ltxGhostSubscript=4
var ltxGhostSupscript=5
var ltxVector=6
var ltxGhostVector=7 //replaces ltxVector which is now used only for \vec{...}, \bar{...} etc
var ltxNumerator=8
var ltxDenominator=9
var ltxSqrt=10
var ltxInt=11
var ltxBold=12
var ltxItalic=13
var ltxLabel=14
var ltxRef=15
var ltxSmall=16
var ltxNormalSize=17 
var ltxLarge=18
var ltxBigLarge=19
var ltxHuge=20 
var ltxLefteqn = 21
var ltxStackRelUpperText = 22
var ltxStackRelMainText = 23 
var ltxOverLine = 24
var ltxBibitem = 25 
var ltxSection = 26 
var ltxSubSection = 27 
var ltxSubSubSection = 28 
var ltxFootnote = 29

var g_UnicodeLaTex=1  // 0=non unicode, 1=unicode, 2=unicode 3.2
var g_Symbol = '<font face=symbol>'
var g_FontEnd = '</font>'
var g_BOX = '<font size="-2"><sup>[<u>&#175;</u>]</sup></font>'
var g_HBAR = '(<sup>h</sup>/<sub>2<font face=symbol>p</font></sub>)'
var g_DAG = '<font face=helvetica>f</font>'
var g_DDAG = '<strike><font face=helvetica>f</font></strike>'

var g_strlog = ''
var g_TOC = '' // Table of contents  
var g_strDebug = ''
var g_reftext = '' // stores parameter of \ref or \label commands

var g_header = ''/*<html>\r\n<head>\r\n' LOL OG XD
  g_header = g_header + '<title>LaTeX4Web 1.4 OUTPUT</title>\r\n'
  g_header = g_header + '<style type=\"text/css\">\r\n<!--\r\n'
  g_header = g_header + ' body {color: black;  background:\"#FFCC99\";  }\r\n'
  g_header = g_header + ' div.p { margin-top: 7pt;}\r\n'
  g_header = g_header + ' td div.comp { margin-top: -0.6ex; margin-bottom: -1ex;}\r\n'
  g_header = g_header + ' td div.comb { margin-top: -0.6ex; margin-bottom: -.6ex;}\r\n'
  g_header = g_header + ' td div.norm {line-height:normal;}\r\n'
  g_header = g_header + ' td div.hrcomp { line-height: 0.9; margin-top: -0.8ex; margin-bottom: -1ex;}\r\n'
  g_header = g_header + ' td.sqrt {border-top:2 solid black;\r\n'
  g_header = g_header + '          border-left:2 solid black;\r\n'
  g_header = g_header + '          border-bottom:none;\r\n'
  g_header = g_header + '          border-right:none;}\r\n'
  g_header = g_header + ' table.sqrt {border-top:2 solid black;\r\n'
  g_header = g_header + '             border-left:2 solid black;\r\n'
  g_header = g_header + '             border-bottom:none;\r\n'
  g_header = g_header + '             border-right:none;}\r\n'
  g_header = g_header + '-->\r\n'
  g_header = g_header + '</style>\r\n'

  g_header = g_header + '</head>\r\n<body>\r\n'*/

for(i=0;i<256;i++)
{
  if(EClass[i] > g_MaxClass)
  {
     g_MaxClass = EClass[i]
  }
}
//end for(i=0;i<256;i++)

//From v1.3, the dfa array is compressed and stored in a smaller file latex_dfa_com.js
//This code uncompresses the array in memory (the array contains 43152 integers..., but only about 1000 are !=0)
var FTT = new Array(43151)
for(i=0;i<FTT.length;i++)
{
  FTT[i]=0
}
for(i=0;i<FTTc.length;i=i+2)
{
  FTT[FTTc[i]]=FTTc[i+1]
}
// end of uncompression

//===========================================================
function GetNextToken(txtSource,startAt)
{
  var cont = true;
  var curPos = 0;
  var curClass=0;
  var maxPos = txtSource.length-1;
  var curStateId =0;
  var nextStateId=0;
  var firstNonVanishingStatePos =0;
  var curAcceptedStateId = -1;
  var Result=-1;
  
  
  if( startAt < 0 ) {startAt=0}
  curPos=startAt
  if( curPos > maxPos )
  {
    return Result;
  }
  //Main loop
  while(cont==true)
  {
    curClass = EClass[txtSource.charCodeAt(curPos)]
    nextStateId = FTT[curStateId*(g_MaxClass+1)+curClass] 
    if (!nextStateId) nextStateId=0; // added trick to skip unicode letters (cyrillic, japanese...)

    if((curStateId==0) && (nextStateId>0))
    {
      firstNonVanishingStatePos = curPos
    }
    if(Accept[nextStateId]>-1)
    {
      curAcceptedStateId = nextStateId
    }
    if(curPos > maxPos )
    {
      cont = false
    }
    else
    {
      if(curAcceptedStateId>-1 && nextStateId==0)
      {
        cont = false // longest match found
      }
      else
      {
        curPos = curPos + 1
        if(nextStateId==0 && curStateId!=0)
        {
          curStateId = 0
          curPos = firstNonVanishingStatePos +1
        }
        cont = true
      }

    }// end else if(curPos > maxPos )
    curStateId = nextStateId
  }// end while (cont==true)
  if(curAcceptedStateId>-1)
  {
    Result = Accept[curAcceptedStateId]
    g_NextTokenPos = curPos-tokens[Result].length
  }
  else
  {
    Result = -1 
    g_NextTokenPos = startAt
  }
  return Result;
}// end function GetNextToken(txtSource,startAt,position)
//----------------------------------------------------------

//============================================================================
function ltxParse(inputString)
{
 
  var step = 0  // this is purely to avoid infinite loops in case of bugs
  var BracketLevel = 0 // 0 outside any bracket
  var FracLevel = 0
  var LineHeight = 1
  var TokenLength = 0
  var CurTableIndent = 0
  var MathMode = 0
  var InLine = 1 // means normal text, without html tables 
  var TextLevel = 0 // +1,2,3 for supscripts and -1,2,3 for subscripts 
  var TextLevelAbsolute = 0 // unsigned sub/sup-script level: in e^{a_{b_c}}, c is at absolute level 3
  var previoustokenid = 0
  var nexttokenid = 0
  var tmpString = '' // can be used for many things, mainly indentation.
  var tmpPos = 0 // can be used as a temporary posisiton in source text, in any context.

  var InsideArray = 0 // = 1 if we are processing data in a \begin{array} ... \end{array} environment
  var InsideEqnArray = 0 // = 1 in an equation array (or tabular array which is not yet supported)

  var OffsetPosition = 0 // used by the \bar, \vec, \hat, \tilde commands to skip the following white spaces 

  var EquationNumber = 0
  var NoNumber = 0  // =1 when \nonumber is specified, to skip incrementing EquationNumber for the current line
  var IntegralBoundaryPending = 0 // =1 means that we are processing the lower or upper bound of a big integral inside tables
  var IntegralBoundaryBracketLevel = 0 // If the bounds of a big integral are within { }, one must backup the bracket level when we start the group
  var SumOrProdPending = 0 // =1 when processing boundaries of a \sum or \prod command
  var SumOrProdBoundaryBracketLevel = 0 // If the bounds of a \sum or \prod are within { }, one must backup the bracket level when we start the group
  var SupscriptAfterRightDelimiter = 0  // =1 if we encounter a supscript after a \right command

  // Maximum group nesting = 20 
  // This array will contain the ltx... constants defined above when processing nested groups 
  var ActionStack = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]

  var SectionNumber = 0
  var SubSectionNumber = 0
  var SubSubSectionNumber = 0
  var SectionStartPos = 0

  var RefReplacements = '' // string containing javascript commands for cross-reference replacements
  var InBetweenText = ''  // text between 2 consecutive tokens found 

  var strDebug = ''

  g_strDebug = '<table border=1><tr><th>Current Pos</th><th>Cur Char</th><th>Next Token Pos</th><th>Token Id</th><th>Token Text</th><th>B Level</th><th>Action Stack</th></tr>'
  g_strlog = g_header // initialize output text
  g_TOC = ''  // table of contents
  g_NextTokenPos=0 

  //=============== PARSING OF INPUT STARTS HERE =================================

  // Apply user-defined replacements (aliases)
  window.status = 'Replacing aliases...'
  var str = LatexReplaceAliases(inputString) // document.MainForm.input.value)  

  window.status = 'Looking for bibliography references ... '
  // bibliography ======================================
  var biblioRegexp = /\\bibitem\{([^}]*)\}/g
  var testArray = new Array()
  var biblioArray = new Array()
  do 
  {
    testArray = biblioRegexp.exec(str)
    if(testArray!==null)
    {
      biblioArray.push(testArray[1])
    }
  }
  while(testArray!==null) 

  for ( var i=0;i<biblioArray.length;i++)
  {
    var bibcite = new RegExp('\\\\cite\{([^{}]*,)?'+biblioArray[i]+'(,[^{}]*)?\}',"g")
    str = str.replace(bibcite,'<a href=\"#bib.'+i+'\">\['+i+'\]<\/a>'+'\\cite\{$1$2\}')
    var bibref = new RegExp("\\\\bibitem\{"+biblioArray[i]+"\}","g")
    str = str.replace(bibref,'\r\n<a name=\"bib.'+i+'\"><li value='+i+'>')
  }
  str = str.replace(/\\cite\{[^}]*\}/g,'')
  // end bibliography -----------------------------------

  // ========================= MAIN LOOP ===========================================
  // Scanning for the next token in the source text
  while( (nexttokenid!=-1) && (g_NextTokenPos!=-1) )
  {
    CurrentPos = g_NextTokenPos + TokenLength
    previoustokenid = nexttokenid
    nexttokenid = GetNextToken(str, CurrentPos)
    if(nexttokenid!=-1)
    {
      TokenLength = tokens[nexttokenid].length 
      window.status = 'Parsing at position ' + g_NextTokenPos + ' / ' + str.length

/*
      // =================================================================================
      //           Uncomment this block to enable debugging (on small latex inputs!!!)
      // ============== debug string ===================================================== 
      // TO BE USED ONLY WITH SMALL LATEX INPUTS
      //
      strDebug = str.substr(0,CurrentPos) +'<span style=\"background-color:green\">'+ str.charAt(CurrentPos) +'</span>'
      if(g_NextTokenPos>CurrentPos)
      {
        strDebug += str.substring(CurrentPos+1,g_NextTokenPos)+'<span style=\"background-color:red\">'+ str.charAt(g_NextTokenPos) +'</span>'+str.substr(g_NextTokenPos+1)
      }
      else
      {
        strDebug += str.substr(CurrentPos+1)
      }
      g_strDebug += '<tr><td valign=top>' + CurrentPos + '</td><td valign=top>' + strDebug  + '</td><td valign=top>' + g_NextTokenPos + '</td><td valign=top>' + nexttokenid + '</td><td><pre>' + tokens[nexttokenid] + '</pre></td><td>' + BracketLevel + '</td><td>' + ActionStack.join(',') + '</td></tr>' + vbCrLf 
      // 
      // -------------------------------- END OF DEBUG BLOCK -----------------------------
*/

      if( (MathMode==1) && (g_strlog.substr(-2,2) == vbCrLf ) ) 
      {
        g_strlog = g_strlog + Spaces(CurTableIndent)
      }
      
      InBetweenText = str.substr(CurrentPos, g_NextTokenPos - CurrentPos)
      g_strlog = g_strlog + InBetweenText //+ "<em>"; // LOL OG XD
      
      //========================================
      switch(nexttokenid)
      {
                       
        case 0: // \lefteqn{
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxLefteqn
          NoNumber = 1 // (this first equation is not numbered) 
          break;

        case 1: // \stackrel{  => see \vec
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxStackRelUpperText
          g_strlog = g_strlog + '<span style=\"position:relative;top:-9pt;left:6pt;\"><small>'
          break;

        case 2: // \begin{array}
          InsideArray = 1
          tmpString = Spaces(CurTableIndent-1)
          g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td>\r\n'+tmpString+'  <table cellspacing=0 align=center>\r\n'+tmpString +'  <tr>\r\n'+tmpString+'    <td>'
          g_strlog = g_strlog + '\r\n'+tmpString+'      <table cellspacing=0>\r\n'+tmpString +'      <tr>\r\n'+tmpString+'        <td>\r\n'+tmpString+'          '
          CurTableIndent = CurTableIndent + 4 
          TextLevel = 1
          TextLevelAbsolute = 1
          // skip alignment parameters (too complex to manage that) like {|r||cl|c|} or so...
          tmpPos = str.indexOf('}',g_NextTokenPos+TokenLength)
          if (tmpPos>-1)
          {
            g_NextTokenPos = tmpPos+1 
            TokenLength=0
          }

          break;

        case 3:// \end{array}
          CurTableIndent = CurTableIndent - 4
          tmpString = Spaces(CurTableIndent-1)
          g_strlog = g_strlog + '\r\n'+tmpString+'        </td>\r\n'+tmpString+'      </tr>\r\n'+tmpString+'      </table>'
          g_strlog = g_strlog + '\r\n'+tmpString+'    </td>\r\n'+tmpString+'  </tr>\r\n'+tmpString+'  </table>\r\n'+tmpString+'</td>\r\n'+tmpString+'<td>\r\n'+tmpString+'  '
          InsideArray = 0
          TextLevel = 0
          TextLevelAbsolute = 0
          break;

        case 4:// \begin{eqnarray}
          MathMode = 1
          InLine = 0
          InsideEqnArray = 1
          g_strlog = g_strlog + '\r\n<table cellspacing=0  align=center>\r\n<tr>\r\n  <td>\r\n    <table cellspacing=0  border=0>\r\n    <tr>\r\n      <td>\r\n        '    
          CurTableIndent = 4
          break;

        case 5: // \end{eqnarray}
          if(NoNumber==0)
          {
            tmpString = Spaces(CurTableIndent-3)
            g_strlog = g_strlog + '\r\n' + tmpString + '    </td>\r\n' + tmpString + '  </tr>\r\n' + tmpString + '  </table>\r\n' + tmpString + '</td>\r\n' + tmpString + '<td>\r\n' + tmpString + '  <table cellspacing=0 >\r\n' + tmpString + '  <tr>\r\n' + tmpString + '    <td align=center>\r\n' + tmpString + '      '
            g_strlog = g_strlog + '<a name=\"eq' + EquationNumber + '\">&nbsp;&nbsp;&nbsp;&nbsp;<font color=blue>(' + EquationNumber + ')</font>'
            EquationNumber = EquationNumber + 1
          }
          NoNumber=0
          g_strlog = g_strlog + '\r\n      </td>\r\n    </tr>\r\n    </table>\r\n  </td>\r\n</tr>\r\n</table>\r\n'
          CurTableIndent = 0
          InsideEqnArray = 0
          InLine = 1
          MathMode = 0
          break;

        case 6: // \nonumber
          NoNumber = 1
          break;

        case 7: // &
          if(InsideArray==1 || InsideEqnArray==1)
          {
            tmpString = Spaces(CurTableIndent-3)
            g_strlog = g_strlog + '\r\n' + tmpString + '    </td>\r\n' + tmpString + '  </tr>\r\n' + tmpString + '  </table>\r\n' + tmpString + '</td>\r\n' + tmpString + '<td>\r\n' + tmpString + '  <table cellspacing=0 >\r\n' + tmpString + '  <tr>\r\n' + tmpString + '    <td align=center>\r\n' + tmpString + '      '
          }
          else
          {
            g_strlog = g_strlog + '&' // this is to avoid bad treatments of &nbsp; in the latex source
          }
          break;

        case 8: // {
          g_strlog = g_strlog + '' 
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxNone
          break;
        case 9: // }
          // added loop to close implicit groups open by commands like \bf, \it etc... 
          while( ActionStack[BracketLevel]==ltxBold || ActionStack[BracketLevel]==ltxItalic || ActionStack[BracketLevel]==ltxSmall || ActionStack[BracketLevel]==ltxNormalSize || ActionStack[BracketLevel]==ltxLarge || ActionStack[BracketLevel]==ltxBigLarge || ActionStack[BracketLevel]==ltxHuge )
          {
            switch(ActionStack[BracketLevel])
            {
              case ltxBold:
                g_strlog = g_strlog + '</b>'
                break;
              case ltxItalic:
                g_strlog = g_strlog + '</i>'
                break;
              default:
                g_strlog = g_strlog + '</font>'
                break;
            } // end switch(ActionStack[BracketLevel]) for \bf, \it ... 
            ActionStack[BracketLevel] = ltxNone
            BracketLevel = BracketLevel-1
          } // end while(ActionStack[BracketLevel] in bold, italic, small ....) 


          // lookup if ending actions are to be done
          switch(ActionStack[BracketLevel])
          {
            case ltxNone:
              g_strlog = g_strlog + '' 
              break;

            case ltxGroup:
              ActionStack[BracketLevel] = ltxNone
              break;

            case ltxSubscript:
              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
              {
                g_strlog = g_strlog + '</sup>' // moves up the lower bound
                if(str.charAt(g_NextTokenPos + 1)!='^') // no upper bound => ending the boundaries
                {
                  SumOrProdPending = 0
                  SumOrProdBoundaryBracketLevel = 0
                  tmpString = Spaces(CurTableIndent-1) // new cell after the \sum or \prod
                  g_strlog = g_strlog + '%%&nbsp;<br>$$\r\n' 
                  g_strlog = g_strlog + tmpString + '</td>\r\n'
                  g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                  g_strlog = g_strlog + tmpString + '  ' 
                }
              }
              else
              {
                g_strlog = g_strlog + '</sub>'
              }

              if(BracketLevel==IntegralBoundaryBracketLevel && IntegralBoundaryPending==1)
              {
                CurTableIndent = CurTableIndent - 4
                tmpString = Spaces(CurTableIndent-1)
                g_strlog = g_strlog + '</sub>\r\n' 
                g_strlog = g_strlog + tmpString + '        </td>\r\n'
                g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
                g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
                g_strlog = g_strlog + tmpString + '    </td>\r\n' 
                g_strlog = g_strlog + tmpString + '  </tr>$$\r\n%%' // note the $$ that marks the end of the lower bound
                g_strlog = g_strlog + tmpString + '  <tr>\r\n'      // and the %% that marks the beginning of the upper bound
                g_strlog = g_strlog + tmpString + '    <td valign=top>\r\n' 
                g_strlog = g_strlog + tmpString + '      <table cellspacing=0 border=0>\r\n' // start of new expression table 
                g_strlog = g_strlog + tmpString + '      <tr>\r\n' 
                g_strlog = g_strlog + tmpString + '        <td nowrap align=center>\r\n'
                g_strlog = g_strlog + tmpString + '          ' 
                CurTableIndent = CurTableIndent + 4

                if(str.charAt(g_NextTokenPos + 1)!='^') // no upper bound => ending the boundary table
                {
                  IntegralBoundaryBracketLevel = 0
                  IntegralBoundaryPending=0
                  CurTableIndent = CurTableIndent - 4
                  tmpString = Spaces(CurTableIndent-1)
                  g_strlog = g_strlog + '&nbsp;\r\n'  // <= add a space to get a non-blank cell
                  g_strlog = g_strlog + tmpString + '        </td>\r\n'
                  g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
                  g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
                  g_strlog = g_strlog + tmpString + '    </td>\r\n' 
                  g_strlog = g_strlog + tmpString + '  </tr>$$\r\n' // $$ delimits the end of the upper bound
                  g_strlog = g_strlog + tmpString + '  </table>\r\n'     
                  g_strlog = g_strlog + tmpString + '</td>\r\n'  
                  g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                  g_strlog = g_strlog + tmpString + '  '
                } // end if(str.charAt(g_NextTokenPos + 1)!='^')

              }// end if(BracketLevel==IntegralBoundaryBracketLevel && IntegralBoundaryPending==1)

              ActionStack[BracketLevel] = ltxNone
              TextLevelAbsolute = TextLevelAbsolute - 1
              if(TextLevelAbsolute==0){TextLevel=0}
              break;

            case ltxSupscript:
              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending==1)
              {
                g_strlog = g_strlog + '</sub><br>$$' // moves down the upper bound
                SumOrProdPending = 0
                SumOrProdBoundaryBracketLevel = 0
                tmpString = Spaces(CurTableIndent-1) // new cell after the \sum or \prod
                g_strlog = g_strlog + '\r\n' 
                g_strlog = g_strlog + tmpString + '</td>\r\n'
                g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
              }
              else
              {
                g_strlog = g_strlog + '</sup>'
                if(SupscriptAfterRightDelimiter=1) // close cell after \right]^{...} 
                {
                  SupscriptAfterRightDelimiter=0
                  tmpString = Spaces(CurTableIndent-1) 
                  g_strlog = g_strlog + '\r\n' 
                  g_strlog = g_strlog + tmpString + '</td>\r\n'
                  g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                  g_strlog = g_strlog + tmpString + '  ' 
                }
              }

              if(IntegralBoundaryBracketLevel==BracketLevel && IntegralBoundaryPending==1)
              {
                IntegralBoundaryBracketLevel = 0
                IntegralBoundaryPending=0
                CurTableIndent = CurTableIndent - 4
                tmpString = Spaces(CurTableIndent-1)
                g_strlog = g_strlog + '</sup>\r\n'  
                g_strlog = g_strlog + tmpString + '        </td>\r\n'
                g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
                g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
                g_strlog = g_strlog + tmpString + '    </td>\r\n' 
                g_strlog = g_strlog + tmpString + '  </tr>$$\r\n' // $$ delimits the end of the upper bound
                g_strlog = g_strlog + tmpString + '  </table>\r\n'     
                g_strlog = g_strlog + tmpString + '</td>\r\n'  
                g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                g_strlog = g_strlog + tmpString + '  '
                 
              }// end if(BracketLevel==IntegralBoundaryBracketLevel && IntegralBoundaryPending==1)

              ActionStack[BracketLevel] = ltxNone
              TextLevelAbsolute = TextLevelAbsolute - 1
              if(TextLevelAbsolute==0){TextLevel=0}
              break;

            case ltxNumerator:            
              //finding denominator
              g_NextTokenPos = str.indexOf('{',g_NextTokenPos)
              if (g_NextTokenPos==-1)
              {
                document.MainForm.output.value = str.substring(0,g_NextTokenPos) 
                alert('Missing denominator')
                return false;
              }
              ActionStack[BracketLevel] = ltxDenominator
              BracketLevel = BracketLevel + 1 //anticipate on the decrement done after the switch command

              // ending numerator and starting denominator
              if(TextLevel != 0 || InLine==1)
              {
                g_strlog = g_strlog + ')\/('  // for sup(b)scripts
              }
              else
              {
                CurTableIndent = CurTableIndent - 2
                g_strlog = g_strlog + '\r\n'
                tmpString = Spaces(CurTableIndent)
                if(FracLevel > 1){g_strlog = g_strlog + tmpString + '  </font>\r\n'}
                g_strlog = g_strlog + tmpString + '  </td>\r\n'
                g_strlog = g_strlog + tmpString + '</tr>\r\n'
                g_strlog = g_strlog + tmpString + '</table>\r\n'
                g_strlog = g_strlog + tmpString + '<div class=hrcomp><hr noshade size=1></div>\r\n' 
                g_strlog = g_strlog + tmpString + '<table cellspacing=0 border=0 >\r\n'
                g_strlog = g_strlog + tmpString + '<tr>\r\n' 
                g_strlog = g_strlog + tmpString + '  <td nowrap align=center>\r\n'
                if(FracLevel > 1) { g_strlog = g_strlog + tmpString + '  <font size=\"-' + eval(FracLevel - 1) + '\">\r\n' }
                g_strlog = g_strlog + tmpString + '    ' //text inside frac is indented
                CurTableIndent = CurTableIndent + 2
              } // end if(TextLevel == 0)
              break;

            case ltxDenominator:
              if(TextLevel != 0  || InLine==1 )
              {
                g_strlog = g_strlog + ")" //for sup(b)scripts
              }
              else
              {
                 CurTableIndent = CurTableIndent - 3
                 g_strlog = g_strlog + '\r\n'
                 tmpString = Spaces(CurTableIndent)
                 if(FracLevel > 1){ g_strlog = g_strlog + tmpString + '    </font>\r\n'}
                 g_strlog = g_strlog + tmpString + '    </td>\r\n'
                 g_strlog = g_strlog + tmpString + '  </tr>\r\n'
                 g_strlog = g_strlog + tmpString + '  </table>\r\n'
                 g_strlog = g_strlog + tmpString + '</td>\r\n' 
                 g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' + tmpString + '  '
                 CurTableIndent = CurTableIndent + 1
              } // end if(TextLevel == 0)
              ActionStack[BracketLevel] = ltxNone
              FracLevel = FracLevel - 1
              break;

            case ltxSqrt:
              if(TextLevel==0 && InLine==0)
              {
                CurTableIndent = CurTableIndent -5
                tmpString = Spaces(CurTableIndent)
                g_strlog = g_strlog + '\r\n' + tmpString + '        </td>\r\n' + tmpString + '       </tr>\r\n' + tmpString + '      </table>\r\n'
                g_strlog = g_strlog + tmpString + '    </td>\r\n'+tmpString+'  </tr>\r\n'+tmpString+'  </table>\r\n'+tmpString+'</td>\r\n'+tmpString+'<td nowrap align=center>\r\n'+tmpString+'  &nbsp;'
              }
              else
              {
                g_strlog = g_strlog + '</span>'
              }
              ActionStack[BracketLevel] = ltxNone
              break;

            case ltxInt:
              ActionStack[BracketLevel] = ltxNone
              break;

            case ltxLefteqn:
              g_strlog = g_strlog + '\r\n  </td>\r\n</tr>\r\n<tr>\r\n  <td>\r\n'
              ActionStack[BracketLevel] = ltxNone
              break;

            case ltxStackRelUpperText:
              //find the beginning of the main text
              g_NextTokenPos = str.indexOf('{',g_NextTokenPos)
              if (g_NextTokenPos==-1)
              {
                document.MainForm.output.value = str.substring(0,g_NextTokenPos) 
                alert('Missing stackrel main text')
                return false;
              }
              ActionStack[BracketLevel] = ltxStackRelMainText
              BracketLevel = BracketLevel + 1 //anticipate on the decrement done after the switch command
 
              // draw the symbol(s) that is under the main text
              g_strlog = g_strlog + '</small></span><span style=\"position:relative;left:-5pt;\">'
              break;

            case ltxStackRelMainText:
              g_strlog = g_strlog +  '</span>'               
              ActionStack[BracketLevel] = ltxNone
              break;

            case ltxOverLine:
              g_strlog = g_strlog +  '</span>'               
              ActionStack[BracketLevel] = ltxNone
              break;
             
            case ltxBibitem:
              g_strlog = g_strlog +  '</span>'               
              ActionStack[BracketLevel] = ltxNone
              break;
             
            case ltxSection:
              g_strlog = g_strlog + '</h1>'       
              g_TOC += '<p><a href=\"#toc.'+SectionNumber+'\">' + g_strlog.substr(SectionStartPos)+'</a>\r\n'          
              ActionStack[BracketLevel] = ltxNone
              break;
             
            case ltxSubSection:
              g_strlog = g_strlog +  '</h2>'      
              g_TOC += '<p><a href=\"#toc.'+SectionNumber+'.'+SubSectionNumber+'\">' + g_strlog.substr(SectionStartPos) +'</a>\r\n'         
              ActionStack[BracketLevel] = ltxNone
              break;
             
            case ltxSubSubSection:
              g_strlog = g_strlog +  '</h3>'
              g_TOC += '<p><a href=\"#toc.'+SectionNumber+'.'+SubSectionNumber+'.'+SubSubSectionNumber+'\">' + g_strlog.substr(SectionStartPos) +'</a>\r\n'          
              ActionStack[BracketLevel] = ltxNone
              break;
             
            case ltxFootnote:
              g_strlog = g_strlog +  '</span>'               
              ActionStack[BracketLevel] = ltxNone
              break;
             
            case ltxVector:
              g_strlog = g_strlog + '</span>' 
              ActionStack[BracketLevel] = ltxNone
              break;

          } // end switch(ActionStack[BracketLevel])

          BracketLevel = BracketLevel - 1
          if(BracketLevel < 0) // Bracket nesting error
          { 
            document.MainForm.input.SelStart = g_NextTokenPos - 1
            document.MainForm.input.SelLength = tokens[nexttokenid].length 
            document.MainForm.output.value = str.substring(0,g_NextTokenPos) 
            alert('Too many }. Ignore?') 
            return false;
          } // end if(BracketLevel < 0)
          break;

        case 10: // $
          MathMode = 1 - MathMode
          break;

        case 11: // $$
          MathMode = 1 - MathMode
          if(MathMode==1)
          {
            InLine = 0
            g_strlog = g_strlog + '\r\n<table cellspacing=0  border=0 align=center>\r\n<tr>\r\n  <td nowrap align=center>\r\n    '
            CurTableIndent = 2
          }
          else
          {
            InLine = 1 // back to normal inline text
            g_strlog = g_strlog + '\r\n  </td>\r\n</tr>\r\n</table>\r\n'
            CurTableIndent = 0
          }
          break;

        case 12: // \begin{equation}
          MathMode = 1
          InLine = 0
          g_strlog = g_strlog + '\r\n<table cellspacing=0  border=0 align=center>\r\n<tr>\r\n  <td nowrap align=\"center\">\r\n    '
          CurTableIndent = 2
          break;

        case 13: // \end{equation}
          if(NoNumber==0)
          {
            g_strlog = g_strlog + '<a name=\"eq' + EquationNumber + '\">&nbsp;&nbsp;&nbsp;&nbsp;<font color=blue>(' + EquationNumber + ')</font>'
            EquationNumber = EquationNumber + 1
          }
          NoNumber=0
          g_strlog = g_strlog + '\r\n  </td>\r\n</tr>\r\n</table>\r\n'
          CurTableIndent = 0
          MathMode = 0
          InLine = 1
          break;

        case 14: // \over
          g_strlog = g_strlog + ' \/ '
          break;

        case 15: // \frac{
          LineHeight = LineHeight + 1
          FracLevel = FracLevel + 1
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxNumerator
          if(TextLevel !=0 || InLine==1)
          {
            g_strlog = g_strlog + '('
            break;
          }
          tmpString = Spaces(CurTableIndent-1) 
          g_strlog = g_strlog + '\r\n' + tmpString + '</td>\r\n' 
          g_strlog = g_strlog + tmpString + '<td nowrap align=\"center\">\r\n'
          g_strlog = g_strlog + tmpString + '  <table cellspacing=0 border=0 >\r\n'
          g_strlog = g_strlog + tmpString + '  <tr>\r\n'
          g_strlog = g_strlog + tmpString + '    <td nowrap align=\"center\">\r\n' 
          if(FracLevel > 1){ g_strlog = g_strlog + tmpString + '    <font size=\"-' + eval(FracLevel - 1) + '\">\r\n'}
          g_strlog = g_strlog + tmpString + '      ' // text inside frac is indented
          CurTableIndent = CurTableIndent + 2
          break;

        case 16: // ^
          BracketLevel = BracketLevel + 1
          tmpString = str.charAt(g_NextTokenPos + 1)
          switch(tmpString)
          {
            case '{':
              if(TextLevelAbsolute==0){TextLevel = 1}
              TextLevelAbsolute = TextLevelAbsolute + 1
              ActionStack[BracketLevel] = ltxSupscript

              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
              {
                g_strlog = g_strlog + '%%<sub>' // moves down the upper bound
              }
              else
              {
                g_strlog = g_strlog + '<sup>'
              }
              g_NextTokenPos = g_NextTokenPos + 1
              break;

            case '\\':  
              if(TextLevelAbsolute==0){TextLevel = 1}
              TextLevelAbsolute = TextLevelAbsolute + 1
              ActionStack[BracketLevel] = ltxGhostSupscript
              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
              {
                g_strlog = g_strlog + '%%<sub>' // moves down the upper bound
              }
              else
              {
                g_strlog = g_strlog + '<sup>'
              }
              break;

            default:
              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
              {
                g_strlog = g_strlog + '%%<sub>' + tmpString + '</sub><br>$$' // moves down the upper bound
                SumOrProdPending = 0
                SumOrProdBoundaryBracketLevel = 0
                tmpString = Spaces(CurTableIndent-1) // new cell after the \sum or \prod
                g_strlog = g_strlog + '\r\n' 
                g_strlog = g_strlog + tmpString + '</td>\r\n'
                g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                g_strlog = g_strlog + tmpString + '  ' 
              }
              else
              {
                g_strlog = g_strlog + '<sup>' + tmpString + '</sup>'
                if(SupscriptAfterRightDelimiter==1) // close cell after command like \right]^X
                {
                  SupscriptAfterRightDelimiter=0
                  tmpString = Spaces(CurTableIndent-1) 
                  g_strlog = g_strlog + '\r\n' 
                  g_strlog = g_strlog + tmpString + '</td>\r\n'
                  g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                  g_strlog = g_strlog + tmpString + '  ' 
                }
              }

              if(IntegralBoundaryBracketLevel== BracketLevel && IntegralBoundaryPending==1) //we must end the upper bound of a big integral
              {
                IntegralBoundaryBracketLevel = 0
                IntegralBoundaryPending = 0
                CurTableIndent = CurTableIndent - 4
                tmpString = Spaces(CurTableIndent-1)
                g_strlog = g_strlog + '\r\n'
                g_strlog = g_strlog + tmpString + '        </td>\r\n'
                g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
                g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
                g_strlog = g_strlog + tmpString + '    </td>\r\n' 
                g_strlog = g_strlog + tmpString + '  </tr>$$\r\n' // $$ delimits the end of the upper bound
                g_strlog = g_strlog + tmpString + '  </table>\r\n'     
                g_strlog = g_strlog + tmpString + '</td>\r\n'  
                g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                g_strlog = g_strlog + tmpString + '  '
              }
              BracketLevel = BracketLevel - 1
              g_NextTokenPos = g_NextTokenPos + 1
              break;
          } // end switch(tmpString)
          break;

        case 17: // _ 
          BracketLevel = BracketLevel + 1
          // treat "as is" outside of math mode
          if(MathMode==0)
          {
             g_strlog = g_strlog + '_'
             break;
          }
          // case of \sum_{... }^{...} or \prod_{... }^{...}
//          if((previoustokenid==126 || previoustokenid==127) && TextLevelAbsolute==0 && InLine==0 && FracLevel==0)
          if((str.substring(g_TokenStartPos-4,g_TokenStartPos-1) =='\sum' || str.substring(g_TokenStartPos-5,g_TokenStartPos-1) =='\prod') && TextLevelAbsolute==0 && InLine==0 && FracLevel==0)
          {
            SumOrProdPending = 1
            SumOrProdBoundaryBracketLevel = BracketLevel 
            g_strlog = g_strlog + '<br>\r\n'  // The lower bound is positionned just with a newline tag.
          } // end if((previoustokenid==126 || previoustokenid==127) && TextLevelAbsolute==0 && InLine==0 && FracLevel==0)

          // case of \int_{... }^{...} 
//          if((previoustokenid==118 || previoustokenid==119) && TextLevelAbsolute==0 && InLine==0 && FracLevel==0)
          if((str.substring(g_TokenStartPos-4,g_TokenStartPos-1) =='\int' || str.substring(g_TokenStartPos-5,g_TokenStartPos-1)=='\oint') && TextLevelAbsolute==0 && InLine==0 && FracLevel==0)
          {
            IntegralBoundaryBracketLevel = BracketLevel // backup bracket level to detect the end of the block 
            IntegralBoundaryPending = 1
            tmpString = Spaces(CurTableIndent-1)
            g_strlog = g_strlog + '\r\n' 
            g_strlog = g_strlog + tmpString + '</td>\r\n' 
            g_strlog = g_strlog + tmpString + '<td nowrap align=left>\r\n'
            g_strlog = g_strlog + tmpString + '  <table cellspacing=0 >\r\n__' // note the undescorses that marks the beginnig of the lower bound
            g_strlog = g_strlog + tmpString + '  <tr>\r\n'
            g_strlog = g_strlog + tmpString + '    <td valign=bottom>\r\n' 
            g_strlog = g_strlog + tmpString + '      <table cellspacing=0 border=0 >\r\n' // start of new expression table 
            g_strlog = g_strlog + tmpString + '      <tr>\r\n' 
            g_strlog = g_strlog + tmpString + '        <td nowrap align=center>\r\n' 
            g_strlog = g_strlog + tmpString + '          '
            CurTableIndent = CurTableIndent + 4
          } // end if(previoustokenid==118 && TextLevelAbsolute==0 && InLine==0 && FracLevel==0)


          tmpString = str.charAt(g_NextTokenPos + 1)
          switch(tmpString)
          {
            case '{':
              if(TextLevelAbsolute==0){TextLevel = -1}
              TextLevelAbsolute = TextLevelAbsolute + 1
              ActionStack[BracketLevel] = ltxSubscript
              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
              {
                g_strlog = g_strlog + '<sup>' // moves up the lower bound
              }
              else
              {
                g_strlog = g_strlog + '<sub>'
              }
              g_NextTokenPos = g_NextTokenPos + 1
              break;

            case '\\':

              if(TextLevelAbsolute==0){TextLevel = -1}
              TextLevelAbsolute = TextLevelAbsolute + 1
              ActionStack[BracketLevel] = ltxGhostSubscript
              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
              {
                g_strlog = g_strlog + '<sup>' // moves up the lower bound
              }
              else
              {
                g_strlog = g_strlog + '<sub>'
              }
              break;

            default:  // consider only the character following the underscore
              if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
              {
                g_strlog = g_strlog + '<sup>' + tmpString + '</sup>' // moves up the lower bound
                if(str.charAt(g_NextTokenPos + 2)!='^') // no upper bound => ending the boundaries
                {
                  SumOrProdPending = 0
                  SumOrProdBoundaryBracketLevel = 0
                  tmpString = Spaces(CurTableIndent-1) // new cell after the \sum or \prod
                  g_strlog = g_strlog + '%%&nbsp;<br>$$\r\n' 
                  g_strlog = g_strlog + tmpString + '</td>\r\n'
                  g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                  g_strlog = g_strlog + tmpString + '  ' 
                }
              }
              else
              {
                g_strlog = g_strlog + '<sub>' + tmpString + '</sub>'
              }
              
              if(IntegralBoundaryBracketLevel== BracketLevel && IntegralBoundaryPending == 1)
              {
                CurTableIndent = CurTableIndent - 4
                tmpString = Spaces(CurTableIndent-1)
                g_strlog = g_strlog + '\r\n' 
                g_strlog = g_strlog + tmpString + '        </td>\r\n'
                g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
                g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
                g_strlog = g_strlog + tmpString + '    </td>\r\n' 
                g_strlog = g_strlog + tmpString + '  </tr>$$\r\n%%' // note the $$ that marks the end of the lower bound
                g_strlog = g_strlog + tmpString + '  <tr>\r\n'      // and the %% that marks the beginning of the upper bound
                g_strlog = g_strlog + tmpString + '    <td valign=top>\r\n' 
                g_strlog = g_strlog + tmpString + '      <table cellspacing=0 border=0 >\r\n' // start of new expression table 
                g_strlog = g_strlog + tmpString + '      <tr>\r\n' 
                g_strlog = g_strlog + tmpString + '        <td nowrap align=center>\r\n' 
                g_strlog = g_strlog + tmpString + '          '
                CurTableIndent = CurTableIndent + 4

                // note that here we must skip the char after the _ to look for the ^
                if(str.charAt(g_NextTokenPos + 2)!='^') // no upper bound => ending the boundary table
                {
                  IntegralBoundaryBracketLevel = 0
                  IntegralBoundaryPending = 0
                  CurTableIndent = CurTableIndent - 4
                  tmpString = Spaces(CurTableIndent-1)
                  g_strlog = g_strlog + '&nbsp;\r\n'  // <= add a space to get a non-blank cell
                  g_strlog = g_strlog + tmpString + '        </td>\r\n'
                  g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
                  g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
                  g_strlog = g_strlog + tmpString + '    </td>\r\n' 
                  g_strlog = g_strlog + tmpString + '  </tr>$$\r\n' // $$ delimits the end of the upper bound
                  g_strlog = g_strlog + tmpString + '  </table>\r\n'     
                  g_strlog = g_strlog + tmpString + '</td>\r\n'  
                  g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
                  g_strlog = g_strlog + tmpString + '  '
                } // end if(str.charAt(g_NextTokenPos + 2)!='^')
 
              }// end if(IntegralBoundaryBracketLevel== BracketLevel && IntegralBoundaryPending == 1)

              BracketLevel = BracketLevel - 1
              g_NextTokenPos = g_NextTokenPos + 1 // skip the char after the _ 
              break;
          } // end switch(tmpString)
          break;

        case 18: // '
          g_strlog = g_strlog + MATHI(162)
          break;

        case 19: // \matrix{
          BracketLevel = BracketLevel + 1
          break;

        case 20:// \bf
          g_strlog = g_strlog + '<b>'
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxBold
          if(str.charAt(g_NextTokenPos+3)==' ') // swallows a space if there's a space after
          {
            g_NextTokenPos = g_NextTokenPos+4
            TokenLength=0
          }
          break;

        case 21:// \it
          g_strlog = g_strlog + '<i>'
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxItalic
          if(str.charAt(g_NextTokenPos+3)==' ') // swallows a space if there's a space after
          {
            g_NextTokenPos = g_NextTokenPos+4
            TokenLength=0
          }
          break;

        case 22:// \alpha
          g_strlog = g_strlog + MATHS('a')
          break;

        case 23:// \beta
          g_strlog = g_strlog + MATHS('b')
          break;

        case 24:// \gamma
          g_strlog = g_strlog + MATHS('g')
          break;

        case 25:// \delta
          g_strlog = g_strlog + MATHS('d')
          break;

        case 26:// \epsilon
          g_strlog = g_strlog + MATHS('e')
          break;

        case 27:// \varepsilon
          g_strlog = g_strlog + MATHS('e')
          break;

        case 28:// \zeta
          g_strlog = g_strlog + MATHS('z')
          break;

        case 29:// \eta
          g_strlog = g_strlog + MATHS('h')
          break;

        case 30:// \theta
          g_strlog = g_strlog + MATHS('q')
          break;

        case 31:// \vartheta
          g_strlog = g_strlog + MATHS('J')
          break;

        case 32:// \iota
          g_strlog = g_strlog + MATHS('i')
          break;

        case 33:// \kappa
          g_strlog = g_strlog + MATHS('k')
          break;

        case 34:// \lambda
          g_strlog = g_strlog + MATHS('l')
          break;

        case 35:// \mu
          g_strlog = g_strlog + MATHS('m')
          break;

        case 36:// \nu
          g_strlog = g_strlog + MATHS('n')
          break;

        case 37:// \xi
          g_strlog = g_strlog + MATHS('x')
          break;

        case 38:// \pi
          g_strlog = g_strlog + MATHS('p')
          break;

        case 39:// \varpi
          g_strlog = g_strlog + MATHS('v')
          break;

        case 40:// \rho
          g_strlog = g_strlog + MATHS('r')
          break;

        case 41:// \varrho
          g_strlog = g_strlog + MATHS('r')
          break;

        case 42:// \sigma
          g_strlog = g_strlog + MATHS('s')
          break;

        case 43:// \varsigma
          g_strlog = g_strlog + MATHS('V')
          break;

        case 44:// \tau
          g_strlog = g_strlog + MATHS('t')
          break;

        case 45:// \upsilon
          g_strlog = g_strlog + MATHS('u')
          break;

        case 46:// \phi
          g_strlog = g_strlog + MATHS('f')
          break;

        case 47:// \varphi
          g_strlog = g_strlog + MATHS('j')
          break;

        case 48:// \chi
          g_strlog = g_strlog + MATHS('c')
          break;

        case 49:// \psi
          g_strlog = g_strlog + MATHS('y')
          break;

        case 50:// \omega
          g_strlog = g_strlog + MATHS('w')
          break;

        case 51:// \Gamma
          g_strlog = g_strlog + MATHS('G')
          break;

        case 52:// \Delta
          g_strlog = g_strlog + MATHS('D')
          break;

        case 53:// \Theta
          g_strlog = g_strlog + MATHS('Q')
          break;

        case 54:// \Lambda
          g_strlog = g_strlog + MATHS('L')
          break;

        case 55:// \Xi
          g_strlog = g_strlog + MATHS('X')
          break;

        case 56:// \Pi
          g_strlog = g_strlog + MATHS('P')
          break;

        case 57:// \Sigma
          g_strlog = g_strlog + MATHS('S')
          break;

        case 58:// \Upsilon
          g_strlog = g_strlog + MATHS('U')
          break;

        case 59:// \Phi
          g_strlog = g_strlog + MATHS('F')
          break;

        case 60:// \Psi
          g_strlog = g_strlog + MATHS('Y')
          break;

        case 61:// \Omega
          g_strlog = g_strlog + MATHS('W')
          break;

        case 62:// \ell
          g_strlog = g_strlog + '<i>l</i>'
          break;

        case 63:// \aleph
          g_strlog = g_strlog + MATHI(192)
          break;

        case 64:// \i
          g_strlog = g_strlog + MATHS('i') // like a iota
          break;

        case 65:// \j
          g_strlog = g_strlog + 'j'
          break;

        case 66:// \wp
          g_strlog = g_strlog + MATHI(195)
          break;

        case 67:// \re
          g_strlog = g_strlog + MATHI(194)
          break;

        case 68:// \im
          g_strlog = g_strlog + MATHI(193)
          break;

        case 69:// \partial
          g_strlog = g_strlog + MATHI(182)
          break;

        case 70:// \infty
          g_strlog = g_strlog + MATHI(165)
          break;

        case 71:// \angle
          g_strlog = g_strlog + MATHI(208)
          break;

        case 72:// \langle
          g_strlog = g_strlog + MATHI(225)
          break;

        case 73:// \rangle
          g_strlog = g_strlog + MATHI(241)
          break;

        case 74:// '
          g_strlog = g_strlog + MATHI(162) // doublon with 18 => to be changed
          break;

        case 75:// \emptyset
          g_strlog = g_strlog + MATHI(198)
          break;

        case 76:// \nabla
          g_strlog = g_strlog + MATHI(209)
          break;

        case 77:// \surd
          g_strlog = g_strlog + MATHI(214)
          break;

        case 78:// \vert
          g_strlog = g_strlog + MATHS('|')
          break;

        case 79:// \Vert
          g_strlog = g_strlog + MATHS('||')
          break;

        case 80: // \{
          g_strlog = g_strlog + '{'
          break;

        case 81: // \}
          g_strlog = g_strlog + '}'
          break;

        case 82: // \[
          g_strlog = g_strlog + '['
          break;

        case 83: // \]
          g_strlog = g_strlog + ']'
          break;

        case 84: // \rceil
          g_strlog = g_strlog + MATHI(249)
          break;

        case 85: // \rfloor
          g_strlog = g_strlog + MATHI(251)
          break;

        case 86: // \lceil
          g_strlog = g_strlog + MATHI(233)
          break;

        case 87: // \lfloor
          g_strlog = g_strlog + MATHI(235)
          break;

        case 88: // \backslash
          g_strlog = g_strlog + '\\'
          break;

        case 89: // \forall
          g_strlog = g_strlog + MATHS('\"')
          break;

        case 90: // \exists
          g_strlog = g_strlog + MATHS('$')
          break;

        case 91: // \neg
          g_strlog = g_strlog + MATHI(216)
          break;

        case 92: // \clubsuit
          g_strlog = g_strlog + MATHI(167)
          break;

        case 93: // \diamondsuit
          g_strlog = g_strlog + MATHI(168)
          break;

        case 94: // \heartsuit
          g_strlog = g_strlog + MATHI(169)
          break;

        case 95: // \spadesuit
          g_strlog = g_strlog + MATHI(170)
          break;

        case 96: // \top
          g_strlog = g_strlog + 'T'
          break;

        case 97: // \perp
          g_strlog = g_strlog + MATHS('^')
          break;

        case 98: // \circ
          g_strlog = g_strlog + MATHI(176)
          break;

        case 99: // \~
          g_strlog = g_strlog + '&#126;'
          break;

        case 100: // \sim
          g_strlog = g_strlog + ' ' + MATHS('&#126;') + " " // this to avoid replacement of ~ by &nbsp;
          break;

        case 101: // <
          if(MathMode==1)
          {
            g_strlog = g_strlog + '&lt;'
          }
          else
          {
            g_strlog = g_strlog + '<'
          }
          break;

        case 102: // >
          if(MathMode==1)
          {
            g_strlog = g_strlog + '&gt;'
          }
          else
          {
            g_strlog = g_strlog + '>'
          }
          break;

        case 103: // \ll
          g_strlog = g_strlog + '&lt;&lt;'
          break;

        case 104: // \gg
          g_strlog = g_strlog + '&gt;&gt;'
          break;

        case 105: // \star
          g_strlog = g_strlog + MATHS('*')
          break;

        case 106: // \diamond
          g_strlog = g_strlog + MATHI(224)
          break;

        case 107: // \bullet
          g_strlog = g_strlog + MATHI(183)
          break;

        case 108: // \cdot
          g_strlog = g_strlog + '&#183;'
          break;

        case 109: // \cup
          g_strlog = g_strlog + MATHI(200)
          break;

        case 110: // \cap
          g_strlog = g_strlog + MATHI(199)
          break;

        case 111: // \pm
          g_strlog = g_strlog + MATHI(177)
          break;

        case 112: // \mp
          g_strlog = g_strlog + '<span style=\"position:relative;top:-4pt;left:3pt;\"><font face=symbol>-</font></span><span style=\"position:relative;left:-3pt;\"><small>+</small></span>'
          break;

        case 113: // \vee
          g_strlog = g_strlog + MATHI(218)
          break;

        case 114: // \wedge
          g_strlog = g_strlog + MATHI(217)
          break;

        case 115: // \oplus
          g_strlog = g_strlog + MATHI(197)
          break;

        case 116: // \otimes
          g_strlog = g_strlog + MATHI(196)
          break;

        case 117: // \oslash
          g_strlog = g_strlog + MATHI(198)
          break;

        case 118: // \int
          if( (TextLevelAbsolute != 0) || (FracLevel > 0) || (InLine==1) )
          {
            g_strlog = g_strlog + MATHI(242)
          }
          else
          {
            g_strlog = g_strlog + delimit('&#242;', 2, CurTableIndent-1)
          }
          break;

        case 119: // \oint
          if( (TextLevelAbsolute != 0) || (FracLevel > 0) || (InLine==1) )
          {
            g_strlog = g_strlog + '<span style=\"position:relative;top:-2;left:+5pt;\">o</span><font face=symbol>&#242;</font>'
          }
          else
          {
              tmpString = Spaces(CurTableIndent-1)
              g_strlog = g_strlog + '\r\n' + tmpString +'</td>\r\n' + tmpString + '<td align=left class=cl>\r\n'+ tmpString +'  <span style=\"position:relative;top:-11;left:+10pt;\"><b>O</b></span><font face=symbol size=+4>&#242;</font>\r\n' + tmpString + '</td>\r\n' + tmpString +'<td nowrap align=center>\r\n' +tmpString +'  '
          }
          break;

        case 120: // \bigcap
          g_strlog = g_strlog + MATHI(199)
          break;

        case 121: // \bigcup
          g_strlog = g_strlog + MATHI(200)
          break;

        case 122: // \bigvee
          g_strlog = g_strlog + MATHI(218)
          break;

        case 123: // \bigwedge
          g_strlog = g_strlog + MATHI(217)
          break;

        case 124: // \bigotimes
          g_strlog = g_strlog + MATHI(196)
          break;

        case 125: // \bigoplus 
          g_strlog = g_strlog + MATHI(197)
          break;

        case 126: // \sum
          if( (TextLevelAbsolute != 0) || (FracLevel > 0) || (InLine==1) )
          {
            g_strlog = g_strlog + MATHI(229) 
          }
          else
          {
            tmpString = Spaces(CurTableIndent-1) // new cell before the \sum or \prod
            g_strlog = g_strlog + '\r\n' 
            g_strlog = g_strlog + tmpString + '</td>\r\n'
            g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
            g_strlog = g_strlog + tmpString + '  '

            g_strlog = g_strlog + '<!--UB-->' + MATHI(229) // the comment locates the position at which the upper bound must be placed if any 
          }
          break;

        case 127: // \prod
          if( (TextLevelAbsolute != 0) || (FracLevel > 0) || (InLine==1) )
          {
            g_strlog = g_strlog + MATHI(213) 
          }
          else
          {
            tmpString = Spaces(CurTableIndent-1) // new cell before the \sum or \prod
            g_strlog = g_strlog + '\r\n' 
            g_strlog = g_strlog + tmpString + '</td>\r\n'
            g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
            g_strlog = g_strlog + tmpString + '  '

            g_strlog = g_strlog + '<!--UB-->' + MATHI(213) // the comment locates the position at which the upper bound must be placed if any 
          }
          break;

        case 128: // \not\subset 
          g_strlog = g_strlog + ' ' + MATHI(203) + ' '
          break;

        case 129: // \div
          g_strlog = g_strlog + MATHI(184)
          break;

        case 130: // \times
          g_strlog = g_strlog + '&times;' // MATHI(180)
          break;

        case 131: // \lesim
          g_strlog = g_strlog + ' &lt;~'
          break;

        case 132: // \gesim
          g_strlog = g_strlog + ' &gt;~'
          break;

        case 133: // \mid
          g_strlog = g_strlog + ' | '
          break;

        case 134: // \leq
          g_strlog = g_strlog + ' ' + MATHI(163) + ' '
          break;

        case 135: // \geq
          g_strlog = g_strlog + ' ' + MATHI(179) + ' '
          break;

        case 136: // \le
          g_strlog = g_strlog + ' ' + MATHI(163) + ' '
          break;

        case 137: // \ge
          g_strlog = g_strlog + ' ' + MATHI(179) + ' '
          break;

        case 138: // \equiv
          g_strlog = g_strlog + ' ' + MATHI(186) + ' '
          break;

        case 139: // \approx
          g_strlog = g_strlog + ' ' + MATHI(187) + ' '
          break;

        case 140: // \neq
          g_strlog = g_strlog + ' ' + MATHI(185) + ' '
          break;

        case 141: // \subset
          g_strlog = g_strlog + ' ' + MATHI(204) + ' '
          break;

        case 142: // \subseteq
          g_strlog = g_strlog + ' ' + MATHI(205) + ' '
          break;

        case 143: // \supset
          g_strlog = g_strlog + ' ' + MATHI(201) + ' '
          break;

        case 144: // \supseteq
          g_strlog = g_strlog + ' ' + MATHI(202) + ' '
          break;

        case 145: // \in
          g_strlog = g_strlog + ' ' + MATHI(206) + ' '
          break;

        case 146: // \notin
          g_strlog = g_strlog + ' ' + MATHI(207) + ' '
          break;

        case 147: // \owns
          g_strlog = g_strlog + ' ' + MATHI(39) + ' '
          break;

        case 148: // \cong (congruent to)
          g_strlog = g_strlog + ' ' + MATHI(64) + ' '
          break;

        case 149: // \propto (proportional to)
          g_strlog = g_strlog + ' ' + MATHI(181) + ' '
          break;

        case 150: // \leftarrow
          g_strlog = g_strlog + MATHI(172)
          break;

        case 151: // \rightarrow
          g_strlog = g_strlog + MATHI(174)
          break;

        case 152: // \uparrow
          g_strlog = g_strlog + MATHI(173)
          break;

        case 153: // \downarrow
          g_strlog = g_strlog + MATHI(175)
          break;

        case 154: // \leftrightarrow
          g_strlog = g_strlog + MATHI(171)
          break;

        case 155: // \Leftarrow
          g_strlog = g_strlog + MATHI(220)
          break;

        case 156: // \Rightarrow
          g_strlog = g_strlog + MATHI(222)
          break;

        case 157: // \Leftrightarrow
          g_strlog = g_strlog + MATHI(219)
          break;

        case 158: // \Uparrow
          g_strlog = g_strlog + MATHI(221)
          break;

        case 159: // \Downarrow
          g_strlog = g_strlog + MATHI(223)
          break;

        case 160: // \ldots
          g_strlog = g_strlog + MATHI(188)
          break;

        case 161: // \vdots
          g_strlog = g_strlog + ':'
          break;

        case 162: // \dagger
          g_strlog = g_strlog + '&#134;'
          break;

        case 163: // \ddagger
          g_strlog = g_strlog + '&#135;'
          break;

        case 164: // \lim
          g_strlog = g_strlog + 'lim'
          break;

        case 165: // \overbrace{
          // TO BE DONE (see \vec )
          BracketLevel = BracketLevel + 1
          break;

        case 166: // \underbrace{
          // TO BE DONE (see \vec)
          BracketLevel = BracketLevel + 1
          break;

        case 167: // \overline{
          g_strlog = g_strlog + '<span style=\"border-top:1 solid black;\">' 
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxOverLine
          break;

        case 168: // \bar (similar to \vec)
          // get next non-blank character
          OffsetPosition = 0
          do
          {
            OffsetPosition = OffsetPosition + 1
            tmpString = str.charAt(g_NextTokenPos + 3 + OffsetPosition)
          }
          while( tmpString.charCodeAt(0) < 33 ) // catches spaces, tabs, cr, lf ...  
          g_strlog = g_strlog + '<span style=\"position:relative;top:-6pt;left:4pt;\"><font face=symbol>-</font></span><span style=\"position:relative;left:-4pt;\">'
          switch(tmpString)
          {
            case '\\':
              // Start waiting for next token before ending the </span> tag
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxGhostVector
              break;
            case '{':
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxVector
              g_NextTokenPos = g_NextTokenPos + 3 + OffsetPosition+1
              TokenLength=0
              break;
            default:
             g_strlog = g_strlog + tmpString + '</span>'
             g_NextTokenPos = g_NextTokenPos + OffsetPosition
             break;
          } // end switch(tmpString)

          break;

        case 169: // \vec
          // get next non-blank character
          OffsetPosition = 0
          do
          {
            OffsetPosition = OffsetPosition + 1
            tmpString = str.charAt(g_NextTokenPos + 3 + OffsetPosition)
          }
          while( tmpString.charCodeAt(0) < 33 ) // catches spaces, tabs, cr, lf ...  
          g_strlog = g_strlog + '<span style=\"position:relative;top:-9pt;left:6pt;\"><font face=\"symbol\" size=\"-1\">&#174;</font></span><span style=\"position:relative;left:-5pt;\">'
          switch(tmpString)
          {
            case '\\':
              // Start waiting for next token before ending the </span> tag
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxGhostVector
              break;
            case '{':
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxVector
              g_NextTokenPos = g_NextTokenPos + 3 + OffsetPosition+1
              TokenLength=0
              break;
            default:
             g_strlog = g_strlog + tmpString + '</span>'
             g_NextTokenPos = g_NextTokenPos + OffsetPosition
             break;
          } // end switch(tmpString)
          break;

        case 170: // \tilde (see \vec)
          // get next non-blank character
          OffsetPosition = 0
          do
          {
            OffsetPosition = OffsetPosition + 1
            tmpString = str.charAt(g_NextTokenPos + 5 + OffsetPosition)
          }
          while( tmpString.charCodeAt(0) < 33 ) // catches spaces, tabs, cr, lf ...  
          g_strlog = g_strlog + '<span style=\"position:relative;top:-7pt;left:4pt;\"><font face=symbol>&#126;</font></span><span style=\"position:relative;left:-4pt;\">'
          switch(tmpString)
          {
            case '\\':
              // Start waiting for next token before ending the </span> tag
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxGhostVector
              break;
            case '{':
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxVector
              g_NextTokenPos = g_NextTokenPos + 3 + OffsetPosition+1
              TokenLength=0
              break;
            default:
             g_strlog = g_strlog + tmpString + '</span>'
             g_NextTokenPos = g_NextTokenPos + OffsetPosition
             break;
          } // end switch(tmpString)
          break;

        case 171: // \hat (see \vec)
          // get next non-blank character
          OffsetPosition = 0
          do
          {
            OffsetPosition = OffsetPosition + 1
            tmpString = str.charAt(g_NextTokenPos + 3 + OffsetPosition)
          }
          while( tmpString.charCodeAt(0) < 33 ) // catches spaces, tabs, cr, lf ...  
          g_strlog = g_strlog + '<span style=\"position:relative;top:-7pt;left:4pt;\">^</span><span style=\"position:relative;left:-4pt;\">'
          switch(tmpString)
          {
            case '\\':
              // Start waiting for next token before ending the </span> tag
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxGhostVector
              break;
            case '{':
              BracketLevel = BracketLevel + 1
              ActionStack[BracketLevel] = ltxVector
              g_NextTokenPos = g_NextTokenPos + 3 + OffsetPosition+1
              TokenLength=0
              break;
            default:
             g_strlog = g_strlog + tmpString + '</span>'
             g_NextTokenPos = g_NextTokenPos + OffsetPosition
             break;
          } // end switch(tmpString)
          break;

        case 172: // \sqrt{
          tmpString = Spaces(CurTableIndent-1)
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxSqrt
          if(TextLevel==0 && InLine==0)
          {
            g_strlog = g_strlog + '\r\n' + tmpString + '</td>\r\n'  + tmpString+ '<td>\r\n'+tmpString+'  <table cellspacing=0 border=0 >\r\n'+tmpString+'  <tr>\r\n'+tmpString+'    <td valign=bottom align=right style=\"border:none;\"><font size=\"+2\"><b>\\</b></font></td>\r\n'  
            g_strlog = g_strlog + tmpString + '    <td align=left>\r\n'
            // inside sqrt is a generic expression => needs a new table (like after $$ or \begin{equation} etc...)
            g_strlog = g_strlog + tmpString + '      <table cellspacing=0  align=left class=sqrt>\r\n' 
            g_strlog = g_strlog + tmpString + '      <tr>\r\n' + tmpString +'        <td>\r\n'+ tmpString + '          '
            CurTableIndent = CurTableIndent + 4
          }
          else
          {
            g_strlog = g_strlog + MATHI(214) + '<span style=\"border-top:1 solid black;\">' 
          }
          break;

        case 173: // \left
          if(TextLevelAbsolute!=0){break;}
          tmpString = Spaces(CurTableIndent-1)
          switch(str.charAt(g_NextTokenPos + 5))
          {
            case '(':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-left:1 solid black;border-top:1 solid black;border-bottom:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString+'<td>\r\n'+tmpString+'  '
              break;
            case '[':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-left:1 solid black;border-top:1 solid black;border-bottom:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString+'<td>\r\n'+tmpString+'  '
              break;
            case '{':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-left:1 solid black;border-top:1 solid black;border-bottom:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString+'<td>\r\n'+tmpString+'  '
              break;
            case '|':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-left:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString+'<td>\r\n'+tmpString+'  '
              break;
            case '.':
              break;
          }
          g_NextTokenPos = g_NextTokenPos + 6
          TokenLength = 0
          break;

        case 174: // \right
          if(TextLevelAbsolute!=0){break;}
          tmpString = Spaces(CurTableIndent-1)
          switch(str.charAt(g_NextTokenPos + 6))
          {
            case ')':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-right:1 solid black;border-top:1 solid black;border-bottom:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString
              break;
            case ']':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-right:1 solid black;border-top:1 solid black;border-bottom:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString
              break;
            case '}':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-right:1 solid black;border-top:1 solid black;border-bottom:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString
              break;
            case '|':
              g_strlog = g_strlog + '\r\n'+tmpString+'</td>\r\n'+tmpString+'<td style=\"border-right:1 solid black;\">&nbsp;\r\n'+tmpString+'</td>\r\n'+tmpString
              break;
            case '.':
              break;
          }
          // correct vertical position of following exponent (if any)
          if(str.charAt(g_NextTokenPos + 7)=='^')
          {
            SupscriptAfterRightDelimiter=1
            g_strlog = g_strlog + '<td valign=top>\r\n'+tmpString+'  '
          }
          else
          {
            g_strlog = g_strlog + '<td>\r\n'+tmpString+'  '
          }
          g_NextTokenPos = g_NextTokenPos + 7
          TokenLength = 0
          break;

        case 175: // \small
          g_strlog = g_strlog + '<font size=\"-1\">'
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxSmall
          if(str.charAt(g_NextTokenPos+3)==' ') // swallows a space if there's a space after
          {
            g_NextTokenPos = g_NextTokenPos+4
            TokenLength=0
          }
          break;

        case 176: // \normalsize
          g_strlog = g_strlog + '<font size=\"+0\">'
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxNormalSize
          if(str.charAt(g_NextTokenPos+3)==' ') // swallows a space if there's a space after
          {
            g_NextTokenPos = g_NextTokenPos+4
            TokenLength=0
          }
          break;

        case 177: // \large
          g_strlog = g_strlog + '<font size=\"+1\">'
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxLarge
          if(str.charAt(g_NextTokenPos+3)==' ') // swallows a space if there's a space after
          {
            g_NextTokenPos = g_NextTokenPos+4
            TokenLength=0
          }
          break;

        case 178: // \Large
          g_strlog = g_strlog + '<font size=\"+2\">'
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxBigLarge
          if(str.charAt(g_NextTokenPos+3)==' ') // swallows a space if there's a space after
          {
            g_NextTokenPos = g_NextTokenPos+4
            TokenLength=0
          }
          break;

        case 179: // \huge
          g_strlog = g_strlog + '<font size=\"+3\">'
          BracketLevel = BracketLevel + 1
          ActionStack[BracketLevel] = ltxHuge
          if(str.charAt(g_NextTokenPos+3)==' ') // swallows a space if there's a space after
          {
            g_NextTokenPos = g_NextTokenPos+4
            TokenLength=0
          }
          break;

        case 180: // \par
          g_strlog = g_strlog + '<br>' // '\r\n<p>'
          break;

        case 181: // \\
          if(InsideArray==1 || InsideEqnArray==1)
          {
            if(NoNumber==0 && InsideArray==0)
            {
              tmpString = Spaces(CurTableIndent-3)
              g_strlog = g_strlog + '\r\n' + tmpString + '    </td>\r\n' + tmpString + '  </tr>\r\n' + tmpString + '  </table>\r\n' + tmpString + '</td>\r\n' + tmpString + '<td>\r\n' + tmpString + '  <table cellspacing=0 >\r\n' + tmpString + '  <tr>\r\n' + tmpString + '    <td align=center>\r\n' + tmpString + '      '
              g_strlog = g_strlog + '<a name=\"eq' + EquationNumber + '\">&nbsp;&nbsp;&nbsp;&nbsp;<font color=blue>(' + EquationNumber + ')</font>'
              EquationNumber = EquationNumber + 1
            }
            NoNumber=0
            tmpString = Spaces(CurTableIndent-4)
            g_strlog = g_strlog + '\r\n' + tmpString + '      </td>\r\n' + tmpString + '    </tr>\r\n' + tmpString + '    </table>\r\n' + tmpString + '  </td>\r\n' + tmpString + '</tr>\r\n' + tmpString + '<tr>\r\n' + tmpString + '  <td>\r\n' + tmpString + '    <table cellspacing=0 >\r\n' + tmpString + '    <tr>\r\n' + tmpString + '      <td align=center>\r\n' + tmpString + '        '
          }
          else
          {
            g_strlog = g_strlog + '<br>\r\n' 
          }
          break;

        case 182: // \;
          g_strlog = g_strlog + '&nbsp;&nbsp;'
          break;

        case 183: // \quad
          g_strlog = g_strlog + '&nbsp;&nbsp;&nbsp;'
          break;

        case 184: // \qquad
          g_strlog = g_strlog + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
          break;

        case 185: // CR + LF
          if(MathMode==0)
          {
            //g_strlog = g_strlog + '<br>\r\n'
            g_strlog = g_strlog + '\r\n'
          }
          break;

        case 186: // \label{
          g_TokenStartPos=g_NextTokenPos 
          g_NextTokenPos = str.indexOf('}',g_NextTokenPos)       
          g_reftext = str.substring(g_TokenStartPos+7,g_NextTokenPos)
          g_strlog = g_strlog + '<a name=\"ref' + g_reftext + '\">\r\n'
          RefReplacements += 'g_strlog = g_strlog.replace(/%%'+ g_reftext +'@@/g, \'' + EquationNumber + '\');\r\n'
          g_NextTokenPos = g_NextTokenPos + 1 
          TokenLength = 0
          break;

        case 187: // \ref{
          g_TokenStartPos=g_NextTokenPos 
          g_NextTokenPos = str.indexOf('}',g_NextTokenPos)  
          g_reftext = str.substring(g_TokenStartPos+5,g_NextTokenPos)
          g_strlog = g_strlog + '<a href=\"#ref' + g_reftext + '\">(%%' + g_reftext + '@@)</a>\r\n'
          g_NextTokenPos = g_NextTokenPos + 1
          TokenLength = 0
          break;

        case 188: // \box
          g_strlog = g_strlog + g_BOX
          break;

        case 189: // -
          if(MathMode==1 && ActionStack[BracketLevel] != ltxRef && ActionStack[BracketLevel] != ltxLabel )
          {
            g_strlog = g_strlog + MATHS('-')
          }
          else
          {
            g_strlog = g_strlog + '-'
          }
          break;

        case 190: // \ <= backslash+ space
          g_strlog = g_strlog + ' '
          break;

        case 191: // \,
          g_strlog = g_strlog + '&nbsp;'
          break;

        case 192: // \cite{
          g_strlog = g_strlog + '&nbsp;'
          break;

        case 193: // \bibitem{
/*
          g_strlog = g_strlog + '&nbsp;'
          BracketLevel = BracketLevel + 1 
          ActionStack[BracketLevel] = ltxBibitem
*/
          break;

        case 194: // \section{
          SectionNumber +=1
          SubSectionNumber = 0
          SubSubSectionNumber = 0
          g_strlog = g_strlog + '<p><a name=\"toc.'+SectionNumber+'\">'
          SectionStartPos = g_strlog.length
          g_strlog = g_strlog + '<h1>'+SectionNumber+'&nbsp;'
          BracketLevel = BracketLevel + 1 
          ActionStack[BracketLevel] = ltxSection
          break;

        case 195: // \subsection{
          SubSectionNumber += 1
          SubSubSectionNumber = 0
          g_strlog = g_strlog + '<p><a name=\"toc.'+SectionNumber+'.'+SubSectionNumber+'\">'  
          SectionStartPos = g_strlog.length
          g_strlog = g_strlog + '<h2>'+SectionNumber+'.'+SubSectionNumber+'&nbsp;'
          BracketLevel = BracketLevel + 1 
          ActionStack[BracketLevel] = ltxSubSection
          break;

        case 196: // \subsubsection{
          SubSubSectionNumber += 1
          g_strlog = g_strlog + '<p><a name=\"toc.'+SectionNumber+'.'+SubSectionNumber+'.'+SubSubSectionNumber+'\">' 
          SectionStartPos = g_strlog.length
          g_strlog = g_strlog + '<h3>'+SectionNumber+'.'+SubSectionNumber+'.'+SubSubSectionNumber+'&nbsp;'
          BracketLevel = BracketLevel + 1 
          ActionStack[BracketLevel] = ltxSubSubSection
          break;

        case 197: // \footnote{
/*
          g_strlog = g_strlog + '&nbsp;'
          BracketLevel = BracketLevel + 1 
          ActionStack[BracketLevel] = ltxFootnote
*/
          break;

        default:
      }//end switch(nexttokenid)
      //----------------------------------------
        
        //g_strlog+="</em>"; // LOL OG XD
    }// if(nexttokenid!=-1)
    
    if(BracketLevel > 0)
    {

      // treat an expression like e^\lambda: after the ^ one must parse the next token and then close the supscript, here. 
      if( (ActionStack[BracketLevel]==ltxGhostSupscript) && (nexttokenid!=16) )
      {
        // treatment of \prod_{...}^\lambda for instance: close cell after the \lambda
        if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
        {
          g_strlog = g_strlog + '</sub><br>$$' // moves down the upper bound
          SumOrProdPending = 0
          SumOrProdBoundaryBracketLevel = 0
          tmpString = Spaces(CurTableIndent-1) // new cell after the \sum or \prod
          g_strlog = g_strlog + '\r\n' 
          g_strlog = g_strlog + tmpString + '</td>\r\n'
          g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
          g_strlog = g_strlog + tmpString + '  ' 
        }
        else
        {
          g_strlog = g_strlog + '</sup>'
          if(SupscriptAfterRightDelimiter=1) // close cell after \right]^\lambda for instance
          {
            SupscriptAfterRightDelimiter=0
            tmpString = Spaces(CurTableIndent-1) 
            g_strlog = g_strlog + '\r\n' 
            g_strlog = g_strlog + tmpString + '</td>\r\n'
            g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
            g_strlog = g_strlog + tmpString + '  ' 
          }
        }

        // treatment of \int_{...}^\lambda : close cell after the \lambda
        if(BracketLevel==IntegralBoundaryBracketLevel && IntegralBoundaryPending==1)
        {
          IntegralBoundaryBracketLevel = 0
          IntegralBoundaryPending = 0
          CurTableIndent = CurTableIndent - 4
          tmpString = Spaces(CurTableIndent-1)
          g_strlog = g_strlog + '\r\n'  
          g_strlog = g_strlog + tmpString + '        </td>\r\n'
          g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
          g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
          g_strlog = g_strlog + tmpString + '    </td>\r\n' 
          g_strlog = g_strlog + tmpString + '  </tr>$$\r\n' // $$ delimits the end of the upper bound
          g_strlog = g_strlog + tmpString + '  </table>\r\n'     
          g_strlog = g_strlog + tmpString + '</td>\r\n'  
          g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
          g_strlog = g_strlog + tmpString + '  '
        }// end if(BracketLevel==IntegralBoundaryBracketLevel && IntegralBoundaryPending==1)

        ActionStack[BracketLevel] = ltxNone
        BracketLevel = BracketLevel - 1
        TextLevelAbsolute = TextLevelAbsolute - 1
        if(TextLevelAbsolute==0){TextLevel = 0}

      } // end if( (ActionStack[BracketLevel]==ltxGhostSupscript) && (nexttokenid!=16) )



      // treat an expression like e_\lambda: after the _ one must parse the next token and then close the subscript, here. 
      if( (ActionStack[BracketLevel]==ltxGhostSubscript) && (nexttokenid!=17) )
      {
        // treatment of \prod_\lambda^{...} for instance: close cell after the \lambda
        if(SumOrProdBoundaryBracketLevel==BracketLevel && SumOrProdPending == 1)
        {
          g_strlog = g_strlog + '</sup>' // moves up the lower bound
          if(str.charAt(g_NextTokenPos + TokenLength)!='^') // no upper bound => ending the boundaries
          {
            SumOrProdPending = 0
            SumOrProdBoundaryBracketLevel = 0
            tmpString = Spaces(CurTableIndent-1) // new cell after the \sum or \prod
            g_strlog = g_strlog + '%%&nbsp;<br>$$\r\n' 
            g_strlog = g_strlog + tmpString + '</td>\r\n'
            g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
            g_strlog = g_strlog + tmpString + '  ' 
          }
        }
        else
        {
          g_strlog = g_strlog + '</sub>'
        }

        // treatment of \int_\lambda^{.....} : close cell after the \lambda
        if(BracketLevel==IntegralBoundaryBracketLevel && IntegralBoundaryPending==1)
        {
          CurTableIndent = CurTableIndent - 4
          tmpString = Spaces(CurTableIndent-1)
          g_strlog = g_strlog + '\r\n' 
          g_strlog = g_strlog + tmpString + '        </td>\r\n'
          g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
          g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
          g_strlog = g_strlog + tmpString + '    </td>\r\n' 
          g_strlog = g_strlog + tmpString + '  </tr>$$\r\n%%' // note the $$ that marks the end of the lower bound
          g_strlog = g_strlog + tmpString + '  <tr>\r\n'      // and the %% that marks the beginning of the upper bound
          g_strlog = g_strlog + tmpString + '    <td valign=top>\r\n' 
          g_strlog = g_strlog + tmpString + '      <table cellspacing=0 border=0 >\r\n' // start of new expression table 
          g_strlog = g_strlog + tmpString + '      <tr>\r\n' 
          g_strlog = g_strlog + tmpString + '        <td nowrap align=center>\r\n' 
          g_strlog = g_strlog + tmpString + '          '
          CurTableIndent = CurTableIndent + 4

          // note that here we must skip the token following the _ to look if there is a ^ after
          if(str.charAt(g_NextTokenPos + TokenLength)!='^') // no upper bound after \int => ending the boundary table
          {
            IntegralBoundaryBracketLevel = 0
            IntegralBoundaryPending = 0
            CurTableIndent = CurTableIndent - 4
            tmpString = Spaces(CurTableIndent-1)
            g_strlog = g_strlog + '&nbsp;\r\n'  // <= add a space to get a non-blank cell
            g_strlog = g_strlog + tmpString + '        </td>\r\n'
            g_strlog = g_strlog + tmpString + '      </tr>\r\n' 
            g_strlog = g_strlog + tmpString + '      </table>\r\n' // end of expression table 
            g_strlog = g_strlog + tmpString + '    </td>\r\n' 
            g_strlog = g_strlog + tmpString + '  </tr>$$\r\n' // $$ delimits the end of the upper bound
            g_strlog = g_strlog + tmpString + '  </table>\r\n'     
            g_strlog = g_strlog + tmpString + '</td>\r\n'  
            g_strlog = g_strlog + tmpString + '<td nowrap align=center>\r\n' 
            g_strlog = g_strlog + tmpString + '  '
          } // end if(str.charAt(g_NextTokenPos + 1)!='^')

        }// end if(BracketLevel==IntegralBoundaryBracketLevel && IntegralBoundaryPending==1)

        ActionStack[BracketLevel] = ltxNone
        BracketLevel = BracketLevel - 1
        TextLevelAbsolute = TextLevelAbsolute - 1
        if(TextLevelAbsolute==0){TextLevel = 0}

      }// end if( (ActionStack[BracketLevel]==ltxGhostSubscript) && (nexttokenid!=17) )


      // treat an expression like \vec \lambda: after the \vec one must parse the next token and then close the vector, here. Idem for \hat \tilde \bar. 
      if( (ActionStack[BracketLevel]== ltxGhostVector) && (nexttokenid !=168) && (nexttokenid !=169) && (nexttokenid !=170) && (nexttokenid !=171))
      {
        g_strlog = g_strlog + '</span>'
        ActionStack[BracketLevel] = ltxNone
        BracketLevel = BracketLevel - 1
      } // end if( (ActionStack[BracketLevel]== ltxGhostVector) && (nexttokenid !=168) && (nexttokenid !=169) && (nexttokenid !=170) && (nexttokenid !=171))


    }// end if(BracketLevel > 0)


    step=step + 1
  }//end while( (nexttokenid!=-1) && (g_NextTokenPos!=-1)  )
  // -------- END OF MAIN LOOP ------------------------------------
  
  g_strlog = g_strlog + str.substr(CurrentPos) // <= append text after the last token found

  // if content of the "Table of contents" is not empty, append it at the end 
  // (can alternately be put at the beginning of the output)
  if (g_TOC.length>0)
  {
    g_strlog = g_strlog + '\r\n<hr>\r\n<p><h1>Table Of Contents</h1>\r\n'+g_TOC
  }
  //g_strlog = g_strlog + '</body>\r\n</html>\r\n'

  window.status = 'Make cross references right....'
  //fill bookmarks with found equation references 
  eval(RefReplacements)

  window.status = 'Post replacements ....'
  g_strlog = PostReplacements(g_strlog) // see latex_aliases.js


  window.status = 'Done'
  // show the HTML output in the 2nd textbox
  return g_strlog;
//  document.MainForm.output.value = g_strlog  


}// end function ltxParse
//-----------------------------------------------------------------------------

//=============================================================================
function ltxCompress()
{
    g_strlog = g_strlog.replace(/\n\s+([^\s])/g, '\n$1') 
    g_strlog = g_strlog.replace(/>\r?\n/g, '>')
    g_strlog = g_strlog.replace(/\r?\n</g, '<')
    document.MainForm.output.value = g_strlog

}// end function ltxCompress()
//-----------------------------------------------------------------------------

//=============================================================================
function ltxShow()
{
   var NewWin
   NewWin = window.open()
   NewWin.document.write(g_strlog)
}// end function ltxShow()
//-----------------------------------------------------------------------------

//=============================================================================
function ltxDebug()
{
   var NewWinDebug
   var HeaderText = '<html><head><title>LaTeX4Web Debug output</title></head><body>\r\n'
   HeaderText = HeaderText + 'Green Char= Current position<br>Red Char=Next token position<br>No Green Char: Next token position=Current position<br><hr>'
   NewWinDebug = window.open()
   NewWinDebug.document.write(HeaderText+g_strDebug + '</table></body></html>')
}// end function ltxDebug()
//-----------------------------------------------------------------------------


//=============================================================================
function ltxClear()
{
  document.MainForm.input.value = ''
  document.MainForm.output.value = ''
}// end function ltxClear()
//-----------------------------------------------------------------------------

//=============================================================================
function ltxDFAgeneration()
{
  // The GenerateDFA function is in file LaTeX_dfa_gen.js
  document.MainForm.output.value = GenerateDFA()

}// end function ltxTest()
//-----------------------------------------------------------------------------


//=============================================================================
function ltxTest()
{

/*
  var FTTc ='[\r\n'
  for(var i=0; i<FTT.length;i++)
  {
    if(FTT[i]!=0) FTTc = FTTc + i+','+FTT[i]+',\r\n'
  }
  FTTc = FTTc + ']\r\n'
*/

}// end function ltxTest()
//-----------------------------------------------------------------------------
