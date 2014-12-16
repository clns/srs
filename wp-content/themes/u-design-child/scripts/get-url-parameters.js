function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function setDropDownLink(bootcampID) {
  $('#select-bootcamp option').filter(function() {
    return $(this).val() === bootcampID;
  }).prop('selected', true);
}
