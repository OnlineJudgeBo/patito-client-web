

/*



	LaTeX4Web DFA generator: 

          Additional component which generates the compressed dfa, 
	given an array of tokens. This way you can easily modify the list of tokens 
        recognized by the script or even add some new tokens. Each token has an id (its 
        rank in the tokens[] array), and when the main script finds a token, a switch(token_id) 
        command is executed. Therefore if you add a new token, simply add a new case within the 
        switch command to execute the code you want to be executed when the token is found in the 
        input text.


	Requirements: the tokens[] array in the latex_tok.js file is used as an input
        The output is a string to be copied into latex_dfa_comp.js

	Author: Eric Chopin, August 28 2004

	Download the application at:	
	http://perso.wanadoo.fr/eric.chopin/latex/latex4web.htm

	Send your comments to: eric.chopin@wanadoo.fr

*/


var genlog='' // only for debug purposes, can be dropped

//=====================================================================
function GenerateDFA()
{
  var EC_def = '  EClass = [\r\n' // stores js code to initialize the Equivalence Class array (to be copied in latex_asc.js)
  var ACC_def = '  Accept = [\r\n' // stores the js code to initialize the Accepted States array (to be copied in latex_acc.js)
  var DFA_def = 'var FTTc = [\r\n' // stores the js code to initialize the compressed Full Transition Table array (to be copied in latex_acc.js)
  
  // The 3 previous strings are put together in the output string

  var EClass2 = new Array(255)
  var Accept2 = new Array()
  var FTTc2= new Array()
  var MaxClass = 0
  var MaxState = 0
  var pos = 0
  var States = new Array()  
  var SingleState = new Array(255)
  var TokenStates = new Array(tokens.length-1)

  var l_EndReached // =0 while the end is not reached, 1 otherwise
  var l_NumberOfTokenParsed // integer
  var l_CurCharPos // long
  var l_CurCharCode // Byte
  var l_CurrentClass // Byte
  var l_PrevCharCode // Byte
  var l_CurrentTokenId // integer
  
  var l_CurrentDFAStateId //long
  var l_NewDFAStateId // Long
  var l_StartingDFAStateId // long
  var l_NextStartingDFAStateId //long

  // Initilizations 
  for (var i=0;i<256;i++)
  {
    EClass2[i]=0
  } 

  MaxClass = 0
  l_EndReached = 0 
  l_CurCharPos = 1
  l_PrevCharCode = 0
  l_NoMoreTokenRead = 0
  
  // End of initializations
  
  //initialize first DFA state
  l_StartingDFAStateId = 0
  l_CurrentDFAStateId = 0

  var SingleState = new Array(255) // SingleState is in fact the transition table for this state
  for(k=0;k<255;k++)
  {
    SingleState[k]=0
  }
  States[0] = SingleState  // add first state to array of states
  Accept2[0]=-1

  for(l_CurrentTokenId = 0; l_CurrentTokenId < tokens.length; l_CurrentTokenId++)
  {
    TokenStates[l_CurrentTokenId]=';0;' // initial state must be matched by all tokens
  }

  // end if 1st state initialization
    

    
  // ================= MAIN LOOP =============================================
  while(l_EndReached==0)
  {    
    l_NextStartingDFAStateId = States.length 

    for(l_CurrentDFAStateId = l_StartingDFAStateId ; l_CurrentDFAStateId<l_NextStartingDFAStateId ; l_CurrentDFAStateId++)  
    {  
      l_NumberOfTokenParsed = 0
            
              //For l_CurrentTokenId = 0 To l_MaxTokenIndex
      for(l_CurrentTokenId = 0; l_CurrentTokenId < tokens.length; l_CurrentTokenId++) 
      {
        // Test if the token is contained in the sublist of the state
        if(TokenStates[l_CurrentTokenId].indexOf(';'+l_CurrentDFAStateId+';') > -1 )
        {
          //Current Char position must be less than the size of the current token
                       // If l_CurCharPos <= Len(DFAListOfTokens(l_CurrentTokenId)) Then
          if(l_CurCharPos <= tokens[l_CurrentTokenId].length )
          {
            l_NumberOfTokenParsed = l_NumberOfTokenParsed + 1
            l_CurCharCode =  tokens[l_CurrentTokenId].charCodeAt(l_CurCharPos-1) 
                        
            // If current char has not yet a class, create this character class
            if( EClass2[l_CurCharCode] == 0 )
            {
              MaxClass = MaxClass + 1
              EClass2[l_CurCharCode] = MaxClass
            } // case when class of current char not yet exists.. add a new class

            l_CurrentClass = EClass2[l_CurCharCode]
                        
            /*========= TEST IF ONE NEEDS TO ADD A NEW DFA STATE ============
            ' A new dfa state is added if the current char has not yet
            ' a target in the transition table of the current dfa state.
            ' If the class of the current char exceeds the size of the
            ' transition table of the current state, then we know that it
            ' is not (obviously) in this transition table. The second case
            ' is that when transition table contains -1. 
            ******************************************************************/

            if(States[l_CurrentDFAStateId][l_CurrentClass] == 0 )
            {
              //The current class is not present in transition table, add a new state

              SingleState = new Array(255) 
              for(k=0;k<255;k++)
              {
                SingleState[k]=0
              }
              l_NewDFAStateId = States.length
              States[l_NewDFAStateId] = SingleState
              Accept2[l_NewDFAStateId] = -1
              //genlog = genlog + 'new state '+l_NewDFAStateId +'\r\n'
              TokenStates[l_CurrentTokenId] = TokenStates[l_CurrentTokenId] + ';' + l_NewDFAStateId + ';'
                            
              //fill transition table of current state with the current transition to the new state
              States[l_CurrentDFAStateId][l_CurrentClass]= l_NewDFAStateId
              //genlog = genlog + 'Fill trans table. state='+l_CurrentDFAStateId+' / class='+l_CurrentClass+' / target='+l_NewDFAStateId 

              if(l_CurCharPos==tokens[l_CurrentTokenId].length)
              {
                Accept2[l_NewDFAStateId] = l_CurrentTokenId
                //genlog = genlog + 'Accepting token '+l_CurrentTokenId+ ' at state '+l_NewDFAStateId
              }
            }
            {
              TokenStates[l_CurrentTokenId]=TokenStates[l_CurrentTokenId]+';'+ States[l_CurrentDFAStateId][l_CurrentClass]+';'

              if(l_CurCharPos==tokens[l_CurrentTokenId].length)
              {
                Accept2[States[l_CurrentDFAStateId][l_CurrentClass]] = l_CurrentTokenId
              }

            } // end (else) if(States[l_CurrentDFAStateId][l_CurrentClass] == 0 )
    
          } // end if(l_CurCharPos <= tokens[l_CurrentTokenId].length )
                    
        } //end if(TokenStates[l_CurrentTokenId].indexOf(';'+l_CurrentDFAStateId+';') > -1 )
                
      }// end for(l_CurrentTokenId = 0; l_CurrentTokenId < tokens.length; l_CurrentTokenId++)
       // end of loop on valid tokens for one child state
        
    } // for(l_CurrentDFAStateId = l_StartingDFAStateId ; l_CurrentDFAStateId<l_NextStartingDFAStateId ; l_CurrentDFAStateId++) 
      // end of loop on previous child states
        
    if(States.length-1 < l_NextStartingDFAStateId)
    {
      l_EndReached = 1
    }

    l_StartingDFAStateId = l_NextStartingDFAStateId    
    l_CurCharPos = l_CurCharPos + 1
        
  } // end while(l_EndReached==0)

    

  for (var i=0;i<256;i++)
  {
    EC_def = EC_def + EClass2[i]
    if(i<255) EC_def +=','
  } 
  EC_def += '\r\n           ];\r\n'

  for (var i=0;i<Accept2.length;i++)
  {
    ACC_def = ACC_def + Accept2[i] + '\r\n'
    if(i<Accept2.length-1) ACC_def +=','
  } 
  ACC_def += '\r\n           ];\r\n'

  for (var i=0;i<States.length;i++)
  {
    for (var j=0;j<=MaxClass;j++)
    {
      if(States[i][j]!=0)
      {
	pos=i*(MaxClass+1)+j
        DFA_def = DFA_def + pos + ',' + States[i][j] + ',\r\n'
      }
    }
  } 
  DFA_def += '           ];\r\n'


  return EC_def +'\r\n'+ ACC_def +'\r\n'+ DFA_def 


}// end function GenerateDFA()
//---------------------------------------------------------------------

