importScripts('https://www.gstatic.com/firebasejs/8.2.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.0/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "AIzaSyD2Z4iK1UuZFoCOJN_9i87JgQTq12_GymY",
    authDomain: "elkenany-fbdc6.firebaseapp.com",
    databaseURL: "https://elkenany-fbdc6.firebaseio.com",
    projectId: "elkenany-fbdc6",
    storageBucket: "elkenany-fbdc6.appspot.com",
    messagingSenderId: "711464214583",
    appId: "1:711464214583:web:e417d914fc8dea121b5fb2",
    measurementId: "G-NFRYJC0Q5F"
  };

  firebase.initializeApp(firebaseConfig);

  const messaging = firebase.messaging();
// Initialize the Firebase app in the service worker by passing the generated config
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    // Customize notification here
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});