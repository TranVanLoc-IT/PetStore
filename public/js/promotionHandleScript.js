

function DeleteDetails(id){
  fetch("/khuyen-mai/" + id, {method:'DELETE'})
  .then(response=>alert(response))
  .error(err => alert(err));
}