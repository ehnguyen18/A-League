function validateForm(fname){
    a=fname;
    final=false;
    checkS1(a);
    checkS2(a);
    if (checkS1(a))
    if (checkS2(a)) {
        final=true;
        document.getElementByID("final-error").style.display = "none";
    }
    else {
        final=false;
        document.getElementByID("final-error").style.display = "block";
    }
    return final;
}

function checkS1(fname){ //https://stackoverflow.com/a/14406066
    var a=document.getElementsByName("s1")[fname].value
    var final=false;
    var patt=/[0-9]/;
    if (a.length==0){
        document.getElementsByName("s1-error")[fname].style.display = "inline-block";
        document.getElementsByName("s1")[fname].style.border = "2px solid #ff0000";
        final=false;
        }
    else {
        if (!patt.test(a)){
            document.getElementsByName("s1-error")[fname].style.display = "inline-block";
            document.getElementsByName("s1")[fname].style.border = "2px solid #ff0000";
            final=false;
        }
        else {
            if (a<0){
                document.getElementsByName("s1-error")[fname].style.display = "inline-block";
                document.getElementsByName("s1")[fname].style.border = "2px solid #ff0000";
                final=false;
            }
            else{
            document.getElementsByName("s1-error")[fname].elements[s1-error].style.display = "none";
            document.getElementsByName("s1")[fname].style.border = "1px solid";
            final=true;
            }
        }
    }
}

function checkS2(fname){
    var a=document.getElementsByName("s2")[fname].value
    var final=false;
    var patt=/[0-9]/;
    if (a.length==0){
        document.getElementsByName("s2-error")[fname].style.display = "inline-block";
        document.getElementsByName("s2")[fname].style.border = "2px solid #ff0000";
        final=false;
        }
    else {
        if (!patt.test(a)){
            document.getElementsByName("s2-error")[fname].style.display = "inline-block";
            document.getElementsByName("s2")[fname].style.border = "2px solid #ff0000";
            final=false;
        }
        else {
            if (a<0){
                document.getElementsByName("s2-error")[fname].style.display = "inline-block";
                document.getElementsByName("s2")[fname].style.border = "2px solid #ff0000";
                final=false;
            }
            else{
            document.getElementsByName("s2-error")[fname].elements[s1-error].style.display = "none";
            document.getElementsByName("s2")[fname].style.border = "1px solid";
            final=true;
            }
        }
    }
}