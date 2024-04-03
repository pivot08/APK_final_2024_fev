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