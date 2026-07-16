function registration(){

let f=document.getElementById("fname").value;

let l=document.getElementById("lname").value;

let s=document.getElementById("sid").value;

let e=document.getElementById("email").value;

let c=document.getElementById("credit").value;

let d=document.getElementById("dept").value;

let ok=true;

fnameError.innerHTML="";
lnameError.innerHTML="";
sidError.innerHTML="";
emailError.innerHTML="";
creditError.innerHTML="";
deptError.innerHTML="";


if(f==""){
fnameError.innerHTML="Required";
ok=false;
}


if(l==""){
lnameError.innerHTML="Required";
ok=false;
}


if(s==""){
sidError.innerHTML="Required";
ok=false;
}


else if(!s.includes("-")){
sidError.innerHTML="Must contain -";
ok=false;
}


if(!e.includes("@student.aiub.edu")){
emailError.innerHTML="Invalid";
ok=false;
}

if(c<0 || c>=148){
creditError.innerHTML="Invalid";
ok=false;
}

if(d==""){
deptError.innerHTML="Required";
ok=false;
}

if(ok){

let row=studentList.insertRow();

row.insertCell(0).innerHTML=f+" "+l;

row.insertCell(1).innerHTML=s;

fname.value="";
lname.value="";
sid.value="";
email.value="";
credit.value="";
dept.value="";

}

return false;

}