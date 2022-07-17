function formhash(form, password) {
   var p = document.createElement("input");
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = hex_sha512(password.value);
   password.value = "";
   form.submit();
}

function formhash2(form, password1, password2) {
   var p1 = document.createElement("input");
   var p2 = document.createElement("input");
   form.appendChild(p1);
   form.appendChild(p2);
   
   p1.name = "p1";
   p1.type = "hidden";
   p1.value = hex_sha512(password1.value);
   
   p2.name = "p2";
   p2.type = "hidden";
   p2.value = hex_sha512(password2.value);
   
   password1.value = "";
   password2.value = "";
   form.submit();
}