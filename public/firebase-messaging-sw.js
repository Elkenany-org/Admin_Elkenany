importScripts('https://www.gstatic.com/firebasejs/9.0.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.2/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing the generated config
var firebaseConfig = {
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

// Retrieve firebase messaging
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);

    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };

    return self.registration.showNotification(
        title,
        options,
    );
});