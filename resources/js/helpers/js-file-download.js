module.exports = function (data, filename, mime, bom) {
  var temp = document.createElement("a");
  temp.style.display = "none";
  temp.href = "/youtube/download";
  document.body.appendChild(temp);
  temp.click();
  setTimeout(() => {
    document.body.removeChild(temp);
  }, 200);
}