
/*


           Miscellaneous functions 


*/


//====================================================
function Spaces(n)
{
  var output =''
  for(var i=0;i<n;i++)
  {
    output = output + '  '; // 2 spaces per indent level
  }// for(var i=0;i<n;i++)
  return output;
}// end function Spaces(n)
//----------------------------------------------------

//====================================================
function MATHS(chr)
{
var output 
  switch(g_UnicodeLaTex)
  {
    case 0:
      output = g_Symbol + chr + g_FontEnd
      break;
    case 1:
      output = symbols[chr.charCodeAt(0)]
      break;
    default:
      output = symbols2[chr.charCodeAt(0)]
      break;
  }// end switch(g_UnicodeLaTex)
  return output;
}// end function MATHS(chr)
//----------------------------------------------------

//====================================================
function MATHI(i)
{
var output 
  switch(g_UnicodeLaTex)
  {
    case 0:
      output = g_Symbol + String.fromCharCode(i) + g_FontEnd
      break;
    case 1:
      output = symbols[i]
      break;
    default:
      output = symbols2[i]
      break;
  }// end switch(g_UnicodeLaTex)
  return output
}// end function MATHI(i)
//----------------------------------------------------


//========================================================================================
function delimit(strtype, heightin,indent)
{
     // Return codes corresponding to a delimiter of given type and height
    
    var topChar;
    var flatChar;
    var midChar;
    var botChar;
    var i, j ;
    var buff;
    var height;
    var codes;
    var tmp =''

    tmp = Spaces(indent)
    height = Math.floor(0.65 * heightin + 0.71) // 2 has to yield 2
    
    switch(strtype)
    {
        case '(':
            i = 0
            topChar = 230
            flatChar = 231
            midChar = 231
            botChar = 232
            break;
        case ')':
            i = 1
            topChar = 246
            flatChar = 247
            midChar = 247
            botChar = 248
            break;
        case '[':
            i = 2
            topChar = 233
            flatChar = 234
            midChar = 234
            botChar = 235
            break;
        case ']':
            i = 3
            topChar = 249
            flatChar = 250
            midChar = 250
            botChar = 251
            break;
        case '{':
            topChar = 236
            flatChar = 239
            midChar = 237
            botChar = 238
            i = 4
            height = Math.floor(2 * (height / 2)) + 1
            break;
        case '}':
            topChar = 252
            flatChar = 239
            midChar = 253
            botChar = 254
            i = 5
            height = Math.floor(2 * (height / 2)) + 1
            break;
        case '|': 
            i = 6
            topChar = 234
            flatChar = 234
            midChar = 234
            botChar = 234
            break;
        case '&#242;': 
            i = 7 // int
            topChar = 243
            flatChar = 244
            midChar = 244
            botChar = 245
            break;
        case '&#233;': 
            i = 8 // lceil
            topChar = 233
            flatChar = 234
            midChar = 234
            botChar = 234
            break;
        case '&#249;': 
            i = 9 // rceil
            topChar = 249
            flatChar = 250
            midChar = 250
            botChar = 250
            break;
        case '&#235;': 
            i = 10 // lfloor
            topChar = 234
            flatChar = 234
            midChar = 234
            botChar = 235
            break;
        case '&#251;': 
            i = 11 // rfloor
            topChar = 250
            flatChar = 250
            midChar = 250
            botChar = 251
            break;
        case '&#225;': 
            i = 12 // langle
            topChar = 32
            flatChar = 32
            midChar = 225
            botChar = 32
            break;
        case '&#241;': 
            i = 13 // rangle
            topChar = 32
            flatChar = 32
            midChar = 241
            botChar = 32
            break;
        case '\/', '\\\\':
            return '\r\n' + tmp + '</td>\r\n' + tmp + '<td align=left>\r\n' + '  <font size=\"+' + eval(2 * (height - 1)) + '\"></font></font>' + strtype + '\r\n' + tmp + '</td>\r\n' + tmp +'<td align=\"center\">\r\n'
            break;


        default:
            alert('Incorrect delimiter::' + strtype + '::')
            i = -1
            codes = ''
            return codes;
            break;

    }// end switch(strtype)

    //  8 bits codes. 
    if(height > 1)
    {
        codes = '\r\n' + tmp +'</td>\r\n' + tmp + '<td align=left class=cl>\r\n'+ tmp +'  <font face=symbol>\r\n'
        for(j = 1; j<= Math.floor(height);j++)
        {
            if(j == 1) 
	    {
            
                buff = String.fromCharCode(topChar) + '<br>\r\n' 
            }
            else
            {
              if(j == Math.floor(height))
              {
                  buff = String.fromCharCode(botChar) + '\r\n'
              }
              else
              {
                if(j == Math.floor(height + 1 / 2))
                {
                  buff = String.fromCharCode(midChar) + '<br>\r\n' 
                }
                else
                {
                  buff = String.fromCharCode(flatChar) + '<br>\r\n'
                }
              }
            }// end else if(j == 1)
            codes = codes + tmp + '    ' + buff 
        }// end for 
        codes = codes +tmp+ '  </font>\r\n' + tmp + '</td>\r\n' + tmp +'<td nowrap align=center>\r\n' +tmp +'  '
    }
    else
    {
        if(i > 6)
        {
            codes = g_Symbol + strtype + '</font>'
        }
        {
            codes = strtype
        }
    }
    return codes;
}// end function delimit(strtype, heightin)
//---------------------------------------------------------------------------


