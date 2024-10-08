

function DeleteDetails(id){
  fetch("/khuyen-mai/" + id, {method:'DELETE'})
  .then(response=>response.json())
  .then(response=>alert(response.Inform))
  .error(err => alert(err));
}