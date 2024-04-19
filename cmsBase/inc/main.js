function formatDate(date) {
   // Get the components of the date
   var year = date.getFullYear();
   var month = padWithZero(date.getMonth() + 1); // Months are zero-based
   var day = padWithZero(date.getDate());
   var hours = padWithZero(date.getHours());
   var minutes = padWithZero(date.getMinutes());
   var seconds = padWithZero(date.getSeconds());

   // Construct the formatted date string
   var formattedDate = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;

   return formattedDate;
}

// Function to pad single digits with leading zero
function padWithZero(number) {
   return (number < 10 ? '0' : '') + number;
}

var clickQuant = 0;
function backIndice() {
   clickQuant++;
   if (clickQuant == 1) {
      window.location.href = 'selection.html?' + paramsOrigin;
   }
}

function backIndex(linkHome) {
   const timer = setTimeout(() => {
      clearTimeout(timer);
      runTimer(window.atob(linkHome));
   }, 60000);
}

function clearTimer() {
   clearTimeout(pressTimer);
}

function runTimer(linkHome) {
   window.location.href = linkHome;
}

function fixHomeLink(linkHome) {
   var links = document.getElementsByTagName('a');
   for (var i = 0; i < links.length; i++) {
      if (links[i].href.indexOf('javascript:history.') < 0)
         links[i].href = window.atob(linkHome);
   }
}