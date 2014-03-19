<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="wys/ckeditor.js"></script>
        <link rel="stylesheet" href="css/style.css">
        <script language="javascript">
            /**
             *   <h1>Saddleback Computer Science Department - Sudo Code Interperter</h1>
             **  
             *      <p>This program receives input from a text area and checks for 
             *  syntax, correctness and interprets the code in a compiler like 
             *  manner.</p>
             */
            
            /**
             * <h1>XML Object</h1>
             * <p>XML Object creation var used to create an Async browsing experince</p>
             ** 
             * <p>@type <b>Boolean</b> - XMLHttpRequest - ActiveXObject - XMLHttpRequest - ActiveXObject</p>
             */
            var XMLHttpRequestObject = false;
            
            /**
             * <h1>Input Box Elemnt</h1>
             * <p>Used to store the element of the input box</p>
             **
             * <p>@type <b>Element</b></p>
             * <p>@exp document</p>
             * <p>@call getElementById</p>
             */
            var inputBoxElement;
            
            /**
             * <h1>Ouput Box Element</h1>
             * <p>Used to store the element of the output box</p>
             ** 
             * <p>@type <b>Element</b></p>
             * <p>@exp document</p>
             * <p>@call getElementById</p>
             */
            var outputBoxElement;
            
            /**
             * <h1>Input Box Original Text</h1>
             * <p>Used to store the original text of the input text area for possible later usage</p>
             **
             * <p>@type <b>String</b></p>
             * <p>@exp inputBoxElement</p>
             * <p>@pro value</p>
             */
            var inputBoxOriginalText;
            
            /**
             * <h1>Ouput Box Original Text</h1>
             * <p>Used to store the original text of the output text area for possible later usage</p>
             **
             * <p>@type <b>String</b></p>
             * <p>@exp outputBoxElement</p>
             * <p>@pro value</p>
             */
            var outputBoxOriginalText;
            
            // Check to see if the AJAX request object can be created
            if (window.XMLHttpRequest) 
            {
                XMLHttpRequestObject = new XMLHttpRequest();
                XMLHttpRequestObject.overrideMimeType("text/html");
            }
            // else attempt and IE connection
            else if (window.ActiveXObject)
            {
                XMLHttpRequestObject = new 
                ActiveXObject("Microsoft.XMLHTTP");
            }
            // Alert the user that they may not be able to load the page.
            else 
            {
                alert("Your browser does not support XMLHTTP! You may not be able to load everything on this page. Please try a diffrent browser.");
            }

            /**
             * <H1>Start Program - Main</h1>
             *      <p>This function acts as the main, it is what respondes to the submit button. 
             *  This function initilizes some global variables such as input box element and 
             *  its orginal values with there proper values.</p>
             ***
             * <p>@returns void</p>
             */
            function startProgram()
            {
                // Stores the input/output element location
                inputBoxElement  = document.getElementById('inputBox');
                outputBoxElement = document.getElementById('outputBox');
                
                // Stores the values of the original input/output content
                inputBoxOriginalText = inputBoxElement.value;
                outputBoxOriginalText = outputBoxElement.value;
                
                
                // Split the text are content by line to examine each command.
                // We expect the user to delimit each command with an enter or \n
                var textAreaSplit = inputBoxOriginalText.split("\n");
                
                // Calling checkBegin tag function on the first element of the 
                // text area allowes us to get a boolean if BEGIN is present
                if(checkBegin(textAreaSplit[0]))
                {
                    // Set Output Box - Output Let the user know the begin tag was detected
                    outputBoxElement.innerHTML = "<span class='goodOutput'>Begin Tag Detected, Starting Process</span>" + "<br /><br />";
                    
                    // Check Code runs each line of the code and runs the command
                    checkCode(textAreaSplit);
                    
                    // Checks to see if the last line is an END tag
                    if(checkEnd(textAreaSplit[textAreaSplit.length-1]))
                    {
                        // Set Output Box - Output Let the user know the end tag was detected
                        outputBoxElement.innerHTML += "<br /><span class='goodOutput'>End Tag Detected, Ending Program</span><br />";
                    }
                    else
                    {
                        // Set Output Box - Output Let the user know the end tag was not detected
                        outputBoxElement.innerHTML += "<br /><span class='badOutput'>Error: Program Terminated Without an End tag or extra spaces/charecters are present </span><br />";
                    }
                        
                }
                else
                {
                    // Set Output Box - Output Let the user know the begin tag was not detected
                    outputBoxElement.innerHTML = "<span class='badOutput'>Error: No Begin Tag Detected</span><br />";
                }
            }
            
            function checkCode(statement)
            {
                var variableArrayList  = new Array();
                
                for(var i = 1; i < statement.length-1; i++)
                {
                    outputBoxElement.innerHTML += "Line " + i + ":        ";
                    var splitStatement = statement[i].split(" ");
                    
                    switch(splitStatement[0])
                    {
                        case "CALC":                           
                            if(/ .*?=/.test(statement))
                            {
                                try 
                                {
                                   variableArrayList.push({ name: splitStatement[1], value: eval(statement[i].split("=")[1]) });
                                } 
                                catch (e) 
                                {
                                        outputBoxElement.innerHTML += "<span class='badOutput'>Inavlid Mathematical Expression Error: " + e.message + "</span><br />";
                                        return;
                                }
                                
                                outputBoxElement.innerHTML += "Assigning " + variableArrayList[variableArrayList.length-1].value + " into the variable " + variableArrayList[variableArrayList.length-1].name + "<br />";
                                
                            }
                            else if(!isNaN(statement[i].toString()[5]) &&
                                    /\s/.test(statement[i].toString()[4]))
                            {
                                try 
                                {
                                    outputBoxElement.innerHTML += eval(statement[i].toString().substr(4, statement[i].toString().length)) + "<br>";
                                } 
                                catch (e) 
                                {
                                        outputBoxElement.innerHTML += "<span class='badOutput'>Inavlid Mathematical Expression Error: " + e.message + "</span><br />";
                                        return;
                                }
                            }
                            break;
                        
                        case "INPUT":
                             var correctRegEx = statement[i].match(/ .*/);
                             outputBoxElement.innerHTML += eval(correctRegEx.toString()) + "<br>";
                            break;
                            
                        case "OUTPUT":
                            //  Finsih this
                            var incorrectRegEx = statement[i].match(/ .*".*?".*/);
                            var correctRegEx   = statement[i].match(/ .*".*?"/);
                            // Checks to see if the string index 1 is a 
                            // space and the index 2 is a int
                            if(statement[i].toString()[7] !== "\"" && /\s/.test(statement[i].toString()[6]))
                            {
                                try 
                                {
                                    outputBoxElement.innerHTML += eval(statement[i].toString().substr(6, statement[i].toString().length)) + "<br>";
                                } 
                                catch (e) 
                                {
                                    var inputVariableName = statement[i].split(" ")[1].toString();
                                    
                                    var foundIndex = searchArrayList(inputVariableName, variableArrayList);
                                    
                                    if(foundIndex === variableArrayList.length)
                                    {
                                        outputBoxElement.innerHTML += "<span class='badOutput'>Invalid Variable Name" + "</span><br />";
                                        return;
                                    }
                                    else if(variableArrayList[foundIndex].name === inputVariableName)
                                    {
                                        
                                         outputBoxElement.innerHTML += variableArrayList[foundIndex].value + "<br />";
                                    }
                                    else
                                    {
                                        outputBoxElement.innerHTML += "<span class='badOutput'>Inavlid Mathematical Expression Error: " + e.message + "</span><br />";
                                        return;
                                    }
                                    
                                }
                            }
                            // If String outputs
                            else if(/ .*".*?"/.test(statement[i]))
                            {
                                var output = statement[i].match(/".*?"/);
                                var outputStatement = output[0].substring(1, output[0].length-1);
                                outputBoxElement.innerHTML += outputStatement.replace(new RegExp('\r?\n','g'), '<br />') + "<br />"; 
                            }
                            else
                            {
                                return outputBoxElement.innerHTML += "<span class='badOutput'>Inavlid Output Statement Syntax</span> " + "<br />";
                            }    
                    
                            
                            
                            break;
                            
                        default :
                            return outputBoxElement.innerHTML += "<span class='badOutput'>Syntax Error, Please Check your command spelling and case</span><br />";
                            break;
                    }
                    
                    
                }
            }
            
            /**
             * <h1>Check Begin</h1>
             * <p>Used to check for begin tag at the start of the string line that is passed in</p>
             **
             * <p>@param <b>String</b> - Statement - the line to check the tag for</p>
             * <p>@exp inputBoxElement, statement</p>
             * <p>@returns <b>Boolean</b> - True if begin tag is present</p>
             */
            function checkBegin(statement)
            {
                var splitStatement = statement.split(" ");
                var returnBoolean = false;
                
                if(splitStatement[0] === "BEGIN")
                {
                    returnBoolean = true;
                }
                
                return returnBoolean;
            }
            
            /**
             * <h1>Check End</h1>
             * <p>Used to check for end tag at the start of the string line that is passed in</p>
             **
             * <p>@param <b>String</b> - Statement - the line to check the tag for</p>
             * <p>@exp inputBoxElement, statement</p>
             * <p>@returns <b>Boolean</b> - True if end tag is present</p>
             */
            function checkEnd(statement)
            {
                var splitStatement = statement.split(" ");
                var returnBoolean = false;
                
                if(splitStatement[0] === "END")
                {
                    returnBoolean = true;
                }
                
                return returnBoolean;
            }
            
            /**
             * <h1>Search Array List</h1>
             *  <p>Search the arraylist for the passed in key and return its 
             * index that its found in. array.length is returned when not found
             * </p>
             **
             *  <p>@param <b>String</b> - statement - the statement to find</p>
             *  <p>@param <b>ArrayList</b> - arrayList - the array list to search</p>
             *  <p>@return <b>Int</b> - the index that the item was found in</p>
             */
            function searchArrayList(statement, arrayList)
            {
                var index = 0;
                var found = false;
                
                while(!found && index < arrayList.length)
                {
                    if (arrayList[index].name === statement) 
                    {
                        found = true; 
                    }
                    else
                    {
                        index++;
                    }
                }
                
                return index;
            }
            
        </script>
    </head>
    <body>
        <h1>Input Text Below</h1>
        <h3>Current Tags</h3>
        <ul>
            <li>BEGIN</li>
            <li>CALC</li>
            <li>OUTPUT</li>
            <li>END</li>
        </ul>
        <div style="width: 1000px;">
            <div style="margin-left: 45px; margin-right: 45px; width: 400px; height: 400px; float: left">
                <textarea name="inputBox" id="inputBox" style="width: 400px; height: 300px;" >
BEGIN
CALC a = 2 + 2
CALC abc = 2 * 2 * 3
CALC a123 = 2 + 2 * 3
OUTPUT abc
OUTPUT "Meow Mix"
OUTPUT "2 + 2"
OUTPUT 2 * (2 + 2)
END</textarea>
            </div>
            <div style="margin-left: 45px; margin-right: 45px; width: 400px; height: 400px; float: right">
                <div id="outputBox">Click Submit to compile</div>
            </div>
            <button type="button" onClick="return startProgram()">Submit</button>   
        </div>
        <br />
        <br />
        <br />
        
        
        
        
        
        <div><h1>Few Example Tests</h1>
        <textarea style="width: 250px; height: 250px;">
BEGIN
CALC a = 2 + 2
CALC abc = 2 * 2 * 3
CALC a123 = 2 + 2 * 3

OUTPUT abc
OUTPUT "Meow Mix"
OUTPUT "2 + 2"
OUTPUT 2 * (2 + 2)
OUTPUT ab
END</textarea>
        <textarea style="width: 250px; height: 250px;">
BEGIN
CALC a = 2 + 2
CALC abc = 2 * 2 * 3
CALC a123 = 2 + 2 * 3
OUTPUT abc
OUTPUT "Meow Mix"
OUTPUT "2 + 2"
OUTPUT " * (2 + 2)
END</textarea>
        <textarea style="width: 250px; height: 250px;">
OUTPUT "No BEGIN"
END</textarea>
        <textarea style="width: 250px; height: 250px;">
BEGIN
OUTPUT "No END"</textarea>
        <textarea style="width: 250px; height: 250px;">
BEGIN
CALC a = 2 + 2
CALC abc = 2 * 2 * 3
CALC a123 = 2 + 2 * 3
OUTPUT abc
OUTPUT "Meow\n1 Mix"
OUTPUT "2 + 2"
OUTPUT 2 * (2 + 2)
END</textarea>
        
        </div>
         

  
    </body>
</html>
