const chatContainer = document.getElementById("chatContainer");
const sendMsgButton = document.getElementById("sendMsgButton");
const message = document.getElementById("message");
const allChats = document.querySelectorAll('.chat__body');

sendMsgButton.addEventListener('click', function () {
    let body = document.createElement('div');
    body.className = "message__body__darker";
    body.innerHTML = `
     <div class="chat__person">
            <img class="img"
                 src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1400&q=80"
                 alt="Avatar">
            <p class="person__name">ضياء محمد</p>
        </div>
        <div class="message margin__right">
            <p>${message.value}</p>
            <span class="time__light">${new Date().toLocaleTimeString()}</span>
        </div>
    `;
    chatContainer.appendChild(body);
    message.value = '';
    chatContainer.scrollTop = chatContainer.scrollHeight;
})
allChats.forEach((element) => {
    element.addEventListener('click', function () {
        let arr = document.querySelectorAll('.chat__active');
        arr.forEach((elm) => {
            elm.classList.remove('chat__active');
        })
        element.classList.add('chat__active')
    });
})
