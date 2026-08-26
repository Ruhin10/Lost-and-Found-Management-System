console.log("Validation Connected");


function validateForm(){

    const itemTitle = document.getElementById("itemTitle").value;
    const dateFound = document.getElementById("dateFound").value;
    const description = document.getElementById("description").value;
    const location = document.getElementById("location").value;
    const contact = document.getElementById("contact").value;


    let isValid = true;


    // Item Title Validation
    if(itemTitle == ""){
        document.getElementById("titleError").innerHTML =
        "Item title is required";
        isValid = false;
    }
    else if(itemTitle.length < 3){
        document.getElementById("titleError").innerHTML =
        "Item title must be at least 2 characters";
        isValid = false;
    }
    else{
        document.getElementById("titleError").innerHTML = "";
    }



    // Date Validation
    if(dateFound == ""){
        document.getElementById("dateError").innerHTML =
        "Date is required";
        isValid = false;
    }
    else{
        document.getElementById("dateError").innerHTML = "";
    }



    // Description Validation
    if(description == ""){
        document.getElementById("descError").innerHTML =
        "Description is required";
        isValid = false;
    }
   
    else{
        document.getElementById("descError").innerHTML = "";
    }



    // Location Validation
    if(location == ""){
        document.getElementById("locationError").innerHTML =
        "Location is required";
        isValid = false;
    }
    else{
        document.getElementById("locationError").innerHTML = "";
    }



    // Contact Validation
    if(contact == ""){
        document.getElementById("contactError").innerHTML =
        "Contact number is required";
        isValid = false;
    }
    else if(contact.length < 12){
        document.getElementById("contactError").innerHTML =
        "Enter valid phone number";
        isValid = false;
    }
    else{
        document.getElementById("contactError").innerHTML = "";
    }



    return isValid;

}