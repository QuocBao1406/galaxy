const io = require("socket.io")(3000, {
  cors: {
    origin: "*"
  }
});

const users = {}; // user_id => socket.id

io.on("connection", (socket) => {
  console.log("User connected:", socket.id);

  socket.on("register", (user_id) => {
    users[user_id] = socket.id;
    console.log("Registered user:", user_id);
  });

  socket.on("send_message", (data) => {
    const { from_user_id, to_user_id, conversation_id, message, image_path } = data;

    // Gửi về cho chính sender
    io.to(users[from_user_id]).emit("receive_message", data);

    // Gửi về cho người nhận nếu đang online
    if (users[to_user_id]) {
      io.to(users[to_user_id]).emit("receive_message", data);
    }
  });

  socket.on("disconnect", () => {
    for (let id in users) {
      if (users[id] === socket.id) {
        delete users[id];
        break;
      }
    }
    console.log("User disconnected:", socket.id);
  });
});