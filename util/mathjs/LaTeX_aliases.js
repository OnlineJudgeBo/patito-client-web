
/*

	USER DEFINED ALIASES MANAGEMENT

*/


//===========================================================================
function LatexReplaceAliases(strInput)
{
/*

      If you have defined some basic aliases, just make the replacement here.
      To get best performance, one should in principle change the DFA to include 
      the aliases. In our case, the input text must be completely parsed for each 
      alias in order to make the replacement. However, this way the DFA is common for 
      every user. In the future I will try to add the javascript code to generate a 
      modified DFA. However, my algorithm is basic and does not provide a compressed 
      DFA, so if you have a very large number of tokens to add, the DFA LaTeX_dfa.js file 
      would become huge, and practically unusable.

*/
    var strOutput = strInput

    // erase comments from source 
    strOutput = strOutput.replace(/^(%[^\n]*\n)+/g, '') // remove comments starting at first character
    strOutput = strOutput.replace(/([^\\])(%[^\n]*\n)+/g, '$1') // \% must not be considered as comment
    // other common replacements
    strOutput = strOutput.replace(/\\centerline\{([^}]*)\}/g, '<center>$1</center>') 
    strOutput = strOutput.replace(/\\mapsto/g, '\\rightarrow') 
    strOutput = strOutput.replace(/\\indent/g, '<p>&nbsp;&nbsp;&nbsp;&nbsp;') 
    strOutput = strOutput.replace(/\\noindent/g, '<p>') 
    strOutput = strOutput.replace(/\\!/g, '') // negative space
    strOutput = strOutput.replace(/\\section\*\s*\{([^}]*)\}/g, "<h1>$1</h1>")
    strOutput = strOutput.replace(/\\subsection\*\s*\{([^}]*)\}/g, "<h2>$1</h2>")
    strOutput = strOutput.replace(/\\subsubsection\*\s*\{([^}]*)\}/g, "<h3>$1</h3>")
    strOutput = strOutput.replace(/\\begin\{enumerate\}/g, '<ol>')
    strOutput = strOutput.replace(/\\end\{enumerate\}/g, '<\/ol>')
    strOutput = strOutput.replace(/\\begin\{itemize\}/g, '<ul>')
    strOutput = strOutput.replace(/\\end\{itemize\}/g, '<\/ul>')
    strOutput = strOutput.replace(/\\item\s*\[([^\]]*)\]/g, '<br>$1&nbsp;')
    strOutput = strOutput.replace(/\\item/g, '\r\n<li>')
    strOutput = strOutput.replace(/\\begin\{thebibliography\}(\{[^}]*\})?/g, '\r\n<ol start=0>\r\n')
    strOutput = strOutput.replace(/\\end\{thebibliography\}/g, '\r\n</ol>\r\n')

    //to be changed in a future version => need to keep the list of indexed words to build the index
    strOutput = strOutput.replace(/\\index\{([^}]*)\}/g, '<a name=\"def.$1\">')

    // user defined replacements
    // NB: if the replacement strings contain some html, put then preferably in the PostReplacements() function
    strOutput = strOutput.replace(/\\vp/g, '\\varphi')
    strOutput = strOutput.replace(/\\bea/g, '\\begin{eqnarray}')
    strOutput = strOutput.replace(/\\ena/g, '\\end{eqnarray}')
    strOutput = strOutput.replace(/\\be([\s\r\n]+)/g, '\\begin{equation}$1')
    strOutput = strOutput.replace(/\\ee([\s\r\n]+)/g, '\\end{equation}$1')


    strOutput = strOutput.replace(/\\ddots/g, '\\') // dots in the diagonal (approximate)
    strOutput = strOutput.replace(/\\longrightarrow/g, '\\rightarrow')
    strOutput = strOutput.replace(/\\lra/g, '\\leftrightarrow')
    strOutput = strOutput.replace(/\\eps([^i])/g, '\\varepsilon$1')
    strOutput = strOutput.replace(/\\cal([\s\r\n])/g, '\\bf\\it$1')
    strOutput = strOutput.replace(/\\em([\s\r\n])/g, '\\it$1')
    strOutput = strOutput.replace(/\\tr([\s\[\(\{\\])/g, 'Tr$1')

    // \footnote should be added to the DFA in order to manage footnotes 
    // but it may also require an array of buffer that will replace g_strlog, 
    // in order to store temporary output string corresponding to the nested groups currently parsed
    strOutput = strOutput.replace(/\\footnote\{/g, ' \{\\small\\it')

    // use the following replacements for documents with accents (french...) 
    // If your document do not contain accents like in english, comment these lines 
    // to gain performances
    strOutput = strOutput.replace(/\\'e/g, '&eacute;')
    strOutput = strOutput.replace(/é/g, '&eacute;')
    strOutput = strOutput.replace(/\\`a/g, '&agrave;')
    strOutput = strOutput.replace(/à/g, '&agrave;')
    strOutput = strOutput.replace(/\\`e/g, '&egrave;')
    strOutput = strOutput.replace(/è/g, '&egrave;')
    strOutput = strOutput.replace(/ù/g, '&ugrave;')
    strOutput = strOutput.replace(/\\`u/g, '&ugrave;')
    strOutput = strOutput.replace(/\\^e/g, '&ecirc;')
    strOutput = strOutput.replace(/ê/g, '&ecirc;')
    strOutput = strOutput.replace(/\\^a/g, '&acirc;')
    strOutput = strOutput.replace(/â/g, '&acirc;')
    strOutput = strOutput.replace(/\\^i/g, '&icirc;')
    strOutput = strOutput.replace(/î/g, '&icirc;')
    strOutput = strOutput.replace(/{\\c c}/g, '&ccedill;')
  

    return strOutput;

}// function LatexReplaceAliases(strInput)
//---------------------------------------------------------------------------


//===========================================================================
function PostReplacements(strInput)
{

   var strOutput = strInput

    strOutput = strOutput.replace(/\~/g, '&nbsp;')
    strOutput = strOutput.replace(/\\newpage/g, '<p><hr>')

    strOutput = strOutput.replace(/\\expf/g, '<span style=\"position:relative;top:5pt;left:3pt;\"><font face=\"symbol\" size=\"-1\">&#174;</font></span><span style=\"position:relative;left:-5pt;\">e</span>')
    strOutput = strOutput.replace(/\\expb/g, '<span style=\"position:relative;top:5pt;left:3pt;\"><font face=\"symbol\" size=\"-1\">&#172;</font></span><span style=\"position:relative;left:-5pt;\">e</span>')
    strOutput = strOutput.replace(/\\exp/g, 'exp')
    strOutput = strOutput.replace(/\\det/g, 'det')
    strOutput = strOutput.replace(/\\sla/g, '<span style="position:relative;left:-7pt;"><b><font face=courier size=+2>/</font></b></span>') // <= slash over a letter or symbol
    
    strOutput = strOutput.replace(/\\ra/g, '<font face=symbol>'+String.fromCharCode(174)+'</font>') // = \rightarrow
    strOutput = strOutput.replace(/\\pr/g, 'Phys.Rev. ')
    strOutput = strOutput.replace(/\\pl/g, 'Phys.Lett. ')
    strOutput = strOutput.replace(/\\prl/g, 'Phys.Rev.Lett. ')
    strOutput = strOutput.replace(/\\np/g, 'Nucl.Phys. ')
    strOutput = strOutput.replace(/\\zp/g, 'Zeit. fuer Phys. ')
    strOutput = strOutput.replace(/\\prep/g, 'Phys. Rep. ')
    strOutput = strOutput.replace(/\\annp/g, 'Ann. Phys. ')
    
    // replace lower and upper boundaries of big integrals in the correct order
    // The <tr> ... </tr> of the upper must come before the one of the lower bound 
    strOutput = strOutput.replace(/__([^$]*)\$\$\r\n%%([^$]*)\$\$/g, '$2\r\n$1')

    //moves backward the upper bounds of commands like \sum or \prod
    strOutput = strOutput.replace(/<!--UB-->([^%]*)%%([^$]*)\$\$/g, '$2$1')


    return strOutput;

}// function PostReplacements(strInput)
//---------------------------------------------------------------------------
