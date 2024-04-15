// //<------------------------ Display Loading ---------------------------->
// function displayLoading() {
//   const loading = document.getElementById("loadingLogo");

//   if (!loading) {
//     return;
//   } else {
//     var currentDate = new Date();
//     var currentDay = currentDate.toISOString().split("T")[0];
//     if (document.cookie.indexOf("visited_loading_" + currentDay) === -1) {
//       document.cookie =
//         "visited_loading_" +
//         currentDay +
//         "=true; expires=" +
//         new Date(
//           currentDate.getFullYear(),
//           currentDate.getMonth(),
//           currentDate.getDate() + 1,
//         ).toUTCString() +
//         "; path=/";

//       loading.style.display = "flex";
//     } else {
//       loading.style.display = "none";
//     }
//   }
// }

// // Afișează banner-ul imediat după încărcarea scripturilor în head
// displayLoading();
// //<---------------------- End Display Loading -------------------------->
