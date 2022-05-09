// Open the add item modal on the index page
function openModal() {
  var addModal = document.getElementById("addItemsModal");
  var overlay = document.getElementById("overlay");
  addModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
  document.getElementById("newItem").focus();
}
//Close the add item model on the index page when clicking outside the modal.
function closeModal() {
  var addModal = document.getElementById("addItemsModal");
  var overlay = document.getElementById("overlay");
  addModal.classList.add("hidden");
  overlay.classList.add("hidden");
}
// Open the add list modal on the lists page
function openListModal() {
  var addModal = document.getElementById("addListModal");
  var overlay = document.getElementById("overlay");
  addModal.classList.remove("hidden");
  addModal.classList.add("flex");
  overlay.classList.remove("hidden");
  document.getElementById("newList").focus();
}
//Close the add list modal on the lists page when clicking outside the modal.
function closeListModal() {
  var addModal = document.getElementById("addListModal");
  var overlay = document.getElementById("overlay");
  addModal.classList.add("hidden");
  overlay.classList.add("hidden");
}

//Open or close the list modal on the list page
function showList(listId) {
  var listID = listId;
  var list = document.getElementById("listID" + listID);
  var listArrow = document.getElementById("listOpenID" + listID);

  if (list.classList.contains("hidden")) {
    list.classList.remove("hidden");
    listArrow.classList.add("rotate-180");
  } else {
    list.classList.add("hidden");
    listArrow.classList.remove("rotate-180");
  }
}
