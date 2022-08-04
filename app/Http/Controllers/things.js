const app = require("express")();
const server = require("http").createServer(app);
const cors = require("cors");

const io = require("socket.io")(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});
app.use(cors());

const PORT = process.env.PORT || 5000;

app.get("/", (req, res)=> {
    res.send("Server is running..");
});
let onlineUsers = [];
const addNewUser = (username,socketId) => {
    !onlineUsers.some(user=>user.username === username) && onlineUsers.push({username: username,socketId:socketId});

}
const removeUser = (socketId) => {
    onlineUsers = onlineUsers.filter(user => user.socketId !== socketId);
}
const getUser = (username) => {
    return onlineUsers.find(user => user.username === username);
}
let messages = [];
const deleteMsgs = (sender_id) => {
    messages = onlineUsers.filter(user => user.sender_id !== sender_id);
}
io.on('connection', ( socket) => {
    socket.on('newUser', (username) => {
        addNewUser(username, socket.id);
    io.to(socket.id).emit('online_users', onlineUsers);

    });
    onlineUsers.forEach ((user)=>{
        io.to(user.socketId).emit('online_users', onlineUsers);
     });
    socket.on('sendMessage',({senderName,idGoing,text })=> {
        let receiver = getUser(`${idGoing}`);
        if(receiver){
          receiver = receiver
        }else{
            receiver= {username: '', socketId: ''};
        }
        const user = getUser(`${senderName}`);
        
        io.to(receiver.socketId).emit("getNotification", {sender_id:senderName,receiver_id:idGoing,message:text });
        io.to(user.socketId).emit("getNotification", {sender_id:senderName,receiver_id:idGoing,message:text });

    });
    socket.on('hideCamera' , (data) => {

        io.to(data.userMe).emit("getHide", {hideCamera:data.cameratoshide });

    });
    socket.on('showCamera' , (data) => {

        io.to(data.userMe).emit("getShow", {showCamera:data.cameratoshow });

    });
    socket.on('unmuteCam' , (data) => {

        io.to(data.userMe).emit("camunmute", {unmuteCam:data.unmuteCam });

    });
    socket.on('muteCam' , (data) => {

        io.to(data.userMe).emit("cammute", {muteCam:data.muteCam });

    });

    socket.on('sendMessageVid',({senderName,idGoing,text })=> {
        var now = new Date();
       var hour = now.getHours();
       var minutes = now.getMinutes();
          messages.push({sender_id: senderName,receiver_id:idGoing,message:text,time: `${hour}:${minutes}`});
        onlineUsers.forEach ((user)=>{
        // io.to(user.socketId).emit("getNotification", {sender_id:senderName,receiver_id:idGoing,message:text });
        io.to(user.socketId).emit("getNotification", messages);

    })
    });
    socket.emit('me', socket.id);

    socket.on('disconnect', ()=> {
        removeUser(socket.id);
        onlineUsers.forEach ((user)=>{
    io.to(user.socketId).emit('online_users', onlineUsers);
        });

        messages = [];
        // socket.broadcast.emit('callended');

    });
    socket.on("calluser", ({userToCall, signalData, from, name})=> {

        let receiver = getUser(userToCall);
        if(receiver){
            receiver = receiver
          }else{
              receiver= {username: '', socketId: ''};
          }
        io.to(receiver.socketId).emit("calluser", {signal: signalData, from, name });

    });
    socket.on("answercall", (data)=>{
        io.to(data.to).emit("callaccepted", {signal:data.signal,userToAnswer:data.userToAnswer});
    })
    socket.on('radio', function(data) {
        socket.broadcast.to(data.url).emit('voice', data.blob);
        socket.join(data.url);
    });
});

server.listen(PORT, ()=> console.log(`Server is listening on port ${PORT}`));
